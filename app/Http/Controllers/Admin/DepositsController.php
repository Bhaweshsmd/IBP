<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Banners;
use App\Models\Bookings;
use App\Models\Constants;
use App\Models\GlobalFunction;
use App\Models\GlobalSettings;
use App\Models\SalonCategories;
use App\Models\Salons;
use App\Models\Services;
use App\Models\UserNotification;
use App\Models\Users;
use App\Models\UserWalletRechargeLogs;
use App\Models\UserWalletStatements;
use App\Models\UserWithdrawRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Twilio\Jwt\ClientToken;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\Services\TwilioService;
use DB;
use App\Jobs\SendEmail;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Jobs\SendNotification;
use App\Models\Fee;
use App\Models\EventType;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use App\Models\EmailTemplate;
use App\Models\SalonBookingSlots;
use App\Models\Taxes;
use App\Models\Card;
use App\Models\CardsTransaction;
use App\Jobs\PlatformEarning;
use App\Models\AdminWithdrawRequest;
use App\Models\PlatformEarningHistory;
use App\Models\RevenueSetting;
use App\Models\PlatformData;
use App\Models\Admin;
use App\Models\AdminEmailTemplate;
use App\Models\CardmembershipFee;
use App\Models\BankDetail;
use App\Jobs\SendAdminNotification;
use App\Models\NotificationTemplate;
use App\Models\AdminNotificationTemplate;
use App\Models\Device;
use App\Models\Language;

class DepositsController extends Controller
{
    function index()
    {
        return view('deposits.index');
    }
    
    function list(Request $request)
    {
        $totalData =  UserWalletRechargeLogs::count();
        $rows = UserWalletRechargeLogs::orderBy('id', 'DESC')->get();
        $settings = GlobalSettings::first();

        $result = $rows;

        $columns = array(
            0 => 'id',
            1 => 'fullname',
            2 => 'identity',
            3 => 'username',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalFiltered = $totalData;
        if (empty($request->input('search.value'))) {
            $result = UserWalletRechargeLogs::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  UserWalletRechargeLogs::Where('amount', 'LIKE', "%{$search}%")
                ->orWhere('transaction_summary', 'LIKE', "%{$search}%")
                ->orWhere('transaction_id', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = UserWalletRechargeLogs::Where('amount', 'LIKE', "%{$search}%")
                ->orWhere('transaction_summary', 'LIKE', "%{$search}%")
                ->orWhere('transaction_id', 'LIKE', "%{$search}%")
                ->count();
        }
        $data = array();
        foreach ($result as $k=>$item) {
            $gateway = GlobalFunction::detectPaymentGateway($item->gateway);
            if($gateway=="Stripe"){
                $gateway="Debit/Credit Card";
            }
            $user = "";
            $name="";
            if ($item->user != null) {
                $user = '<a href="' . route('users.profile', $item->user->id) . '"><span class="badge bg-primary text-white">' . $item->user->profile_id . '</span></a>';
                $name= '<a href="' . route('users.profile', $item->user->id) . '"><span class="badge bg-primary text-white">' . $item->user->first_name.' '.$item->user->last_name. '</span></a>';
            }
            
            $fee=0;
            $total=0;
            
            if($item->transaction_id=="ADDED_BY_ADMIN"){
                $fee=0;
                $total=0;
            }
            $transaction_type=  substr($item->transaction_id,0,2);
             if($transaction_type=="pi"){
                 $transaction_summary =json_decode($item->transaction_summary);
                //  dd($transaction_summary->amount);
                $fee=($transaction_summary->amount/100)-$item->amount;
                $total=$transaction_summary->amount/100;
            }
             if($transaction_type=="to"){
                 $transaction_summary =json_decode($item->transaction_summary);
                 if($transaction_summary!=null){
                //  dd($transaction_summary->amount);
                    $fee=($transaction_summary->amount/100)-$item->amount;
                    $total=$transaction_summary->amount/100; 
                 }else{
                  $fee=0;
                   $total=0;
                 }
            }
            

            $data[] = array(
                ++$k,
                $user,
                $name,
                $settings->currency . number_format($item->amount, 2, '.', ','),
                $settings->currency . number_format($fee, 2, '.', ','),
                $settings->currency . number_format($total, 2, '.', ','),
                $gateway,
                $item->transaction_id,
                // $item->transaction_summary,
                GlobalFunction::formateTimeString($item->created_at),
            );
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => $totalFiltered,
            "data"            => $data
        );
        echo json_encode($json_data);
        exit();
    }
    
    function user_wallet_topup(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'amount' => 'required',
            'transaction_id' => 'required',
            'transaction_summary' => 'required',
        ];
         
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        $settings = GlobalSettings::first();

        $user = Users::find($request->user_id);
        if ($user == null) {
            return response()->json(['status' => false, 'message' => "User doesn't exists!"]);
        }
        
        $fee=Fee::where('type','deposit')->first();
        if($fee->maximum < $request->amount ){
            $message= "Amount should be maximum ". $settings->currency.number_format($fee->maximum,2)." and minimum ". $settings->currency.number_format($fee->minimum,2);
            return response()->json(['status' => false, 'message' =>$message]);    
        }
        
        if($fee->minimum > $request->amount){
            $message= "Amount should be maximum ". $settings->currency.number_format($fee->maximum,2)." and minimum ". $settings->currency.number_format($fee->minimum,2);
            return response()->json(['status' => false, 'message' =>$message]);    
        }
        
        $total_amount=$request->amount+($request->amount*$fee->charge_percent)/100;
        
        $charge_amount=($request->amount*$fee->charge_percent)/100;
        
        $user->wallet = $user->wallet + $request->amount;
        $user->save();

        GlobalFunction::addUserStatementEntry(
            $user->id,
            null,
            $request->amount,
            Constants::credit,
            Constants::deposit,
            $request->transaction_summary,
            $fee->charge_percent??0,
            $charge_amount,
           $total_amount
        );

        $rechargeLog = new UserWalletRechargeLogs();
        $rechargeLog->user_id = $user->id;
        $rechargeLog->amount = $request->amount;
        $rechargeLog->gateway = $request->gateway;
        $rechargeLog->transaction_id = $request->transaction_id;
        $rechargeLog->transaction_summary = $request->transaction_summary;
        $rechargeLog->save();
        
        try{
            
            $wallet_details=UserWalletStatements::where('user_id',$user->id)->latest()->first();  
            
            $user_name=ucfirst($user->first_name.' '.$user->last_name);
            $created_at=date('D, d F Y',strtotime($wallet_details->created_at));
            $amount= $settings->currency.number_format($request->amount,2);
            $charge_amount= $settings->currency.number_format($charge_amount,2);
            $total_amount= $settings->currency.number_format($total_amount,2);

            $check_device = Device::where('user_id', $user->id)->first();
            if(!empty($check_device->language)){
                $act_lang = $check_device->language;
            }else{
                $act_lang = '1';
            }
            
            $email_template = EmailTemplate::find(4);
            if($act_lang == '1'){
                $subject = $email_template->email_subjects;
                $content = $email_template->email_content;
            }elseif($act_lang == '2'){
                $subject = $email_template->email_subject_pap;
                $content = $email_template->email_content_pap;
            }elseif($act_lang == '3'){
                $subject = $email_template->email_subject_nl;
                $content = $email_template->email_content_nl;
            }
            
            $content = str_replace(["{user}","{uuid}","{created_at}","{amount}","{fee}","{total}"], [$user_name,$wallet_details->transaction_id,$created_at,$amount,$charge_amount,$total_amount,],$content);
             
            $details=[         
                "subject"=>$subject ,
                "message"=>$content,
                "to"=>$user->email,
            ];
            send_email($details);
          
            $superAdmin=Admin::where('user_type',1)->first();
            $bookingsuccesemail=AdminEmailTemplate::find(4);
            $admin_subject=$bookingsuccesemail->email_subjects??'';
            $admin_content=$bookingsuccesemail->email_content??''; 
            $admin_content = str_replace(["{user}","{uuid}","{created_at}","{amount}","{fee}","{total}"], [$user_name,$wallet_details->transaction_id,$created_at,$amount,$charge_amount,$total_amount,],$admin_content);
             
            $details=[         
                "subject"=>$admin_subject ,
                "message"=>$admin_content,
                "to"=>$superAdmin->email,
            ];
            send_email($details);
          
            $details=[         
                "subject"=>$admin_subject ,
                "message"=>$admin_content,
                "to"=>$settings->admin_email,
            ];
            send_email($details);
          
            dispatch(new PlatformEarning($wallet_details->charge_amount,$wallet_details->id,'wallet_topup'));

            $type="add_fund";
            
            $notification_template = NotificationTemplate::find(1);
            if($act_lang == '1'){
                $title = $notification_template->notification_subjects;
                $message = strip_tags($notification_template->notification_content);
            }elseif($act_lang == '2'){
                $title = $notification_template->notification_subject_pap;
                $message = strip_tags($notification_template->notification_content_pap); 
            }elseif($act_lang == '3'){
                $title = $notification_template->notification_subject_nl;
                $message = strip_tags($notification_template->notification_content_nl);
            }
            
            $message = str_replace(["{amount}"],[$amount],$message);
            
            $item = new UserNotification();
            $item->user_id = $user->id;
            $item->title = $title;
            $item->description = $message;
            $item->notification_type = $type;
            $item->temp_id = '1';
            $item->amount = $amount;
            $item->save();
            
            GlobalFunction::sendPushToUser($title, $message, $user);

            $adminNotification=AdminNotificationTemplate::find(1);
            $title=$adminNotification->notification_subjects??'';
            $title=str_replace(["{user}"],[$user_name],$title);
            $message=$adminNotification->notification_content??'';
            $message=str_replace(["{amount}","{user}"],[$amount,$user_name],$message);
            dispatch(new SendAdminNotification($title,$message,$type,$user->id));
          
        }catch(\Exception $e){
            return $data=['status' => false, 'message' =>$e->getMessage()];
        }
        return back()->with(['card_topup' => 'Money added to IBP Account Successfully']);
    }
}
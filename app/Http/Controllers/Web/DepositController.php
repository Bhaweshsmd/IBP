<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Constants;
use App\Models\GlobalFunction;
use App\Models\GlobalSettings;
use App\Models\SalonCategories;
use App\Models\SalonImages;
use App\Models\Salons;
use App\Models\ServiceImages;
use App\Models\Services;
use Illuminate\Support\Facades\Validator;
use App\Models\Users;
use App\Services\TwilioService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\Bookings;
use App\Jobs\SendEmail;
use App\Models\UserNotification;
use App\Models\PlatformData;
use App\Models\PlatformEarningHistory;
use App\Models\SalonEarningHistory;
use App\Models\SalonPayoutHistory;
use App\Models\SalonReviews;
use App\Models\SalonWalletStatements;
use Carbon\Carbon;
use Mockery\Generator\StringManipulation\Pass\ConstantsPass;
use PHPUnit\TextUI\XmlConfiguration\Constant;
use Symfony\Component\VarDumper\Caster\ConstStub;
use App\Jobs\SendNotification;
use App\Models\SalonBookingSlots;
use App\Models\Taxes;
use App\Models\Fee;
use App\Models\UserWalletStatements;
use DB;
use Illuminate\Validation\ValidationException;
use App\Models\EventType;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use App\Models\UserWalletRechargeLogs;
use App\Models\UserWithdrawRequest;
use App;
use App\Models\EmailTemplate;
use App\Models\AdminEmailTemplate;
use App\Models\Admin;
use Stripe;
use App\Models\Card;
use App\Models\CardsTransaction;
use App\Jobs\PlatformEarning;
use App\Models\SalonGallery;
use App\Models\SalonMapImage;
use App\Jobs\SendAdminNotification;
use App\Models\NotificationTemplate;
use App\Models\AdminNotificationTemplate;
use App\Models\Page;
use App\Models\Blog;
use App\Models\Coupons;
use App\Models\BankDetail;
use App\Models\WebBanner;
use App\Models\UsedCoupon;
use App\Models\Faqs;
use App\Models\Device;
use App\Models\Language;

class DepositController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService, Auth $auth)
    {  
        $this->middleware('CheckAccountVerification',['except' => ['accountVerification', 'accountVerificationOtp','login','logout']]);
        $this->twilioService = $twilioService;
    }
    
    function wallet_topup(Request $request)
    {
        $rules = [
            'amount' => 'required',
            'token_id' => 'required',
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        $settings = GlobalSettings::first();
        
        $user_id = Session::get('user_id');
        $total_amount = Session::get('total_amount');
        $amount = Session::get('amount');
        $charge_amount = Session::get('charge_amount');
        
        $user = Users::find($user_id);
        $fee = Fee::where('type','deposit')->first();
        
        Stripe\Stripe::setApiKey($settings->stripe_secret);
        $payment = Stripe\Charge::create ([
            "amount" => $total_amount * 100,
            "currency" => "usd",
            "source" => $request->token_id,
            "description" => "Deposit - IBP(Web)" 
        ]);
        $user->wallet = $user->wallet + $amount;
        $user->save();

        GlobalFunction::addUserStatementEntry(
            $user->id,
            null,
            $amount,
            Constants::credit,
            Constants::deposit,
            $request->transaction_summary,
            $fee->charge_percent??0,
            $charge_amount,
            $total_amount
        );
        
        $rechargeLog = new UserWalletRechargeLogs();
        $rechargeLog->user_id = $user->id;
        $rechargeLog->amount = $amount;
        $rechargeLog->gateway = 1;
        $rechargeLog->transaction_id = $request->token_id;
        $rechargeLog->transaction_summary = json_encode($payment);
        $rechargeLog->save();
        
        try{
            $wallet_details=UserWalletStatements::where('user_id',$user->id)->latest()->first();  
            $user_name=ucfirst($user->first_name.' '.$user->last_name);
            $created_at=date('D, d F Y',strtotime($wallet_details->created_at));
            $amount= $settings->currency.number_format($amount,2);
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
            $admin_content=str_replace(["{user}","{uuid}","{created_at}","{amount}","{fee}","{total}"], [$user_name,$wallet_details->transaction_id,$created_at,$amount,$charge_amount,$total_amount,],$admin_content);
            
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
            
            $message=str_replace(["{amount}"],[$amount],$message);
            
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
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }

        return GlobalFunction::sendSimpleResponse(true, 'Money added to IBP Account successfully!');
    }
    
    public function user_wallet(Request $request,$type=null)
    {
        $user_id=Session::get('user_id');
        $userDetails=Users::where('id',$user_id)->first();
        $data['settings'] = GlobalSettings::first();
        $data['type']=$type;
        $data['statement'] = UserWalletStatements::with(['user'])->where('user_id',$user_id)->latest()->get();
              
        if($type=='purchase'){
            $data['statement'] = UserWalletStatements::with(['user'])->where('user_id',$user_id)->where('type',1)->orderBy('id','DESC')->get(); //booking payment
        }elseif($type=='withdraw'){
            $data['statement'] = UserWalletStatements::with(['user'])->where('user_id',$user_id)->where('type',2)->orderBy('id','DESC')->get(); // withdraw
        }elseif($type=='refund'){
            $data['statement'] = UserWalletStatements::with(['user'])->where('user_id',$user_id)->where('type',3)->orderBy('id','DESC')->get(); //  refund
        }elseif($type=='deposit'){
             $data['statement'] = UserWalletStatements::with(['user'])->where('user_id',$user_id)->where('type',0)->orderBy('id','DESC')->get(); //   deposit
        }else{
            $data['statement'] = UserWalletStatements::with(['user'])->where('user_id',$user_id)->orderBy('id','DESC')->get(); 
        }
        $data['total_transaction'] = UserWalletStatements::with(['user'])->where('user_id',$user_id)->sum('amount');
            
        $data['total_fund_added'] = UserWalletStatements::with(['user'])->where('user_id',$user_id)->where('booking_id',null)->where('cr_or_dr',1)->sum('amount');
        $data['total_withdraw']  =UserWithdrawRequest::where('user_id',$user_id)->where('status','!=',2)->sum('amount');
        $data['total_withdraw_pending']  =UserWithdrawRequest::where('user_id',$user_id)->where('status',0)->sum('amount');
        $data['total_withdraw_success']  =UserWithdrawRequest::where('user_id',$user_id)->where('status',1)->sum('amount');
        
        $data['total_debit'] = UserWalletStatements::with(['user'])->where('user_id',$user_id)->where('cr_or_dr',0)->sum('amount');
        $data['userDetails']=$userDetails;
        
        $data['total_bookings']=Bookings::where('user_id', $user_id)->sum('payable_amount');
        $data['total_refund'] = UserWalletStatements::with(['user'])->where('user_id',$user_id)->where('type',3)->sum('amount'); //  refund
        $data['fee']=Fee::where('type','deposit')->first();
        $data['withdraw_fee']=Fee::where('type','withdraw')->first();
        
        return view('web.users.wallet',$data);
    }
}
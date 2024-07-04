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

class WithdrawController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService, Auth $auth)
    {
        $this->twilioService = $twilioService;
    }
    
    function index()
    {   
        $data['ibp_revenue'] = PlatformEarningHistory::whereIn('type', ['wallet_booking', 'card_booking', 'user_withdraw'])->sum('ibp_revenue');
        $data['total_withdrawal'] = AdminWithdrawRequest::where('status','!=',2)->sum('amount');
        $data['card_memb_fee'] =  CardmembershipFee::sum('fee');
        
        $data['pending_withdrawal']=AdminWithdrawRequest::where('status',0)->sum('amount');
        $data['completed_withdrawal']=AdminWithdrawRequest::where('status',1)->sum('amount');
        $data['rejected_withdrawal']=AdminWithdrawRequest::where('status',2)->sum('amount');
        
        $data['withdraw_fee']=Fee::where('type','admin_withdraw')->first();
        $data['settings'] = GlobalSettings::first();
        
        $admin_detials = Admin::where('user_name',session()->get('user_name'))->first();
        $data['banks'] = BankDetail::where('user_id', $admin_detials->user_id)->where('user_type', 'admin')->orderBy('id', 'DESC')->get();
        return view('withdrawals.index',$data);
    }
    
    function pending(Request $request)
    {
        $totalData =  AdminWithdrawRequest::where('status', Constants::statusWithdrawalPending)->with('user')->count();
        $rows = AdminWithdrawRequest::where('status', Constants::statusWithdrawalPending)->with('user')->orderBy('id', 'DESC')->get();
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
            $result = AdminWithdrawRequest::where('status', Constants::statusWithdrawalPending)
                ->with('user')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  AdminWithdrawRequest::where('status', Constants::statusWithdrawalPending)
                ->where(function ($query) use ($search) {
                    $query->where('request_number', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%")
                        ->orWhere('holder', 'LIKE', "%{$search}%")
                        ->orWhereHas('user', function ($query) use ($search) {
                            $query->Where('fullname', 'LIKE', "%{$search}%");
                        });
                })
                ->with('user')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = AdminWithdrawRequest::where('status', Constants::statusWithdrawalPending)
                ->with('user')
                ->where(function ($query) use ($search) {
                    $query->where('request_number', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%")
                        ->orWhere('holder', 'LIKE', "%{$search}%")
                        ->orWhereHas('user', function ($query) use ($search) {
                            $query->Where('fullname', 'LIKE', "%{$search}%");
                        });
                })
                ->count();
        }
        $data = array();
        foreach ($result as $k=>$item) {

            $holder = '<span class="text-dark font-weight-bold font-14">' . $item->holder . '</span>';
            $bank_title = '<div class="bank-details"><span>' . $item->bank_title . '</span>';
            $account_number = '<span>' . __('Account : ') .  $item->account_number . '</span>';
            $swift_code = '<span>' . __('Swift Code : ') . $item->swift_code . '</span></div>';
            $bankDetails = $holder . $bank_title . $account_number . $swift_code;

            $user = "";
            $name="";
            if ($item->user != null) {
                $user = '<a href="javascript:void(0)"><span class="badge bg-primary text-white">' . $item->user->user_name . '</span></a>';
                $name='<a href="javascript:void(0)"><span class="badge bg-primary text-white">' . $item->user->first_name." ".$item->user->last_name. '</span></a>';
            }

            $amount = '<span class="text-dark font-weight-bold font-16">' . $settings->currency . $item->amount . '</span><br>';
            $status = '<span class="badge bg-warning text-white"rel="' . $item->id . '">' . __('Pending') . '</span>';
            $amountData = $amount . $status;
            
            $fee= '<span class="text-dark font-weight-bold font-16">' .  $settings->currency.number_format($item->amount*$item->charge_percent/100,2) . '</span><br>';
            $amounttotransfer='<span class="text-dark font-weight-bold font-16">' . $settings->currency.number_format($item->amount-$item->amount*$item->charge_percent/100,2) . '</span><br>';

            if(has_permission(session()->get('user_type'), 'edit_admin_withdrawal')){
                $complete = '<a href="" class="mr-2 btn btn-success text-white complete" rel=' . $item->id . ' >' . __("Complete") . '</a>';
            }else{
                $complete = '';
            }
            
            if(has_permission(session()->get('user_type'), 'delete_admin_withdrawal')){
                $reject = '<a href="" class="mr-2 btn btn-danger text-white reject" rel=' . $item->id . ' >' . __("Reject") . '</a>';
            }else{
                $reject = '';
            }
            
            $action =  $complete . $reject;


            $data[] = array(
                ++$k,
                $item->request_number,
                 $user,
                 $name,
                 $amountData,
                $bankDetails,
                GlobalFunction::formateTimeString($item->created_at),
                $action
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
    
    function completed(Request $request)
    {
        $totalData =  AdminWithdrawRequest::where('status', Constants::statusWithdrawalCompleted)->with('user')->count();
        $rows = AdminWithdrawRequest::where('status', Constants::statusWithdrawalCompleted)->with('user')->orderBy('id', 'DESC')->get();
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
            $result = AdminWithdrawRequest::where('status', Constants::statusWithdrawalCompleted)
                ->with('user')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  AdminWithdrawRequest::where('status', Constants::statusWithdrawalCompleted)
                ->where(function ($query) use ($search) {
                    $query->where('request_number', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%")
                        ->orWhere('holder', 'LIKE', "%{$search}%")
                        ->orWhere('summary', 'LIKE', "%{$search}%")
                        ->orWhereHas('user', function ($query) use ($search) {
                            $query->Where('fullname', 'LIKE', "%{$search}%");
                        });
                })
                ->with('user')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = AdminWithdrawRequest::where('status', Constants::statusWithdrawalCompleted)
                ->with('user')
                ->where(function ($query) use ($search) {
                    $query->where('request_number', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%")
                        ->orWhere('holder', 'LIKE', "%{$search}%")
                        ->orWhere('summary', 'LIKE', "%{$search}%")
                        ->orWhereHas('user', function ($query) use ($search) {
                            $query->Where('fullname', 'LIKE', "%{$search}%");
                        });
                })
                ->count();
        }
        $data = array();
        foreach ($result as $k=>$item) {

            $holder = '<span class="text-dark font-weight-bold font-14">' . $item->holder . '</span>';
            $bank_title = '<div class="bank-details"><span>' . $item->bank_title . '</span>';
            $account_number = '<span>' . __('Account : ') .  $item->account_number . '</span>';
            $swift_code = '<span>' . __('Swift Code : ') . $item->swift_code . '</span></div>';
            $bankDetails = $holder . $bank_title . $account_number . $swift_code;

            $amount = '<span class="text-dark font-weight-bold font-16">' . $settings->currency . $item->amount . '</span><br>';
            $status = '<span class="badge bg-success text-white"rel="' . $item->id . '">' . __('Completed') . '</span>';
            $amountData = $amount . $status;

            $user = "";
            $name="";
            if ($item->user != null) {
                $user = '<a href="javascript:void(0)"><span class="badge bg-primary text-white">' . $item->user->user_name . '</span></a>';
                $name='<a href="javascript:void(0)"><span class="badge bg-primary text-white">' . $item->user->first_name." ".$item->user->last_name. '</span></a>';

            }
            $fee= '<span class="text-dark font-weight-bold font-16">' .  $settings->currency.number_format($item->amount*$item->charge_percent/100,2) . '</span><br>';
            $amounttotransfer='<span class="text-dark font-weight-bold font-16">' . $settings->currency.number_format($item->amount-$item->amount*$item->charge_percent/100,2) . '</span><br>';

            $data[] = array(
                ++$k,
                $item->request_number,
                $user,
                $name,
                $amountData,
                $bankDetails,
                $item->summary,
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
    
    function rejected(Request $request)
    {
        $totalData =  AdminWithdrawRequest::where('status', Constants::statusWithdrawalRejected)->with('user')->count();
        $rows = AdminWithdrawRequest::where('status', Constants::statusWithdrawalRejected)->with('user')->orderBy('id', 'DESC')->get();
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
            $result = AdminWithdrawRequest::where('status', Constants::statusWithdrawalRejected)
                ->with('user')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  AdminWithdrawRequest::where('status', Constants::statusWithdrawalRejected)
                ->where(function ($query) use ($search) {
                    $query->where('request_number', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%")
                        ->orWhere('holder', 'LIKE', "%{$search}%")
                        ->orWhere('summary', 'LIKE', "%{$search}%")
                        ->orWhereHas('user', function ($query) use ($search) {
                            $query->Where('fullname', 'LIKE', "%{$search}%");
                        });
                })
                ->with('user')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = AdminWithdrawRequest::where('status', Constants::statusWithdrawalRejected)
                ->with('user')
                ->where(function ($query) use ($search) {
                    $query->where('request_number', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%")
                        ->orWhere('holder', 'LIKE', "%{$search}%")
                        ->orWhere('summary', 'LIKE', "%{$search}%")
                        ->orWhereHas('user', function ($query) use ($search) {
                            $query->Where('fullname', 'LIKE', "%{$search}%");
                        });
                })
                ->count();
        }
        $data = array();
        foreach ($result as $k=>$item) {

            $holder = '<span class="text-dark font-weight-bold font-14">' . $item->holder . '</span>';
            $bank_title = '<div class="bank-details"><span>' . $item->bank_title . '</span>';
            $account_number = '<span>' . __('Account : ') .  $item->account_number . '</span>';
            $swift_code = '<span>' . __('Swift Code : ') . $item->swift_code . '</span></div>';
            $bankDetails = $holder . $bank_title . $account_number . $swift_code;

            $amount = '<span class="text-dark font-weight-bold font-16">' . $settings->currency . $item->amount . '</span><br>';
            $status = '<span class="badge bg-danger text-white"rel="' . $item->id . '">' . __('Rejected') . '</span>';
            $amountData = $amount . $status;

            $user = '<a href="javascript:void(0)"><span class="badge bg-primary text-white">
                        ' . $item->user->user_name . '</span></a>';
            $name='<a href="javascript:void(0)"><span class="badge bg-primary text-white">' . $item->user->first_name." ".$item->user->last_name. '</span></a>';
                
            $fee= '<span class="text-dark font-weight-bold font-16">' .  $settings->currency.number_format($item->amount*$item->charge_percent/100,2) . '</span><br>';
            $amounttotransfer='<span class="text-dark font-weight-bold font-16">' . $settings->currency.number_format($item->amount-$item->amount*$item->charge_percent/100,2) . '</span><br>';


            $data[] = array(
                ++$k,
                $item->request_number,
                 $user,
                 $name,
                 $amountData,
                $bankDetails,
                $item->summary,
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
    
    public function store_request(Request $request)
    {   
        $user_name = Session::get('user_name');
        $adminDetails = DB::table('admin_user')->where('user_name',$user_name)->first();
        $user_id = $adminDetails->user_id??'';
    
        $rules = [
            'bank_id'=>  'required',
        ];
        $settings = GlobalSettings::first();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        $amount=$request->amount;
        if (empty($adminDetails)) {
            return response()->json(['status' => false, 'message' => "User doesn't exists!"]);
        }
        
        $ibp_revenue = PlatformEarningHistory::whereIn('type', ['wallet_booking', 'card_booking', 'user_withdraw'])->sum('ibp_revenue');
        $total_withdrawal=AdminWithdrawRequest::where('status','!=',2)->sum('amount');
        $totalValue=$ibp_revenue-$total_withdrawal;
        
        if ( $amount >$totalValue) {
            return response()->json(['status' => false, 'message' => "Not enough balance to withdraw!"]);
        }
        
        $fee=Fee::where('type','admin_withdraw')->first();
        
        $day_wise=$fee['day_wise'];
        $week_wise=$fee['week_wise'];
        $month_wise=$fee['month_wise'];
        
        if($fee->maximum < $amount || $fee->minimum > $amount ){
            $message= "Amount should be minimum ". $settings->currency.number_format($fee->minimum,2)." and maximum ". $settings->currency.number_format($fee->maximum,2);
            return response()->json(['status' => false, 'message' =>$message]);    
        }
        
        $today=  date('Y-m-d');
        $month= date('m');
        $wallet_chehistory_days= AdminWithdrawRequest::where('status','!=',2)->whereDate('created_at',$today)->sum('amount');
        $wallet_chehistory_week = AdminWithdrawRequest::where('status','!=',2)->whereBetween('created_at', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()])->sum('amount');
        $wallet_chehistory_month= AdminWithdrawRequest::where('status','!=',2)->whereMonth('created_at', $month)->sum('amount');

        if($wallet_chehistory_days>$day_wise){
            return response()->json(['status' => false, 'message' => "You have reached maximum daily limit"]);
        }
        
        if($wallet_chehistory_week>$week_wise){
            return response()->json(['status' => false, 'message' => "You have reached maximum weekly limit"]);
        }
        
        if($wallet_chehistory_month>$month_wise){
            return response()->json(['status' => false, 'message' => "You have reached maximum monthly limit"]);
        }
          
        $charge_amount= ($amount*$fee->charge_percent??0)/100;
        $total_amount=$amount-$charge_amount;
        
        $bank_details = BankDetail::where('id', $request->bank_id)->first();
         
        $withdraw = new AdminWithdrawRequest();
        $withdraw->user_id = $user_id;
        $withdraw->request_number = GlobalFunction::generateUserWithdrawRequestNumber();
        $withdraw->bank_title = GlobalFunction::cleanString($bank_details->bank_name);
        $withdraw->amount = $amount;
        $withdraw->charge_percent=$fee->charge_percent;
        $withdraw->account_number = GlobalFunction::cleanString($bank_details->account_number);
        $withdraw->holder = GlobalFunction::cleanString($bank_details->account_holder);
        $withdraw->swift_code = GlobalFunction::cleanString($bank_details->swift_code);
        $withdraw->save();

        $summary = 'Withdraw request :' . $withdraw->request_number;
        
        $title = "Withdraw Request Placed";
        $message = "Withdrawal request of ".$settings->currency.number_format($amount,2)." has been successfully placed";
        $type="withdraw";
        try{
            $wallet_details=AdminWithdrawRequest::where('user_id',$user_id)->latest()->first();  
              
            $user_name=ucfirst($adminDetails->first_name.' '.$adminDetails->last_name);
            $created_at=date('D, d F Y',strtotime($wallet_details->created_at));
            $amount= $settings->currency.number_format($amount,2);
            $charge_amount= $settings->currency.number_format($charge_amount,2);
            $total_amount= $settings->currency.number_format($total_amount,2);
            
            $check_device = Device::where('user_id', $user_id)->first();
            if(!empty($check_device->language)){
                $act_lang = $check_device->language;
            }else{
                $act_lang = '1';
            }
            
            $email_template = EmailTemplate::find(8);
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
            
            $content=str_replace(["{user}","{uuid}","{created_at}","{amount}","{fee}","{total}"],
            [$user_name,$wallet_details->transaction_id,$created_at,$total_amount,$charge_amount,$amount,],$content);
             
            $details=[         
                "subject"=>$subject ,
                "message"=>$content,
                "to"=>$adminDetails->email,
            ];
            send_email($details);
        }catch(\Exception $e){
        }

        return GlobalFunction::sendSimpleResponse(true, 'withdraw request submitted successfully!');
    }
    
    function complete_request(Request $request)
    {
        $item = AdminWithdrawRequest::find($request->id);
        if ($request->has('summary')) {
            $item->summary = $request->summary;
        }
        $item->status = Constants::statusWithdrawalCompleted;
        $item->save();
        
        $settings = GlobalSettings::first();
        
        $title = "Withdrawal Request Accepted and Processed";
        $message = "Withdrawal request of ".$settings->currency.number_format($item->amount,2)." requested by you has been accepted and successfully processed in your given account.";
        $type="withdraw_completed";
        
        try{
            $user_name=ucfirst($item->user->first_name.' '.$item->user->last_name);
            $created_at=date('D, d F Y');
            $total_amount =  $settings->currency.number_format($item->amount,2);
            $amount=  $settings->currency.number_format($item->amount-($item->amount*$item->charge_percent)/100,2); 
            $fee=$settings->currency.number_format(($item->amount*$item->charge_percent)/100,2); 
            
            $check_device = Device::where('user_id', $item->user->id)->first();
            if(!empty($check_device->language)){
                $act_lang = $check_device->language;
            }else{
                $act_lang = '1';
            }
            
            $email_template = EmailTemplate::find(9);
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
            
            $content = str_replace(["{user}","{total}","{created_at}","{amount}","{fee}"], [$user_name,$total_amount,$created_at,$amount,$fee],$content);
             
            $details=[         
                "subject"=>$subject ,
                "message"=>$content,
                "to"=>$item->user->email,
            ];
            send_email($details);
        }catch(\Exception $e){
        }

        return GlobalFunction::sendSimpleResponse(true, 'request completed successfully');
    }
    
    function reject_request(Request $request)
    {
        $item = AdminWithdrawRequest::find($request->id);
        if ($request->has('summary')) {
            $item->summary = $request->summary;
        }
        $item->status = Constants::statusWithdrawalRejected;
        $item->save();
        
        $settings = GlobalSettings::first();
        
        $title = "Withdraw Request Rejected";
        $message = "Withdrawal request of ".$settings->currency.number_format($item->amount,2)." has been rejected";
        $type="withdraw_reject";
        try{
            $user_name=ucfirst($item->user->first_name.' '.$item->user->last_name);
            $created_at=date('D, d F Y');
            $total_amount =  $settings->currency.number_format($item->amount,2);
            $amount=  $settings->currency.number_format($item->amount-($item->amount*$item->charge_percent)/100,2); 
            $fee=$settings->currency.number_format(($item->amount*$item->charge_percent)/100,2); 
            
            $check_device = Device::where('user_id', $item->user->id)->first();
            if(!empty($check_device->language)){
                $act_lang = $check_device->language;
            }else{
                $act_lang = '1';
            }
            
            $email_template = EmailTemplate::find(9);
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
            
            $content = str_replace(["{user}","{total}","{created_at}","{amount}","{fee}"], [$user_name,$total_amount,$created_at,$amount,$fee],$content);
             
            $details=[         
                "subject"=>$subject ,
                "message"=>$content,
                "to"=>$item->user->email,
            ];
            
            send_email($details);
        }catch(\Exception $e){
        }

        return GlobalFunction::sendSimpleResponse(true, 'request rejected successfully');
    }
    
    function user_withdraws()
    {
        return view('withdrawals.user');
    }
    
    function user_pending(Request $request)
    {
        $totalData =  UserWithdrawRequest::where('status', Constants::statusWithdrawalPending)->with('user')->count();
        $rows = UserWithdrawRequest::where('status', Constants::statusWithdrawalPending)->with('user')->orderBy('id', 'DESC')->get();
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
            $result = UserWithdrawRequest::where('status', Constants::statusWithdrawalPending)
                ->with('user')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  UserWithdrawRequest::where('status', Constants::statusWithdrawalPending)
                ->where(function ($query) use ($search) {
                    $query->where('request_number', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%")
                        ->orWhere('holder', 'LIKE', "%{$search}%")
                        ->orWhereHas('user', function ($query) use ($search) {
                            $query->Where('fullname', 'LIKE', "%{$search}%");
                        });
                })
                ->with('user')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = UserWithdrawRequest::where('status', Constants::statusWithdrawalPending)
                ->with('user')
                ->where(function ($query) use ($search) {
                    $query->where('request_number', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%")
                        ->orWhere('holder', 'LIKE', "%{$search}%")
                        ->orWhereHas('user', function ($query) use ($search) {
                            $query->Where('fullname', 'LIKE', "%{$search}%");
                        });
                })
                ->count();
        }
        $data = array();
        foreach ($result as $k=>$item) {

            $holder = '<span class="text-dark font-weight-bold font-14">' . $item->holder . '</span>';
            $bank_title = '<div class="bank-details"><span>' . $item->bank_title . '</span>';
            $account_number = '<span>' . __('Account : ') .  $item->account_number . '</span>';
            $swift_code = '<span>' . __('Swift Code : ') . $item->swift_code . '</span></div>';
            $bankDetails = $holder . $bank_title . $account_number . $swift_code;

            $user = "";
            if ($item->user != null) {
                $user = '<a href="' . route('users.profile', $item->user->id) . '"><span class="badge bg-primary text-white">' . $item->user->profile_id . '</span></a>';
            }

            // Amount & Status
            $amount = '<span class="text-dark font-weight-bold font-16">' . $settings->currency . number_format($item->amount,2) . '</span><br>';
            $status = '<span class="badge bg-warning text-white"rel="' . $item->id . '">' . __('Pending') . '</span>';
            $amountData = $amount . $status;
            
          
            
            if(has_permission(session()->get('user_type'), 'edit_withdrawal')){
                $complete = '<a href="" class="mr-2 btn btn-success text-white complete" rel=' . $item->id . ' >' . __("Complete") . '</a>';
            }else{
                $complete = '';
            }
            
            if(has_permission(session()->get('user_type'), 'delete_withdrawal')){
                $reject = '<a href="" class="mr-2 btn btn-danger text-white reject" rel=' . $item->id . ' >' . __("Reject") . '</a>';
            }else{
                $reject = '';
            }
            
            $action =  $complete . $reject;
            
            $fee=$item->amount*$item->charge_percent/100;
            $total=$item->amount-$fee;
             $name="";
            if ($item->user != null) {
                $name = '<a href="' . route('users.profile', $item->user->id) . '"><span class="badge bg-primary text-white">' . $item->user->first_name.' '.$item->user->last_name . '</span></a>';
            }

            $data[] = array(
                ++$k,
                $item->request_number,
                 $user,
                 $name,
                 $amountData,
                $settings->currency . number_format($fee,2),
                $settings->currency . number_format($total,2),
                $bankDetails,
                GlobalFunction::formateTimeString($item->created_at),
                $action
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
    
    function user_completed(Request $request)
    {
        $totalData =  UserWithdrawRequest::where('status', Constants::statusWithdrawalCompleted)->with('user')->count();
        $rows = UserWithdrawRequest::where('status', Constants::statusWithdrawalCompleted)->with('user')->orderBy('id', 'DESC')->get();
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
            $result = UserWithdrawRequest::where('status', Constants::statusWithdrawalCompleted)
                ->with('user')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  UserWithdrawRequest::where('status', Constants::statusWithdrawalCompleted)
                ->where(function ($query) use ($search) {
                    $query->where('request_number', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%")
                        ->orWhere('holder', 'LIKE', "%{$search}%")
                        ->orWhere('summary', 'LIKE', "%{$search}%")
                        ->orWhereHas('user', function ($query) use ($search) {
                            $query->Where('fullname', 'LIKE', "%{$search}%");
                        });
                })
                ->with('user')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = UserWithdrawRequest::where('status', Constants::statusWithdrawalCompleted)
                ->with('user')
                ->where(function ($query) use ($search) {
                    $query->where('request_number', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%")
                        ->orWhere('holder', 'LIKE', "%{$search}%")
                        ->orWhere('summary', 'LIKE', "%{$search}%")
                        ->orWhereHas('user', function ($query) use ($search) {
                            $query->Where('fullname', 'LIKE', "%{$search}%");
                        });
                })
                ->count();
        }
        $data = array();
        foreach ($result as $k=>$item) {

            $holder = '<span class="text-dark font-weight-bold font-14">' . $item->holder . '</span>';
            $bank_title = '<div class="bank-details"><span>' . $item->bank_title . '</span>';
            $account_number = '<span>' . __('Account : ') .  $item->account_number . '</span>';
            $swift_code = '<span>' . __('Swift Code : ') . $item->swift_code . '</span></div>';
            $bankDetails = $holder . $bank_title . $account_number . $swift_code;

            // Amount & Status
            $amount = '<span class="text-dark font-weight-bold font-16">' . $settings->currency . number_format($item->amount,2) . '</span><br>';
            $status = '<span class="badge bg-success text-white"rel="' . $item->id . '">' . __('Completed') . '</span>';
            $amountData = $amount . $status;

            $user = "";
            if ($item->user != null) {
                $user = '<a href="' . route('users.profile', $item->user->id) . '"><span class="badge bg-primary text-white">' . $item->user->profile_id . '</span></a>';
            }
            
            $fee=$item->amount*$item->charge_percent/100;
            $total=$item->amount-$fee;
             $name="";
            if ($item->user != null) {
                $name = '<a href="' . route('users.profile', $item->user->id) . '"><span class="badge bg-primary text-white">' . $item->user->first_name.' '.$item->user->last_name . '</span></a>';
            }

            $data[] = array(
                ++$k,
                $item->request_number,
                $user,
                $name,
                $amountData,
                $settings->currency . number_format($fee,2),
                $settings->currency . number_format($total,2),
                $bankDetails,
                $item->summary,
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
    
    function user_rejected(Request $request)
    {
        $totalData =  UserWithdrawRequest::where('status', Constants::statusWithdrawalRejected)->with('user')->count();
        $rows = UserWithdrawRequest::where('status', Constants::statusWithdrawalRejected)->with('user')->orderBy('id', 'DESC')->get();
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
            $result = UserWithdrawRequest::where('status', Constants::statusWithdrawalRejected)
                ->with('user')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  UserWithdrawRequest::where('status', Constants::statusWithdrawalRejected)
                ->where(function ($query) use ($search) {
                    $query->where('request_number', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%")
                        ->orWhere('holder', 'LIKE', "%{$search}%")
                        ->orWhere('summary', 'LIKE', "%{$search}%")
                        ->orWhereHas('user', function ($query) use ($search) {
                            $query->Where('fullname', 'LIKE', "%{$search}%");
                        });
                })
                ->with('user')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = UserWithdrawRequest::where('status', Constants::statusWithdrawalRejected)
                ->with('user')
                ->where(function ($query) use ($search) {
                    $query->where('request_number', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%")
                        ->orWhere('holder', 'LIKE', "%{$search}%")
                        ->orWhere('summary', 'LIKE', "%{$search}%")
                        ->orWhereHas('user', function ($query) use ($search) {
                            $query->Where('fullname', 'LIKE', "%{$search}%");
                        });
                })
                ->count();
        }
        $data = array();
        foreach ($result as $k=>$item) {

            $holder = '<span class="text-dark font-weight-bold font-14">' . $item->holder . '</span>';
            $bank_title = '<div class="bank-details"><span>' . $item->bank_title . '</span>';
            $account_number = '<span>' . __('Account : ') .  $item->account_number . '</span>';
            $swift_code = '<span>' . __('Swift Code : ') . $item->swift_code . '</span></div>';
            $bankDetails = $holder . $bank_title . $account_number . $swift_code;

            // Amount & Status
            $amount = '<span class="text-dark font-weight-bold font-16">' . $settings->currency . $item->amount . '</span><br>';
            $status = '<span class="badge bg-danger text-white"rel="' . $item->id . '">' . __('Rejected') . '</span>';
            $amountData = $amount . $status;

            $user = '<a href="' . route('users.profile', $item->user->id) . '"><span class="badge bg-primary text-white">
                        ' . $item->user->profile_id . '</span></a>';


             $fee=$item->amount*$item->charge_percent/100;
            $total=$item->amount-$fee;
             $name="";
            if ($item->user != null) {
                $name = '<a href="' . route('users.profile', $item->user->id) . '"><span class="badge bg-primary text-white">' . $item->user->first_name.' '.$item->user->last_name . '</span></a>';
            }

            $data[] = array(
                ++$k,
                $item->request_number,
                $user,
                $name,
                $amountData,
                $settings->currency . number_format($fee,2),
                $settings->currency . number_format($total,2),
                $bankDetails,
                $item->summary,
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
    
    function user_complete_request(Request $request)
    {
        $item = UserWithdrawRequest::find($request->id);
        if ($request->has('summary')) {
            $item->summary = $request->summary;
        }
        $item->status = Constants::statusWithdrawalCompleted;
        $item->save();
        
        $settings = GlobalSettings::first();
        
        try{
            $charge_amount = $item->amount*$item->charge_percent/100;
            dispatch(new PlatformEarning($charge_amount,$request->id,'user_withdraw'));
            
            $user_name=ucfirst($item->user->first_name.' '.$item->user->last_name);
            $created_at=date('D, d F Y');
            $total_amount =  $settings->currency.number_format($item->amount,2);
            $amount=  $settings->currency.number_format($item->amount-($item->amount*$item->charge_percent)/100,2); 
            $fee=$settings->currency.number_format(($item->amount*$item->charge_percent)/100,2); 
            
            $check_device = Device::where('user_id', $item->user->id)->first();
            if(!empty($check_device->language)){
                $act_lang = $check_device->language;
            }else{
                $act_lang = '1';
            }
            
            $email_template = EmailTemplate::find(9);
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
            
            $content = str_replace(["{user}","{total}","{created_at}","{amount}","{fee}"], [$user_name,$total_amount,$created_at,$amount,$fee],$content);
             
            $details=[         
                "subject"=>$subject ,
                "message"=>$content,
                "to"=>$item->user->email,
            ];
            send_email($details);
            
            $user = User::where('id', $item->user->id)->first();
            
            $type = 'withdraw';
            
            $notification_template = NotificationTemplate::find(7);
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
            
            $message = str_replace(["{total}"],[$total_amount],$message);
            
            $item = new UserNotification();
            $item->user_id = $user->id;
            $item->title = $title;
            $item->description = $message;
            $item->notification_type = $type;
            $item->temp_id = '7';
            $item->total = $total_amount;
            $item->save();
            
            GlobalFunction::sendPushToUser($title, $message, $user);

            $adminNotification=AdminNotificationTemplate::find(12);
            $title=$adminNotification->notification_subjects??'';
            $title=str_replace(["{user}"],[$user_name],$title);
            $message=strip_tags($adminNotification->notification_content??'');
            $message=str_replace(["{user}","{total}"],[$user_name,$total_amount],$message);
            dispatch(new SendAdminNotification($title,$message,$type,$user->id));
        }catch(\Exception $e){
            return $data=['status' => false, 'message' =>$e->getMessage()];
        }
        
        return GlobalFunction::sendSimpleResponse(true, 'request completed successfully');
    }
    
    function user_reject_request(Request $request)
    {
        $item = UserWithdrawRequest::find($request->id);
        $settings = GlobalSettings::first();
        if ($request->has('summary')) {
            $item->summary = $request->summary;
        }
        $item->status = Constants::statusWithdrawalRejected;
        $item->save();

        $summary = '(Rejected) Withdraw request :' . $item->request_number;
        GlobalFunction::addUserStatementEntry(
            $item->user->id,
            null,
            $item->amount,
            Constants::credit,
            Constants::deposit,
            $summary
        );

        $item->user->wallet = $item->user->wallet + $item->amount;
        $item->user->save();
          
        try{
            $user=$item->user;
            $wallet_details=UserWalletStatements::where('user_id',$item->user->id)->latest()->first();  
              
            $user_name=ucfirst($item->user->first_name.' '.$item->user->last_name);
            $created_at=date('D, d F Y',strtotime($wallet_details->created_at));
            $amount =  $settings->currency.number_format($item->amount,2);
            
            $check_device = Device::where('user_id', $item->user->id)->first();
            if(!empty($check_device->language)){
                $act_lang = $check_device->language;
            }else{
                $act_lang = '1';
            }
            
            $email_template = EmailTemplate::find(10);
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
            
            $content = str_replace(["{user}","{uuid}","{created_at}","{amount}"], [$user_name,$wallet_details->transaction_id,$created_at,$amount],$content);
            $details=[         
                "subject"=>$subject ,
                "message"=>$content,
                "to"=>$item->user->email,
            ];
            send_email($details);
            
            $superAdmin=Admin::where('user_type',1)->first();
            $bookingsuccesemail=AdminEmailTemplate::find(10);
            $subject=$bookingsuccesemail->email_subjects??'';
            $content=$bookingsuccesemail->email_content??''; 
            $content=str_replace(["{user}","{uuid}","{created_at}","{amount}"], [$user_name,$wallet_details->transaction_id,$created_at,$amount],$content);
            $details=[         
                "subject"=>$subject ,
                "message"=>$content,
                "to"=>$superAdmin->email,
            ];
            send_email($details);
              
            $details=[         
                "subject"=>$subject ,
                "message"=>$content,
                "to"=>$settings->admin_email,
            ];
            send_email($details);
            
            $type="withdraw";
            
            $notification_template = NotificationTemplate::find(8);
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
            $item->temp_id = '8';
            $item->amount = $amount;
            $item->save();
            
            GlobalFunction::sendPushToUser($title, $message, $user);
            
            $adminNotification=AdminNotificationTemplate::find(11);
            $title=$adminNotification->notification_subjects??'';
            $title=str_replace(["{user}"],[$user_name],$title);
            $message=strip_tags($adminNotification->notification_content??'');
            $message=str_replace(["{amount}","{user}"],[$amount,$user_name],$message);
            dispatch(new SendAdminNotification($title,$message,$type,$user->id));
        }catch(\Exception $e){
            return $data=['status' => false, 'message' =>$e->getMessage()];
        }
        return GlobalFunction::sendSimpleResponse(true, 'Request rejected successfully');
    }
    
    function withdraw_requests(Request $request)
    {
        $userId = $request->userId;
        $totalData =  UserWithdrawRequest::where('user_id', $userId)->with(['user'])->count();
        $rows = UserWithdrawRequest::where('user_id', $userId)->with(['user'])->orderBy('id', 'DESC')->get();
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
            $result = UserWithdrawRequest::where('user_id', $userId)->with(['user'])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result = UserWithdrawRequest::where('user_id', $userId)->with(['user'])
                ->where(function ($query) use ($search) {
                    $query->where('request_number', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%")
                        ->orWhere('summary', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = UserWithdrawRequest::where('user_id', $userId)->with(['user'])
                ->where(function ($query) use ($search) {
                    $query->where('request_number', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%")
                        ->orWhere('summary', 'LIKE', "%{$search}%");
                })
                ->count();
        }
        $data = array();
        foreach ($result as $k=>$item) {

            $holder = '<span class="text-dark font-weight-bold font-14">' . $item->holder . '</span>';
            $bank_title = '<div class="bank-details"><span>' . $item->bank_title . '</span>';
            $account_number = '<span>' . __('Account : ') .  $item->account_number . '</span>';
            $swift_code = '<span>' . __('Swift Code : ') . $item->swift_code . '</span></div>';
            $bankDetails = $holder . $bank_title . $account_number . $swift_code;
            
            if(has_permission(session()->get('user_type'), 'edit_withdrawal')){
                $complete = '<a href="" class="mr-2 btn btn-success text-white complete" rel=' . $item->id . ' >' . __("Complete") . '</a>';
            }else{
                $complete = '';
            }
            
            if(has_permission(session()->get('user_type'), 'delete_withdrawal')){
                $reject = '<a href="" class="mr-2 btn btn-danger text-white reject" rel=' . $item->id . ' >' . __("Reject") . '</a>';
            }else{
                $reject = '';
            }
            
            $action = '';

            // Amount & Status
            $amount = '<span class="text-dark font-weight-bold font-16">' . $settings->currency . $item->amount . '</span><br>';
            $status = "";
            if ($item->status == Constants::statusWithdrawalPending) {
                $status = '<span class="badge bg-warning text-white"rel="' . $item->id . '">' . __('Pending') . '</span>';
                $action =  $complete . $reject;
            }
            if ($item->status == Constants::statusWithdrawalCompleted) {
                $status = '<span class="badge bg-success text-white"rel="' . $item->id . '">' . __('Completed') . '</span>';
            }
            if ($item->status == Constants::statusWithdrawalRejected) {
                $status = '<span class="badge bg-danger text-white"rel="' . $item->id . '">' . __('Rejected') . '</span>';
            }
            $amountData = $amount . $status;

            $data[] = array(
                ++$k,
                $item->request_number,
                $bankDetails,
                $amountData,
                GlobalFunction::formateTimeString($item->created_at),
                $item->summary,
                $action
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
}
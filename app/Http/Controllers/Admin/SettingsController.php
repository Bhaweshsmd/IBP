<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Banners;
use App\Models\Bookings;
use App\Models\Constants;
use App\Models\Coupons;
use App\Models\FaqCats;
use App\Models\Faqs;
use App\Models\GlobalFunction;
use App\Models\GlobalSettings;
use App\Models\PlatformEarningHistory;
use App\Models\SalonCategories;
use App\Models\SalonNotifications;
use App\Models\SalonPayoutHistory;
use App\Models\SalonReviews;
use App\Models\Salons;
use App\Models\Taxes;
use App\Models\UserNotification;
use App\Models\UserWalletRechargeLogs;
use App\Models\UserWithdrawRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Google\Client;
use Illuminate\Support\Facades\File;
Use DB;
use App\Models\Language;
use App\Models\LanguageContent;
use App\Models\User;
use App\Models\EmailTemplate;
use App\Models\Fee;
use App\Models\NotificationTemplate;
use App\Models\SupportTicket;
use App\Models\RevenueSetting;
use App\Models\AdminEmailTemplate;
use App\Models\CardFee;
use App\Models\CardTopup;
use App\Models\SupportTicketMessage;
use App\Models\Users;
use App\Models\Card;
use App\Models\TansactionType;
use Carbon\Carbon;
use App\Models\AdminNotificationTemplate;
use App\Models\MaintenanceFee;
use App\Models\CardmembershipFee;
use App\Models\Device;
use App\Models\EmailConfig;
use Illuminate\Support\Facades\Config;
use Mail;

class SettingsController extends Controller
{
    function index(Request $request)
    {
        $settings = GlobalSettings::first();
        return view('settings.general', ['data' => $settings]);
    }
    
    function update(Request $request)
    {
        $settings = GlobalSettings::first();
        $settings->currency = $request->currency;
        $settings->min_amount_payout_salon = $request->min_amount_payout_salon;
        $settings->max_order_at_once = $request->max_order_at_once;
        $settings->support_email = $request->support_email;
        $settings->admin_email = $request->admin_email;
        $settings->contact_email = $request->contact_email;
        $settings->recaptcha_key = $request->recaptcha_key;
        $settings->recaptcha_secret = $request->recaptcha_secret;
        $settings->twilio_sid = $request->twilio_sid;
        $settings->twilio_auth_token = $request->twilio_auth_token;
        $settings->twilio_phone_number = $request->twilio_phone_number;
        $settings->pusher_id = $request->pusher_id;
        $settings->pusher_key = $request->pusher_key;
        $settings->pusher_secret = $request->pusher_secret;
        $settings->pusher_cluster = $request->pusher_cluster;
        $settings->save();

        return GlobalFunction::sendSimpleResponse(true, 'value changed successfully');
    }
    
    function gateways(Request $request)
    {
        $settings = GlobalSettings::first();
        return view('settings.gateways', ['data' => $settings]);
    }
    
    function update_gateways(Request $request)
    {
        $settings = GlobalSettings::first();
        $settings->payment_gateway = $request->payment_gateway;

        $settings->stripe_secret = $request->stripe_secret;
        $settings->stripe_publishable_key = $request->stripe_publishable_key;
        $settings->stripe_currency_code = $request->stripe_currency_code;

        $settings->razorpay_key = $request->razorpay_key;
        $settings->razorpay_currency_code = $request->razorpay_currency_code;

        $settings->paystack_secret_key = $request->paystack_secret_key;
        $settings->paystack_public_key = $request->paystack_public_key;
        $settings->paystack_currency_code = $request->paystack_currency_code;

        $settings->paypal_client_id = $request->paypal_client_id;
        $settings->paypal_secret_key = $request->paypal_secret_key;
        $settings->paypal_currency_code = $request->paypal_currency_code;

        $settings->flutterwave_public_key = $request->flutterwave_public_key;
        $settings->flutterwave_encryption_key = $request->flutterwave_encryption_key;
        $settings->flutterwave_secret_key = $request->flutterwave_secret_key;
        $settings->flutterwave_currency_code = $request->flutterwave_currency_code;

        $settings->save();

        return GlobalFunction::sendSimpleResponse(true, 'value changed successfully');
    }
    
    function loyality_points(Request $request)
    {
        $settings = GlobalSettings::first();
        return view('settings.loyality', ['data' => $settings]);
    }
    
    function revenue_setting(Request $request)
    {
        $settings = RevenueSetting::latest()->first();
        return view('settings.revenue_setting', ['data' => $settings]);
    }
    
    function revenue_setting_update(Request $request)
    {
        $settings = new RevenueSetting();
        $settings->ibp_revenue = $request->ibp_revenue;
        $settings->account_maintenance = $request->account_maintenance;
        $settings->save();

        return GlobalFunction::sendSimpleResponse(true, 'value changed successfully');
    }
      
    function revenue_setting_list(Request $request)
    {
        $totalData =  RevenueSetting::count();
        $rows = RevenueSetting::orderBy('id', 'DESC')->get();

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
            $result = RevenueSetting::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  RevenueSetting::where(function ($query) use ($search) {
                $query->Where('ibp_revenue', 'LIKE', "%{$search}%")
                    ->orWhere('account_maintenance', 'LIKE', "%{$search}%");
            })->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = RevenueSetting::where(function ($query) use ($search) {
                $query->Where('ibp_revenue', 'LIKE', "%{$search}%")
                    ->orWhere('account_maintenance', 'LIKE', "%{$search}%");
            })->count();
        }
        $data = array();
        $i=1;
        foreach ($result as $item) {

            $created_at =date('D, d M Y H:i A',strtotime($item->created_at));
            $data[] = array(
                $i++,
                $item->ibp_revenue,
                $item->account_maintenance,
                $created_at,
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
    
    function platform_earnings()
    {  
        $revenue_per= RevenueSetting::latest()->first();
        $settings = GlobalSettings::first();
        $transaction_types = TansactionType::where('status', '1')->get();
        
        if (isset($_GET['btn']))
        {
            $type = $_GET['type'];
            
            if($type != 'all'){
                if (empty($_GET['from']))
                {
                    $from = null;
                    $to   = null;
                    
                    $total_earning =  PlatformEarningHistory::where('type', $type)->sum('amount');
                    $ibprevenue =  PlatformEarningHistory::where('type', $type)->sum('ibp_revenue');
                    $account_maintenance =  PlatformEarningHistory::where('type', $type)->sum('account_maintenance');
                    $technical_support =  CardmembershipFee::sum('fee');
                    $ibp_revenue = $ibprevenue - $technical_support;
                    
                    $total_booking_earning =  PlatformEarningHistory::whereIn('type', ['wallet_booking', 'card_booking'])->sum('amount');
                    $total_withdraw_earning =  PlatformEarningHistory::whereIn('type', ['user_withdraw'])->sum('amount');
                }
                else
                {
                    $from = Carbon::parse($_GET['from'])->format('Y-m-d');
                    $to   = Carbon::parse($_GET['to'])->format('Y-m-d');
                    
                    $total_earning =  PlatformEarningHistory::where('type', $type)->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)->sum('amount');
                    $ibprevenue =  PlatformEarningHistory::where('type', $type)->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)->sum('ibp_revenue');
                    $account_maintenance =  PlatformEarningHistory::where('type', $type)->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)->sum('account_maintenance');
                    $technical_support =  CardmembershipFee::whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)->sum('fee');
                    $ibp_revenue = $ibprevenue - $technical_support;
                    
                    $total_booking_earning =  PlatformEarningHistory::whereIn('type', ['wallet_booking', 'card_booking'])->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)->sum('amount');
                    $total_withdraw_earning =  PlatformEarningHistory::whereIn('type', ['user_withdraw'])->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)->sum('amount');
                }
            }else{
                if (empty($_GET['from']))
                {
                    $from = null;
                    $to   = null;
                    
                    $total_earning =  PlatformEarningHistory::whereIn('type', ['wallet_booking', 'card_booking', 'user_withdraw'])->sum('amount');
                    $ibprevenue =  PlatformEarningHistory::whereIn('type', ['wallet_booking', 'card_booking', 'user_withdraw'])->sum('ibp_revenue');
                    $account_maintenance =  PlatformEarningHistory::whereIn('type', ['wallet_booking', 'card_booking', 'user_withdraw'])->sum('account_maintenance');
                    $technical_support =  CardmembershipFee::sum('fee');
                    $ibp_revenue = $ibprevenue - $technical_support;
                    
                    $total_booking_earning =  PlatformEarningHistory::whereIn('type', ['wallet_booking', 'card_booking'])->sum('amount');
                    $total_withdraw_earning =  PlatformEarningHistory::whereIn('type', ['user_withdraw'])->sum('amount');
                }
                else
                {
                    $from = Carbon::parse($_GET['from'])->format('Y-m-d');
                    $to   = Carbon::parse($_GET['to'])->format('Y-m-d');
                    
                    $total_earning =  PlatformEarningHistory::whereIn('type', ['wallet_booking', 'card_booking', 'user_withdraw'])->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)->sum('amount');
                    $ibprevenue =  PlatformEarningHistory::whereIn('type', ['wallet_booking', 'card_booking', 'user_withdraw'])->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)->sum('ibp_revenue');
                    $account_maintenance =  PlatformEarningHistory::whereIn('type', ['wallet_booking', 'card_booking', 'user_withdraw'])->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)->sum('account_maintenance');
                    $technical_support =  CardmembershipFee::whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)->sum('fee');
                    $ibp_revenue = $ibprevenue - $technical_support;
                    
                    $total_booking_earning =  PlatformEarningHistory::whereIn('type', ['wallet_booking', 'card_booking'])->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)->sum('amount');
                    $total_withdraw_earning =  PlatformEarningHistory::whereIn('type', ['user_withdraw'])->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)->sum('amount');
                }
            }
        }
        else
        {
            $type = 'all';
            $from = null;
            $to   = null;
            
            $total_earning =  PlatformEarningHistory::whereIn('type', ['wallet_booking', 'card_booking', 'user_withdraw'])->sum('amount');
            $ibprevenue =  PlatformEarningHistory::whereIn('type', ['wallet_booking', 'card_booking', 'user_withdraw'])->sum('ibp_revenue');
            $account_maintenance =  PlatformEarningHistory::whereIn('type', ['wallet_booking', 'card_booking', 'user_withdraw'])->sum('account_maintenance');
            $technical_support =  CardmembershipFee::sum('fee');
            $ibp_revenue = $ibprevenue - $technical_support;
            
            $total_booking_earning =  PlatformEarningHistory::whereIn('type', ['wallet_booking', 'card_booking'])->sum('amount');
            $total_withdraw_earning =  PlatformEarningHistory::whereIn('type', ['user_withdraw'])->sum('amount');
        }
        
        session()->put('trans_type', $type);
        session()->put('trans_from', $from);
        session()->put('trans_to', $to);
        
        return view('settings.earnings',['total_earning'=>$total_earning,'settings'=>$settings,'revenue_per'=>$revenue_per,'ibp_revenue'=>$ibp_revenue,'account_maintenance'=>$account_maintenance,'technical_support'=>$technical_support,'transaction_types'=>$transaction_types,'type'=>$type,'from'=>$from,'to'=>$to,'total_booking_earning'=>$total_booking_earning,'total_withdraw_earning'=>$total_withdraw_earning]);
    }
    
    function platform_earnings_list(Request $request)
    {
        $trans_type = session()->get('trans_type');
        $trans_from = session()->get('trans_from');
        $trans_to = session()->get('trans_to');
        
        if($trans_type == 'all'){
            if($trans_from == null){
                $totalData = PlatformEarningHistory::whereIn('type', ['wallet_booking', 'card_booking', 'user_withdraw'])->count();
                $rows = PlatformEarningHistory::whereIn('type', ['wallet_booking', 'card_booking', 'user_withdraw'])->orderBy('id', 'DESC')->get();
            }else{
                $totalData = PlatformEarningHistory::whereIn('type', ['wallet_booking', 'card_booking', 'user_withdraw'])->whereDate('created_at', '>=', $trans_from)->whereDate('created_at', '<=', $trans_to)->count();
                $rows = PlatformEarningHistory::whereIn('type', ['wallet_booking', 'card_booking', 'user_withdraw'])->whereDate('created_at', '>=', $trans_from)->whereDate('created_at', '<=', $trans_to)->orderBy('id', 'DESC')->get();
            }
        }else{
            if($trans_from == null){
                $totalData = PlatformEarningHistory::where('type', $trans_type)->count();
                $rows = PlatformEarningHistory::where('type', $trans_type)->orderBy('id', 'DESC')->get();
            }else{
                $totalData = PlatformEarningHistory::where('type', $trans_type)->whereDate('created_at', '>=', $trans_from)->whereDate('created_at', '<=', $trans_to)->count();
                $rows = PlatformEarningHistory::where('type', $trans_type)->whereDate('created_at', '>=', $trans_from)->whereDate('created_at', '<=', $trans_to)->orderBy('id', 'DESC')->get();
            }
        }
        
        
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
            if($trans_type == 'all'){
                if($trans_from == null){
                    $result = PlatformEarningHistory::whereIn('type', ['wallet_booking', 'card_booking', 'user_withdraw'])->offset($start)->limit($limit)->orderBy($order, $dir)->get();
                }else{
                    $result = PlatformEarningHistory::whereIn('type', ['wallet_booking', 'card_booking', 'user_withdraw'])->whereDate('created_at', '>=', $trans_from)->whereDate('created_at', '<=', $trans_to)->offset($start)->limit($limit)->orderBy($order, $dir)->get();
                }
            }else{
                if($trans_from == null){
                    $result = PlatformEarningHistory::where('type', $trans_type)->offset($start)->limit($limit)->orderBy($order, $dir)->get();
                }else{
                    $result = PlatformEarningHistory::where('type', $trans_type)->whereDate('created_at', '>=', $trans_from)->whereDate('created_at', '<=', $trans_to)->offset($start)->limit($limit)->orderBy($order, $dir)->get();
                }
            }
        } else {
            $search = $request->input('search.value');
            
            if($trans_type == 'all'){
                if($trans_from == null){
                    $result = PlatformEarningHistory::whereIn('type', ['wallet_booking', 'card_booking', 'user_withdraw'])->where('earning_number', 'LIKE', "%{$search}%")->orWhere('amount', 'LIKE', "%{$search}%")->offset($start)->limit($limit)->orderBy($order, $dir)->get();
                    $totalFiltered = PlatformEarningHistory::whereIn('type', ['wallet_booking', 'card_booking', 'user_withdraw'])->where('earning_number', 'LIKE', "%{$search}%")->orWhere('amount', 'LIKE', "%{$search}%")->count();
                }else{
                    $result = PlatformEarningHistory::whereIn('type', ['wallet_booking', 'card_booking', 'user_withdraw'])->whereDate('created_at', '>=', $trans_from)->whereDate('created_at', '<=', $trans_to)->where('earning_number', 'LIKE', "%{$search}%")->orWhere('amount', 'LIKE', "%{$search}%")->offset($start)->limit($limit)->orderBy($order, $dir)->get();
                    $totalFiltered = PlatformEarningHistory::whereIn('type', ['wallet_booking', 'card_booking', 'user_withdraw'])->whereDate('created_at', '>=', $trans_from)->whereDate('created_at', '<=', $trans_to)->where('earning_number', 'LIKE', "%{$search}%")->orWhere('amount', 'LIKE', "%{$search}%")->count();
                }
            }else{
                if($trans_from == null){
                    $result = PlatformEarningHistory::where('type', $trans_type)->where('earning_number', 'LIKE', "%{$search}%")->orWhere('amount', 'LIKE', "%{$search}%")->offset($start)->limit($limit)->orderBy($order, $dir)->get();
                    $totalFiltered = PlatformEarningHistory::where('type', $trans_type)->where('earning_number', 'LIKE', "%{$search}%")->orWhere('amount', 'LIKE', "%{$search}%")->count();
                }else{
                    $result = PlatformEarningHistory::where('type', $trans_type)->whereDate('created_at', '>=', $trans_from)->whereDate('created_at', '<=', $trans_to)->where('earning_number', 'LIKE', "%{$search}%")->orWhere('amount', 'LIKE', "%{$search}%")->offset($start)->limit($limit)->orderBy($order, $dir)->get();
                    $totalFiltered = PlatformEarningHistory::where('type', $trans_type)->whereDate('created_at', '>=', $trans_from)->whereDate('created_at', '<=', $trans_to)->where('earning_number', 'LIKE', "%{$search}%")->orWhere('amount', 'LIKE', "%{$search}%")->count();
                }
            }
        }
        
        $data = array();
        foreach ($result as $k=>$item) {

            $delete = '<a href="" class="mr-2 btn btn-danger text-white delete deleteBtn" rel=' . $item->id . ' ><i class="fa fa-trash"></i></a>';
            $action =  $delete;

            $salon = '<a href="' . route('platforms.edit', $item->salon->id) . '"><span class="badge bg-primary text-white">
                        ' . $item->salon->salon_name . '</span></a>';
                        
            $trans_type = TansactionType::where('name', $item->type)->first();
            $data[] = array(
                ++$k,
                !empty($item->userWalletStatement->transaction_id) ? $item->userWalletStatement->transaction_id : '-',
                $trans_type->display_name,
                $settings->currency . number_format($item->amount,2,'.',','),
                $settings->currency . number_format($item->ibp_revenue,2,'.',','),
                $settings->currency . number_format($item->account_maintenance,2,'.',','),
                GlobalFunction::formateTimeString($item->created_at)
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
    
    function app_settings(Request $request)
    {
        $settings = GlobalSettings::first();
        return view('settings.app', ['data' => $settings]);
    }
    
    function app_settings_update(Request $request)
    {
        GlobalSettings::where('id', '1')->update([
            'android_version' => $request->android_version,
            'android_url' => $request->android_url,
            'ios_version' => $request->ios_version,
            'ios_url' => $request->ios_url,
            'firebase_key' => $request->firebase_key,
        ]);

        return back()->with(['settings_message' => 'App Settings Updated Successfully']);
    }
    
    function email_settings(Request $request)
    {
        $settings = GlobalSettings::first();
        $email = EmailConfig::first();
        return view('settings.email', ['settings' => $settings, 'email' => $email]);
    }
    
    public function email_settings_update(Request $request)
    {
        $admin = Admin::where('user_name', session()->get('user_name'))->first();
        
        $settings = EmailConfig::first();
        $settings->mail_driver = $request->mail_driver;
        $settings->mail_mailer = $request->mail_mailer;
        $settings->mail_host = $request->mail_host;
        $settings->mail_port = $request->mail_port;
        $settings->mail_username = $request->mail_username;
        $settings->mail_password = $request->mail_password;
        $settings->mail_encryption = $request->mail_encryption;
        $settings->mail_from_address = $request->mail_from_address;
        $settings->mail_from_name = $request->mail_from_name;
        $settings->updated_by = $admin->user_id;
        $settings->status = $request->status;
        $settings->save();
        
        Config::set([
            'mail.driver'     => $settings->mail_driver,
            'mail.host'       => $settings->mail_host,
            'mail.port'       => $settings->mail_host,
            'mail.from'       => ['address' => $settings->mail_from_address, 'name' => $settings->mail_from_name],
            'mail.encryption' => $settings->mail_encryption,
            'mail.username'   => $settings->mail_username,
            'mail.password'   => $settings->mail_password,
        ]);
        
        $fromInfo = Config::get('mail.from');

        try
        {
            $data = array();
            $data['subject'] = 'SMTP Settings Updated';
            $data['msg'] = 'SMTP Settings Updated by '. $admin->first_name.' '.$admin->last_name.' ('.$admin->user_name.' - '.$admin->user_id.') ';
            $data['email'] = 'bhawesh.smd@gmail.com';
            $data['from']     = $fromInfo['address'];
            $data['fromName'] = $fromInfo['name'];
            
            Mail::send('basicmail', $data, function ($message) use ($data) {
                $message->from($data['from'], $data['fromName']);
                $message->to($data['email']);
                $message->subject($data['subject']);
            });
            
            $emailConfig = EmailConfig::first();
            $emailConfig->status = 1;
            $emailConfig->save();
            
            return back()->with(['settings_message_success' => 'SMTP settings are verified successfully!']);
        }
        catch (\Exception $e)
        {
            $emailConfig = EmailConfig::first();
            $emailConfig->status = 2;
            $emailConfig->save();
            
            return back()->with(['settings_message_error' => 'Email Settings Updated fail']);
        }
    }
}

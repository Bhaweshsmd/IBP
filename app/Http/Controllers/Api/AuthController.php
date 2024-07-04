<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
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
use App\Models\User;
use App\Models\Fee;
use App\Models\EventType;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use App\Models\EmailTemplate;
use App\Models\SalonBookingSlots;
use App\Models\Taxes;
use App\Models\Card;
use App\Models\CardsTransaction;
use App\Models\AdminWithdrawRequest;
use App\Models\PlatformEarningHistory;
use App\Models\RevenueSetting;
use App\Models\PlatformData;
use App\Models\Admin;
use App\Models\AdminEmailTemplate;
use App\Models\CardmembershipFee;
use App\Models\Device;
use App\Models\UsersLoginLocation;
use App\Jobs\SendEmail;
use App\Jobs\SendNotification;
use App\Jobs\PlatformEarning;
use App\Services\TwilioService;
use Twilio\Jwt\ClientToken; 
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use DB;
use Session;
use App\Models\AdminNotificationTemplate;
use App\Models\Language;
use App\Models\Country;
use App\Models\NotificationTemplate;
use App\Jobs\SendAdminNotification;

class AuthController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService, Auth $auth)
    {
        $this->twilioService = $twilioService;
    }
    
    function register(Request $request)
    { 
        $rules = [
            'first_name'=>'required',
            'phone_number'=>'required|unique:users',
            'email'=>'required|unique:users',
            'country_code' => 'required',
            'identity' => 'required',
            'user_type'=>'required',
            'password' => 'required|same:confirm_password'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $user = new Users();  
        $user->identity = $request->identity;
        $user->fullname = GlobalFunction::cleanString($request->fullname);
        $user->first_name = GlobalFunction::cleanString($request->first_name);
        $user->last_name = GlobalFunction::cleanString($request->last_name);
        $user->country_code = $request->country_code;
        $user->phone_number = $request->phone_number;
        $user->formated_number = $request->country_code.$request->phone_number;
        $user->email = $request->email;
        $user->user_type = $request->user_type;
        $user->password = Hash::make($request->password);
        $user->added_by = "app";
        $user->is_verified = 1;
        $user->save();

        $user = Users::where('id', $user->id)->withCount('bookings')->first();
        $profile_id='IBPC'.$user->id;
        Users::where('id',$user->id)->update(['profile_id'=>$profile_id]);
        
        try{
            $user_name = $user->first_name.' '.$user->last_name;
            
            $check_device = Device::where('user_id', $user->id)->first();
            if(!empty($check_device->language)){
                $act_lang = $check_device->language;
            }else{
                $act_lang = '1';
            }
            
            $settings = GlobalSettings::first();
            
            $email_template = EmailTemplate::find(3);
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
            
            $content = str_replace(["{user}","{email}","{phone}"], [$user_name,$user->email,$user->formated_number],$content);
            
            $details=[         
                "subject"=>$subject ,
                "message"=>$content,
                "to"=>$user->email,
            ];
            send_email($details);
           
            $superAdmin=Admin::where('user_type',1)->first();
            $bookingsuccesemail=AdminEmailTemplate::find(3);
            $admin_subject=$bookingsuccesemail->email_subjects??'';
            $admin_content=$bookingsuccesemail->email_content??''; 
            $admin_content=str_replace(["{user}","{email}","{phone}"], [$user_name,$user->email,$user->formated_number],$admin_content);
            
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
            
            $type = "register";
            $userNotification = NotificationTemplate::find(3);
            $title = $userNotification->notification_subjects;
            $message = strip_tags($userNotification->notification_content);
            
            $item = new UserNotification();
            $item->user_id = $user->id;
            $item->title = $title;
            $item->description = $message;
            $item->notification_type = $type;
            $item->temp_id = '3';
            $item->save();
            
            GlobalFunction::sendPushToUser($title, $message, $user);
            
            $adminNotification=AdminNotificationTemplate::find(3);
            $title=$adminNotification->notification_subjects;
            $message=strip_tags($adminNotification->notification_content);
            $message=str_replace(["{web}"],['App'],$message);
            dispatch(new SendAdminNotification($title,$message,$type,$user->id));
            
        }catch(\Exception $e){
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
        
        return GlobalFunction::sendDataResponse(true, 'User registration successful', $user);
    }
    
    public function register_device(Request $request) 
    {
        $userid     = $request->user_id;
        $device_id  = $request->device_id;
        $fcm_token  = $request->fcm_token;
        $device_name  = $request->device_name;
        $device_manufacture  = $request->device_manufacture;
        $device_model  = $request->device_model;
        $os_ver     = $request->os_ver;
        $device_os  = $request->device_os;
        $app_ver    = $request->app_ver;
        
        $checkuser = User::where('id', $userid)->first();

        $check_user = Device::where('user_id', $userid)->where('device_id', $device_id)->first();
        if(!empty($check_user)){
            
            Device::where('user_id', $userid)->where('device_id', $device_id)->update([
                'fcm_token'          => $fcm_token,
                'app_ver'            => $app_ver,
                'device_name'        => $device_name,
                'device_manufacture' => $device_manufacture,
                'device_model'       => $device_model,
                'os_ver'             => $os_ver,
                'device_os'          => $device_os,
                'language'           => $check_user->language
            ]);
            
            Device::where('device_id', $device_id)->whereNull('user_id')->delete();
            Device::where('device_id', $device_id)->whereNull('fcm_token')->delete();

            return response()->json(['status' => false, 'message' => 'Device updated successfully']);
        }else{
            
            $check_device = Device::where('user_id', $userid)->first();
            if(!empty($check_device)){
                Device::where('user_id', $userid)->update([
                    'fcm_token'          => null,
                    'user_id'            => null,
                ]);
                
                Device::where('device_id', $device_id)->update([
                    'fcm_token'          => null,
                    'user_id'            => null,
                ]);
                
                Device::insert([
                    'user_id'            => $userid,
                    'device_id'          => $device_id,
                    'fcm_token'          => $fcm_token,
                    'app_ver'            => $app_ver,
                    'device_name'        => $device_name,
                    'device_manufacture' => $device_manufacture,
                    'device_model'       => $device_model,
                    'os_ver'             => $os_ver,
                    'device_os'          => $device_os,
                    'language'           => $check_device->language
                ]);
                
                Device::whereNull('user_id')->delete();
                Device::whereNull('fcm_token')->delete();
                
                return response()->json(['status' => false, 'message' => 'Device updated successfully']);
            }else{
                Device::where('device_id', $device_id)->delete();
                Device::where('user_id', $userid)->delete();
                Device::where('device_id', $device_id)->whereNull('user_id')->delete();
                Device::where('device_id', $device_id)->whereNull('fcm_token')->delete();
                
                Device::create([
                    'user_id'            => $userid,
                    'device_id'          => $device_id,
                    'fcm_token'          => $fcm_token,
                    'app_ver'            => $app_ver,
                    'device_name'        => $device_name,
                    'device_manufacture' => $device_manufacture,
                    'device_model'       => $device_model,
                    'os_ver'             => $os_ver,
                    'device_os'          => $device_os,
                    'language'           => '1'
                ]);
                
                return response()->json(['status' => false, 'message' => 'Device updated successfully']);
            }
        }
    }
    
    public function send_otp(Request $request)
    {
        $rules= [
            'phone' => 'required',
            'email'=> 'required',
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        try{
            $otp = rand(111111,999999);
            $message = "Your verification code for Isidel Beach Park is ".$otp;
            
            $response = $this->twilioService->sendSMS($request->phone, $message);
            if($response->sid) { 
                $data['phone'] = $request->phone;
                $data['otp'] = $otp;
                $data['verified'] = 0;
                
                DB::table("phone_verification")->insert($data);
              
                $bookingsuccesemail=EmailTemplate::find(7);
                $subject=$bookingsuccesemail->email_subjects??'';
                $content=$bookingsuccesemail->email_content??''; 
                $content=str_replace(["{verification_otp}"],[$otp],$content);
                $details=[         
                    "subject" => $subject ,
                    "message" => $content,
                    "to" => $request->email,
                ];
                send_email($details);
                
                return response()->json(['status' => true, 'message' => 'OTP sent successfully.']);
            } else {
                return response()->json(['status' => false, 'message' =>"Failed to send OTP"]);
            }
        }catch(\Exception $e){
            return response()->json(['status' => false, 'message' =>$e->getMessage()]);
        }
    }
    
    public function verify_otp(Request $request)
    {
        $rules= [
            'phone' => 'required',
            'otp' => 'required',
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        try{
            $get_data= DB::table("phone_verification")->where('phone',$request->phone)->where('otp',$request->otp)->where('verified',0)->latest()->first();
        
            if (!empty($get_data)) { 
                DB::table("phone_verification")->where('phone', $request->phone)->where('otp', $request->otp)->update(['verified'=>1]);
                Users::where('formated_number', $request->phone)->update(['is_verified'=>1]);
                return response()->json(['status' => true, 'message' => 'OTP verified successfully.']);
            } else {
                return response()->json(['status' => false, 'message' =>"Invalid OTP"]);
            }
        }catch(\Exception $e){
            return response()->json(['status' => false, 'message' =>$e->getMessage()]);
        }
    }
    
    public function login(Request $request)
    {
        $rules= [
            'email' => 'required',
            'password' => 'required',
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        $email      = $request->email;
        $password   = $request->password;
        $device_id  = $request->device_id;
        $fcm_token  = $request->fcm_token;
        $device_name  = $request->device_name;
        $device_manufacture  = $request->device_manufacture;
        $device_model  = $request->device_model;
        $os_ver     = $request->os_ver;
        $device_os  = $request->device_os;
        $app_ver    = $request->app_ver;
        
        try{
            if (strpos($email, '@') !== false){
                $user = User::where('email', $email)->first();
            }else{
                $user = User::where(['phone_number' => $email])->orWhere(['formated_number' => $email])->first();
            }
            
            if(Auth::attempt(['email' => $user->email, 'password' => $password]))
            {   
                $check_device = Device::where('user_id', $user->id)->where('device_id', $device_id)->first();
                if(!empty($check_device)){
                    $language = $check_device->language;
                }else{
                    $language = '1';
                }
                
                UsersLoginLocation::create([
                    'user_id'            => $user->id,
                    'ip_address'         => $request->ip(),
                    'device_id'          => $device_id,
                    'fcm_token'          => $fcm_token,
                    'app_ver'            => $app_ver,
                    'device_name'        => $device_name,
                    'device_manufacture' => $device_manufacture,
                    'device_model'       => $device_model,
                    'os_ver'             => $os_ver,
                    'device_os'          => $device_os,
                    'language'           => $language
                ]);

                User::where('id', $user->id)->update([
                    'last_login_at' => $request->last_login_at,
                    'last_login_ip' => $request->ip(),
                ]);
               
                $fullName                  = $user->first_name . ' ' . $user->last_name;
                $accessToken               = DB::table('personal_access_tokens')->where('tokenable_id', $user->id);
                $getAccessToken            = $accessToken->first();
                if (empty($getAccessToken)){
                    $token = $user->createToken($fullName)->accessToken;
                }else{
                    $accessToken->delete(); 
                    $token = $user->createToken($fullName)->accessToken;
                }
                
                $data['user_id'] = $user->id;
                $data['token'] = $token->token;
                $data['last_login'] = $request->last_login_at;
                
                return GlobalFunction::sendDataResponse(true, 'User login successfully', $data);
            }else{
                return response()->json(['status' => false, 'message' => 'Invalid Credentials']);
            }
        }catch(\Exception $e){
            return response()->json(['status' => false, 'message' =>$e->getMessage()]);
        }
    }
    
    public  function forgot_password(Request $request)
    {
        $rules = [
            'country_code' => 'required',
            'phone_number' => 'required',
            'new_password' => 'required|same:confirm_password'
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()){
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' =>$msg]);
        }
             
        $country_code = $request->country_code;
        $phone_number = $request->phone_number;
        $formatted_phone = $country_code.$phone_number;
        
        $user = Users::where('formated_number', $formatted_phone)->first();
        if(!empty($user)){
            try{
                $password = Hash::make($request->new_password);
                Users::where('id', $user->id)->update(['password'=> $password]);
                return response()->json(['status' => true, 'message' =>"Password Updated Succefully"]);
            }catch(\Exception $e){
                return $data=['status' => false, 'message' =>$e->getMessage()];
            }
        }else{
            return response()->json(['status' => false, 'message' =>"User not exists"]);
        }
    }
    
    public  function get_countries()
    {
        $countries = Country::where('status', '1')->get();
        foreach($countries as $country){
            $country['flag'] = url('flags/'.strtolower($country->short_name).'.png');
        }
        return GlobalFunction::sendDataResponse(true, 'Countries fetched successfully', $countries);
    }
    
    public function logout(Request $request) 
    {
        $rules= [
            'user_id' => 'required'
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        $user_id = $request->user_id;
        $user  = User::where('id', $user_id)->first();
        if (empty($user)){
            return response()->json(['status' => false, 'message' => 'User not exists']);
        }
        
        return response()->json(['status' => true, 'message' => 'Logout from devices successfully']);
    }
    
    function check_user(Request $request)
    {
        $rules = [
            'formated_number'=>'required|unique:users',
            'email'=>'required|unique:users',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        } 
        
        return response()->json(['status' => true, 'message' =>"User not exists."]);
    }
    
    function check_phone(Request $request)
    {
        $rules = [
            'formated_number'=>'required|unique:users'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        } 
        
        return response()->json(['status' => true, 'message' => "Phone not exists."]);
    }
    
    function check_email(Request $request)
    {
        $rules = [
            'email'=>'required|unique:users'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        } 
        
        return response()->json(['status' => true, 'message' => "Email not exists."]);
    }
}
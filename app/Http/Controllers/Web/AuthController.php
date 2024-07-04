<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Mockery\Generator\StringManipulation\Pass\ConstantsPass;
use PHPUnit\TextUI\XmlConfiguration\Constant;
use Symfony\Component\VarDumper\Caster\ConstStub;
use App\Models\SalonBookingSlots;
use App\Models\Taxes;
use App\Models\Fee;
use App\Models\UserWalletStatements;
use App\Models\Users;
use App\Models\Constants;
use App\Models\GlobalFunction;
use App\Models\GlobalSettings;
use App\Models\SalonCategories;
use App\Models\SalonImages;
use App\Models\Salons;
use App\Models\ServiceImages;
use App\Models\Services;
use App\Models\Bookings;
use App\Models\UserNotification;
use App\Models\PlatformData;
use App\Models\PlatformEarningHistory;
use App\Models\SalonEarningHistory;
use App\Models\SalonPayoutHistory;
use App\Models\SalonReviews;
use App\Models\SalonWalletStatements;
use App\Models\EventType;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use App\Models\UserWalletRechargeLogs;
use App\Models\UserWithdrawRequest;
use App\Models\EmailTemplate;
use App\Models\AdminEmailTemplate;
use App\Models\Admin;
use App\Models\Card;
use App\Models\CardsTransaction;
use App\Models\SalonGallery;
use App\Models\SalonMapImage;
use App\Models\NotificationTemplate;
use App\Models\AdminNotificationTemplate;
use App\Models\Page;
use App\Models\Blog;
use App\Models\Coupons;
use App\Models\BankDetail;
use App\Models\WebBanner;
use App\Models\UsedCoupon;
use App\Models\UsersLoginLocation;
use App\Jobs\SendNotification;
use App\Jobs\SendEmail;
use App\Jobs\SendAdminNotification;
use App\Jobs\PlatformEarning;
use App\Services\TwilioService;
use Session;
use DB;
use Stripe;
use Carbon\Carbon;
use App\Models\Device;
use App\Models\Language;

class AuthController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService, Auth $auth)
    {  
        $this->middleware('CheckAccountVerification',['except' => ['accountVerification', 'accountVerificationOtp','login','logout']]);
        $this->twilioService = $twilioService;
    }
    
    public function getBrowser() 
    { 
        $u_agent = $_SERVER['HTTP_USER_AGENT']; 
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version= "";
    
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        }
        elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        }
        elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }
        
        if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
        { 
            $bname = 'Internet Explorer'; 
            $ub = "MSIE"; 
        } 
        elseif(preg_match('/Firefox/i',$u_agent)) 
        { 
            $bname = 'Mozilla Firefox'; 
            $ub = "Firefox"; 
        } 
        elseif(preg_match('/Chrome/i',$u_agent)) 
        { 
            $bname = 'Google Chrome'; 
            $ub = "Chrome"; 
        } 
        elseif(preg_match('/Safari/i',$u_agent)) 
        { 
            $bname = 'Apple Safari'; 
            $ub = "Safari"; 
        } 
        elseif(preg_match('/Opera/i',$u_agent)) 
        { 
            $bname = 'Opera'; 
            $ub = "Opera"; 
        } 
        elseif(preg_match('/Netscape/i',$u_agent)) 
        { 
            $bname = 'Netscape'; 
            $ub = "Netscape"; 
        } 
        
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
        ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
        }
        
        $i = count($matches['browser']);
        if ($i != 1) {
            if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
                $version= $matches['version'][0];
            }
            else {
                $version= $matches['version'][1];
            }
        }
        else {
            $version= $matches['version'][0];
        }
        
        if ($version==null || $version=="") {$version="?";}
        
        return array(
            'userAgent' => $u_agent,
            'name'      => $bname,
            'version'   => $version,
            'platform'  => $platform,
            'pattern'    => $pattern
        );
    } 
    
    public function register()
    {
        $data['countries'] = DB::table('countries')->get();
        return view('web.auth.register',$data);
    }
    
    public function otp_verify(Request $request)
    {
        $rules = [
            'first_name'=>'required',
            'phone_number'=>'required|unique:users',
            'email'=>'required|unique:users',
            'identity' => 'required',
            'user_type'=>'required',
            'country'=>'required',
            'password' => 'required|same:confirm_password'
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()){
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return back()->withErrors($validator)->withInput();
        }
        
        Session::put('registrationDetails',$request->except('_token'));
        $data['registrationDetails']=$request->except('_token');
        $data['formated_number']=$formated_number=$request->country.$request->phone_number;
        
        Session::put('formated_number',$formated_number);
        Session::put('otp_email',$request->email);
        $response= $this->sendOTP($formated_number,$request->email);
        
        if(!$response['status']){
            return back()->withErrors($response)->withInput(); 
        }
        
        return redirect()->to('otp-verification');
    }
    
    public function otp_verification(Request $request)
    {
        $registrationDetails = Session::get('registrationDetails');
        return view('web.auth.otp-verify');
    }
    
    function register_user(Request $request)
    {
        $settings = GlobalSettings::first();
        $registrationDetails=  Session::get('registrationDetails');
        $formated_number= Session::get('formated_number');

        try{ 
            $user = new Users();  
            $user->identity = $registrationDetails['identity'];
            $user->fullname = GlobalFunction::cleanString($registrationDetails['first_name']);
            $user->first_name = GlobalFunction::cleanString($registrationDetails['first_name']);
            $user->last_name = GlobalFunction::cleanString($registrationDetails['last_name']);
            $user->phone_number = $registrationDetails['phone_number'];
            $user->formated_number = $formated_number;
            $user->email = $registrationDetails['email'];
            $user->country_code=$registrationDetails['country']??'';
            $user->device_type = null;
            $user->device_token = null;
            $user->login_type = null;
            $user->user_type = $registrationDetails['user_type'];
            $user->firebase_uid = $createdUser->uid??'';
            $user->password = Hash::make($registrationDetails['password']);
            $user->added_by="web";
            $user->is_verified=1;
            $user->save();
        
            $user = Users::where('id', $user->id)->withCount('bookings')->first();
            $profile_id='IBPC'.$user->id;
            Users::where('id',$user->id)->update(['profile_id'=>$profile_id]);

            $user_name = $user->first_name.' '.$user->last_name;
            
            $check_device = Device::where('user_id', $user->id)->first();
            if(!empty($check_device->language)){
                $act_lang = $check_device->language;
            }else{
                $act_lang = '1';
            }
            
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
            
            $content = str_replace(["{user}","{email}","{phone}"], [$user_name,$registrationDetails['email'],$formated_number],$content);
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
            $admin_content=str_replace(["{user}","{email}","{phone}"],
            [$user_name,$registrationDetails['email'],$formated_number],$admin_content);
            
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
            $title=$adminNotification->notification_subjects??'';
            $message=strip_tags($adminNotification->notification_content??'');
            $message=str_replace(["{web}"],['Web'],$message);
            dispatch(new SendAdminNotification($title,$message,$type,$user->id));
            
            $remember_me = $request->has('remember_token') ? true : false;  
        
            if (Auth::attempt(["email"=>$registrationDetails['email'],'password'=>$registrationDetails['password']],$remember_me)){
                Session::put('user_id',$user->id);
                Session::put('user_type',$user->user_type);
                Session::put('user_name',$user->first_name);
                
                $current_time = Carbon::now()->format('d-M-Y H:i:s');
                $browser = $this->getBrowser();
                
                UsersLoginLocation::create([
                    'user_id'       => $user->id,
                    'ip_address'    => $request->ip(),
                    'browser'       => $browser['name'],
                    'version'       => $browser['version'],
                    'platform'      => $browser['platform'],
                ]);
    
                Users::where('id', $user->id)->update([
                    'last_login_at' => $current_time,
                    'last_login_ip' => $request->ip(),
                ]);
                
                return redirect('user-dashboard');
            }
            
        }catch(\Exception $e){
            return $data=['status' => false, 'message' =>$e->getMessage()];
        }
        
        return redirect('login');
    }
    
    public function resend_otp(Request $request)
    {
        $formated_number= Session::get('formated_number');
        $otp_email= Session::get('otp_email');
        $response= $this->sendOTP($formated_number,$otp_email);
        if($response['status']){
            return $data=['status' => true, 'message' => 'OTP Resent successfully.'];
        }
        return $data=['status' => false, 'message' => 'Something Went Wrong.'];
    }
    
    public function login()
    {
        return view('web.auth.login');
    }
    
    public function user_login(Request $request)
    {
        $rules = [
            'email' => 'required',
            'password' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return back()->with(['status' => false, 'message' => $msg])->withInput();
        }
        
        $checkEmail=Users::where('email',$request->email)->first();
        $msg="Invalid Email or Password";
        if(empty($checkEmail)){
            return back()->with(['status' => false, 'message' => $msg])->withInput();
        }
        
        $remember_me = $request->has('remember_token') ? true : false;  
        
        if (Auth::attempt(["email"=>$request->email,'password'=>$request->password],$remember_me)){
            Session::put('user_id',$checkEmail->id);
            Session::put('user_type',$checkEmail->user_type);
            Session::put('user_name',$checkEmail->first_name);
            
            $current_time = Carbon::now()->format('d-M-Y H:i:s');
            $browser = $this->getBrowser();
            
            UsersLoginLocation::create([
                'user_id'       => $checkEmail->id,
                'ip_address'    => $request->ip(),
                'browser'       => $browser['name'],
                'version'       => $browser['version'],
                'platform'      => $browser['platform'],
            ]);

            Users::where('id', $checkEmail->id)->update([
                'last_login_at' => $current_time,
                'last_login_ip' => $request->ip(),
            ]);
            
            return  redirect('user-dashboard');
        }else{
            return back()->with(['status' => false, 'message' => $msg])->withInput();
        }
    }
    
    public function sendOTP($formated_number=null,$email=null)
    {
        $opt = rand(111111,999999);
        $message = "Your verification code for Isidel Beach Park is ".$opt;
        
        try{
            $response = $this->twilioService->sendSMS($formated_number, $message);
            if ($response->sid) { 
                $data['phone'] = $formated_number;
                $data['otp'] = $opt;
                $data['verified'] = 0;
                DB::table("phone_verification")->insert($data);

                $bookingsuccesemail=EmailTemplate::find(7);
                $subject=$bookingsuccesemail->email_subjects??'';
                $content=$bookingsuccesemail->email_content??''; 
                $content=str_replace(["{verification_otp}"],[$opt],$content);
                $details=[         
                    "subject"=>$subject ,
                    "message"=>$content,
                    "to"=>$email,
                ];
                send_email($details);
                
                return $data = ['status' => true, 'message' => 'OTP sent successfully.'];
            }else{
                return $data = ['status' => false, 'message' => 'Failed to send OTP'];
            }
        }catch(\Exception $e){
            return $data=['status' => false, 'message' =>$e->getMessage()];
        }
    }
    
    public function forgot_password(Request $request)
    {
        if($request->isMethod('post')){
            $rules = [
                'country' => 'required',
                'phone_number' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $messages = $validator->errors()->all();
                $msg = $messages[0];
                 return back()->withErrors($validator)->withInput();
            }
            
            $phone_number = $request->country.$request->phone_number;
        
            $check_phone = Users::where('formated_number', $phone_number)->first();
            if(empty($check_phone)){
                $msg="Invalid phone number";
                return back()->withErrors($msg)->withInput();
            }
          
            $this->sendOTP($phone_number, $check_phone->email);
          
            $registrationDetails['country'] = $request->country;
            $registrationDetails['phone_number'] = $request->phone_number;
            Session::put('formated_number', $phone_number);
            Session::put('registrationDetails', $registrationDetails);
            
            return redirect()->to('forgot-otp-verification');
        }else{
            $data['countries'] = DB::table('countries')->get();
            return view('web.auth.forgot-password', $data);
        }
    }
    
    public  function forgot_otp_verification(Request $request)
    {
        if($request->isMethod('post')){
            $rules= [
                'otp' => 'required',
            ];
            
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $messages = $validator->errors()->all();
                $msg = $messages[0];
                return back()->withErrors($validator)->withInput();
            } 
            
            $registrationDetails = Session::get('registrationDetails');
            $formated_number = Session::get('formated_number');

            try{
                $get_data = DB::table("phone_verification")->where('phone',$formated_number)->where('otp',$request->otp)->where('verified',0)->latest()->first();
                if (!empty($get_data)){ 
                    DB::table("phone_verification")->where('phone',$formated_number)->where('otp',$request->otp)->update(['verified'=>1]);
                    return redirect('update-forgot-password');
                }else{
                    $msg="Wrong OTP";
                    return back()->withErrors($msg)->withInput();
                }
            }catch(\Exception $e){
                return back()->withErrors($e->getMessage())->withInput();
            }
        }else{
            $data['countries']=DB::table('countries')->get();
            return view('web.auth.forgot-password-otp-verification',$data);
        }
    }
    
    public function update_forgot_password(Request $request)
    {
        if($request->isMethod('post')){
            $rules = [
                'password' =>'required',
                'password' => 'required|same:confirm_password'
            ];
            
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()){
                $messages = $validator->errors()->all();
                $msg = $messages[0];
                return back()->withErrors($msg)->withInput();
            }
             
            $user_id = Session::get('user_id');
            $formated_number = Session::get('formated_number');
            $registrationDetails = Session::get('registrationDetails');
            
            $user = Users::where('formated_number', $formated_number)->first();
            if(empty($user)){
                $msg="Invalid phone number";
                return back()->withErrors($msg)->withInput();
            }
            
                
            try{
                $password = Hash::make($request->password);
                Users::where('id', $user->id)->update(['password'=> $password]);
                
                $msg="Password Updated Succefully";
                return redirect('login')->withSuccess($msg)->withInput();
            }catch(\Exception $e){
                return back()->withErrors($e->getMessage())->withInput();
            }
        }else{
            return view('web.auth.forgot-password-update');
        }
    }
    
    public function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect('/');
    }
}
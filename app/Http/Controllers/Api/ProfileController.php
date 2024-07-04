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
use App\Models\Language;
use App\Models\LanguageContent;
use App\Jobs\SendEmail;
use App\Jobs\SendNotification;
use App\Jobs\PlatformEarning;
use App\Services\TwilioService;
use Twilio\Jwt\ClientToken;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use DB;
use Session;

class ProfileController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService, Auth $auth)
    {
        $this->twilioService = $twilioService;
    }
    
    function user_profile(Request $request)
    {
        $rules = [
            'user_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $user = Users::where('id', $request->user_id)->withCount('bookings')->first();
        return GlobalFunction::sendDataResponse(true, 'User details fetched successful', $user);
    }
    
    function update_profile(Request $request)
    {
        $rules = [
            'user_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        $user_id = $request->user_id;
        $user = Users::find($request->user_id);
        
        if (empty($user)) {
            return response()->json(['status' => false, 'message' => "User not exists!"]);
        }
        
        if ($request->has('first_name')) {
            $user->first_name = GlobalFunction::cleanString($request->first_name);
        }
        
        if ($request->has('last_name')) {
            $user->last_name = GlobalFunction::cleanString($request->last_name);
        }
        
        if ($request->has('identity')) {
            $user->identity = GlobalFunction::cleanString($request->identity);
        }
        
        if ($request->has('fullname')) {
            $user->fullname = GlobalFunction::cleanString($request->fullname);
        }
        
        if ($request->has('is_notification')) {
            $user->is_notification = $request->is_notification;
        }
        
        if ($request->has('profile_image')) {
            $user->profile_image = GlobalFunction::saveFileAndGivePath($request->profile_image);
        }
        $user->save();

        $user = Users::where('id', $user->id)->withCount('bookings')->first();
        return GlobalFunction::sendDataResponse(true, 'user updated successfully', $user);
    }
    
    public function get_langauages()
    {
        $languages = Language::where('status','Active')->get();
        return GlobalFunction::sendDataResponse(true, 'Languages fetched successfully', $languages);
    }
    
    function update_language(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'language_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $user = Users::where('id', $request->user_id)->first();
        if (empty($user)) {
            return response()->json(['status' => false, 'message' => "User not exists!"]);
        }
        
        $language = Language::where('id', $request->language_id)->first();
        if (empty($language)) {
            return response()->json(['status' => false, 'message' => "Language not exists!"]);
        }
        
        Device::where('user_id', $request->user_id)->update([
            'language' => $request->language_id
        ]);
        
        return response()->json(['status' => true, 'message' => 'Language updated successfully.']);
    }
    
    function langauage_contents(Request $request)
    {
        $device = Device::where('device_id', $request->device_id)->first();
        if (empty($device)) {
            $device_lang = '1';
        }else{
            $device_lang = $device->language;
        }
        
        $language = Language::where('id', $device_lang)->first();
        $lang = $language->short_name;
        $languages = LanguageContent::select('string', $lang)->where('active', '1')->get();

        foreach($languages as $value){
            $data[$value->string] = $value->$lang;
        }
        
        return GlobalFunction::sendDataResponse(true, 'Data fetch successfully', $data);
    }
    
    public function check_app_update(Request $request) 
    {
        $rules = [
            'platform' => 'required',
            'app_ver' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        $platform = $request->platform;
        $app_ver = $request->app_ver;
        
        $settings = GlobalSettings::first();
    
        if($platform == 'android'){
            $app_version = $settings->android_version;
            $app_url = $settings->android_url;
        }elseif($platform == 'ios'){
            $app_version = $settings->ios_version;
            $app_url = $settings->ios_url;
        }
            
        if($app_version > $app_ver){
            $data['update_available']  =  true;
        }else{
            $data['update_available']  =  false;
        }
                
        return GlobalFunction::sendDataResponse(true, 'App version fetched successfully', $data);
    } 
    
    public  function change_password(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'old_password' => 'required',
            'new_password' => 'required|same:confirm_password'
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()){
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' =>$msg]);
        }
             
        $user_id = $request->user_id;
        $user = Users::where('id', $user_id)->first();
        
        if (!Hash::check($request->old_password, $user->password)) { 
            return response()->json(['status' => false, 'message' =>"old Password is wrong"]);
        }

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
    
    function notifications_list(Request $request)
    {
        $rules = [
            'start' => 'required',
            'count' => 'required',
            'user_id'=>'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        $device = Device::where('user_id', $request->user_id)->first();
        if(!empty($device->language)){
            $cont_lang = $device->language;
        }else{
            $cont_lang = '1';
        }
        
        $notifications = UserNotification::where('user_id',$request->user_id)->orWhere('notification_type','promotional')->offset($request->start)->limit($request->count)->orderBy('id', 'DESC')->get();
        
        foreach($notifications as $notification){
            $template = DB::table('notification_template')->where('id', $notification->temp_id)->first();
            if(!empty($template)){
                if($cont_lang == '1'){
                    $subject = $template->notification_subjects;
                    $content = $template->notification_content;
                }elseif($cont_lang == '2'){
                    $subject = $template->notification_subject_pap;
                    $content = $template->notification_content_pap;
                }elseif($cont_lang == '3'){
                    $subject = $template->notification_subject_nl;
                    $content = $template->notification_content_nl;
                }
                
                if($notification->temp_id == '1'){
                    $content = str_replace(["{amount}"],[$notification->amount], $content);
                }elseif($notification->temp_id == '2'){
                    $subject = str_replace(["{booking_id}"],[$notification->booking_id], $subject);
                    $content = str_replace(["{item_name}","{booking_id}"],[$notification->item_name,$notification->booking_id], $content);
                }elseif($notification->temp_id == '4'){
                    $content = str_replace(["{booking_id}"],[$notification->booking_id], $content);
                }elseif($notification->temp_id == '5'){
                    $content = str_replace(["{booking_id}"],[$notification->booking_id], $content);
                }elseif($notification->temp_id == '6'){
                    $content = str_replace(["{total}"],[$notification->total], $content);
                }elseif($notification->temp_id == '7'){
                    $content = str_replace(["{total}"],[$notification->total], $content);
                }elseif($notification->temp_id == '8'){
                    $content = str_replace(["{amount}"],[$notification->amount], $content);
                }elseif($notification->temp_id == '9'){
                    $content = str_replace(["{card_number}"],[$notification->card_number], $content);
                }elseif($notification->temp_id == '10'){
                    $content = str_replace(["{amount}","{card_number}"],[$notification->amount,$notification->card_number], $content);
                }elseif($notification->temp_id == '11'){
                    $content = str_replace(["{ticket_id}"],[$notification->ticket_id], $content);
                }elseif($notification->temp_id == '12'){
                    $content = str_replace(["{date_time}"],[$notification->date_time], $content);
                }elseif($notification->temp_id == '14'){
                    $content = str_replace(["{ticket_id}"],[$notification->ticket_id], $content);
                }elseif($notification->temp_id == '15'){
                    $subject = str_replace(["{ticket_id}"],[$notification->ticket_id], $subject);
                    $content = str_replace(["{ticket_id}"],[$notification->ticket_id], $content);
                }
            }else{
                $subject = $notification->title;
                $content = $notification->description;
            }
            
            $notification['id'] = $notification->id;
            $notification['title'] = strip_tags($subject);
            $notification['description'] = strip_tags($content);
            $notification['notification_time'] = date('Y-m-d H:i:s',strtotime($notification->created_at));
        }
        
        return GlobalFunction::sendDataResponse(true, 'Data fetched successfully', $notifications);
    }
    
    function remove_account(Request $request)
    {
        $rules = [
            'user_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $user = Users::find($request->user_id);
        if ($user == null) {
            return response()->json(['status' => false, 'message' => "User doesn't exists!"]);
        }

        UserWalletStatements::where('user_id', $user->id)->delete();
        $user->delete();

        return GlobalFunction::sendSimpleResponse(true, "User account deleted successfully");
    }
    
    public function card_details(Request $request)
    {
        $rules = [
            'user_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        $user_id = $request->user_id;
        $user = Users::find($user_id);
        if ($user == null) {
            return response()->json(['status' => false, 'message' => "User doesn't exists!"]);
        }
        
        $userDetails=Users::where('id',$user_id)->first();
           
        $data['carddetails']=Card::where('assigned_to',$user_id)->first();
        $data['cardtransaction']=CardsTransaction::where('user_id',$user_id)->latest()->get();
        
        return GlobalFunction::sendDataResponse(true, 'Crad details fetched successfully', $data);
    }
}
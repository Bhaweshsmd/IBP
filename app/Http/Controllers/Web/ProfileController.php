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
use App\Jobs\SendNotification;
use App\Jobs\SendEmail;
use App\Jobs\SendAdminNotification;
use App\Jobs\PlatformEarning;
use App\Services\TwilioService;
use Session;
use DB;
use Stripe;
use Carbon\Carbon;

class ProfileController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService, Auth $auth)
    {  
        $this->middleware('CheckAccountVerification',['except' => ['accountVerification', 'accountVerificationOtp','login','logout']]);
        $this->twilioService = $twilioService;
    }
    
    public function user_profile()
    {
        $user_id = Session::get('user_id');
        $userDetails = Users::where('id',$user_id)->first();
        $data['settings'] = GlobalSettings::first();
        $data['allbookings'] = Bookings::with(['user', 'salon','service','service.category'])->where('user_id',$user_id)->orderBy('id', 'DESC')->get();
        
        $favourite_services = explode(',',$userDetails->favourite_services);
        $data['wishlist'] = Services::whereIn('id',$favourite_services)->orderBy('id', 'DESC')->get();
        $data['user_details'] = $userDetails;
        
        if($userDetails->user_type){
            $user_type="Non Resident";
        }else{
            $user_type="Resident";  
        }
        
        $data['user_type'] = $user_type;
        if(!empty($userDetails->profile_image)){
            $data['imgUrl'] = GlobalFunction::createMediaUrl($userDetails->profile_image);
        }else{
            $data['imgUrl'] = url('profile.jpg');
        }
        
        return view('web.users.profile', $data);
    }
    
    function update_user(Request $request)
    {   
        $user_id = Session::get('user_id');
      
        $rules = [
            'first_name' =>'required',
            'last_name' =>'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        $user = Users::find($user_id);
        if (empty($user)){
            return response()->json(['status' => false, 'message' => "User doesn't exists!"]);
        }

        if ($request->has('first_name')) {
            $user->first_name = GlobalFunction::cleanString($request->first_name);
        }
        
        if ($request->has('last_name')) {
            $user->last_name = GlobalFunction::cleanString($request->last_name);
        }

        if ($request->has('favourite_salons')){
            $user->favourite_salons = GlobalFunction::cleanString($request->favourite_salons);
        }

        if ($request->has('is_notification')) {
            $user->is_notification = $request->is_notification;
        }
        
        if ($request->has('profile_image')) {
            $user->profile_image = GlobalFunction::saveFileAndGivePath($request->profile_image);
        }
        $user->save();

        $user = Users::where('id', $user->id)->withCount('bookings')->first();
        return GlobalFunction::sendDataResponse(true, 'Profile updated successfully', $user);
    }
    
    public function user_password()
    {
        $user_id = Session::get('user_id');
        $userDetails = Users::where('id',$user_id)->first();
        $data['settings'] = GlobalSettings::first();
        $data['allbookings'] = Bookings::with(['user', 'salon','service','service.category'])->where('user_id',$user_id)->orderBy('id', 'DESC')->get();
        
        $favourite_services=explode(',',$userDetails->favourite_services);
        $data['wishlist'] = Services::whereIn('id',$favourite_services)->orderBy('id', 'DESC')->get();
        $data['user_details']=$userDetails;
        
        if($userDetails->user_type){
            $user_type="Non Resident";
        }else{
            $user_type="Resident";  
        }
        
        $data['user_type'] = $user_type;
        $data['imgUrl'] = GlobalFunction::createMediaUrl($userDetails->profile_image);
        
        return view('web.users.password',$data);
    }
    
    public  function update_password(Request $request)
    {
        $rules = [
            'old_password' =>'required',
            'new_password' => 'required|same:confirm_password'
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()){
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' =>$msg]);
        }
             
        $user_id = Session::get('user_id');
        $user = Users::where('id', $user_id)->first();
        
        if (!Hash::check($request->old_password, $user->password)) { 
            return response()->json(['status' => false, 'message' =>"old Password is wrong"]);
        }

        if(!empty($user)){
            try{
                $password = Hash::make($request->new_password);
                Users::where('id',$user->id)->update(['password'=> $password]);
                return response()->json(['status' => true, 'message' =>"Password Updated Succefully"]);
            }catch(\Exception $e){
                return $data=['status' => false, 'message' =>$e->getMessage()];
            }
        }else{
            return response()->json(['status' => false, 'message' =>"User not exists"]);
        }
    }
}
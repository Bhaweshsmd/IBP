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

class WebController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService, Auth $auth)
    {  
        $this->middleware('CheckAccountVerification',['except' => ['account_verification', 'account_verification_otp','login','logout']]);
        $this->twilioService = $twilioService;
    }
    
    public function change_language(Request $request,$lang)
    {
        App::setLocale($lang);
        session()->put('locale', $lang);
        
        $userid = Session::get('user_id');

        $checkuser = Users::where('id', $userid)->first();
        $check_lang = Language::where('short_name', $lang)->first();
        $check_device = Device::where('user_id', $userid)->first();
        
        if(!empty($check_device)){
            Device::where('user_id', $userid)->update([
                'language' => $check_lang->id
            ]);
        }else{
            Device::create([
                'user_id'  => $userid,
                'language' => $check_lang->id
            ]);
        }
  
        return redirect()->back();
    }

    function apply_coupon(Request $request)
    { 
        $rules = [
            'coupon_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        $settings = GlobalSettings::first();
        
        $today=date('Y-m-d');
        $coupon = Coupons::where('id',$request->coupon_id)->whereDate('expiry_date','>=',$today)->first();
        $totalBookValue= Session::get('totalBookValue');
        if(empty($coupon)){
            $msg="Coupon not valid";
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        if(Session::get('coupon_id')){
            if(Session::get('coupon_id')!=$request->coupon_id){
                $msg="Please remove already applied coupon";
                return response()->json(['status' => false, 'message' => $msg]);
            }
        }
        
        $coupon_discount=0;
        if($totalBookValue < $coupon->min_order_amount){
            $msg="Order amount should be minimum ". $settings->currency.number_format($coupon->min_order_amount,2);
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        $coupon_discount=$totalBookValue*$coupon->percentage;
        if($coupon_discount>=$coupon->max_discount_amount){
            $amount_after_discount= $totalBookValue-$coupon->max_discount_amount;
            $coupon_discount=$coupon->max_discount_amount;
        }else{
            $amount_after_discount= $totalBookValue-$coupon_discount;
        }
        
        $msg="Coupon Applied Successfully";
        $data['statusText']="Applied";
        if(Session::get('coupon_id')==$request->coupon_id){
            $amount_after_discount=$totalBookValue;
            Session::put('coupon_id','');
            $msg="Coupon Removed Successfully";
            $data['statusText']="Apply Coupon";
        }else{
            Session::put('coupon_id',$request->coupon_id); 
        }
        
        Session::put('amount_after_discount',$amount_after_discount);
        Session::put('coupon_discount',$coupon_discount);
        $data['coupon_discount']=number_format($coupon_discount,2);
        $data['amount_after_discount']=number_format($amount_after_discount,2);
        return response()->json(['status' => true, 'message' =>$msg,'data'=>$data]);
    }
    
    public function user_dashboard(Request $request)
    {
        $user_id=Session::get('user_id');
        $userDetails=Users::where('id',$user_id)->first();
        $data['settings'] = GlobalSettings::first();
        $data['allbookings'] = Bookings::with(['user', 'salon','service','service.category'])->where('user_id',$user_id)->latest()->limit(5)->get();
        $data['allbookingsCount'] = Bookings::with(['user', 'salon','service','service.category'])->where('user_id',$user_id)->count();
        $data['total_transaction'] = UserWalletStatements::with(['user'])->where('user_id',$user_id)->sum('amount');
        $data['total_bookings']=Bookings::where('user_id', $user_id)->sum('payable_amount');

        $favourite_services=explode(',',$userDetails->favourite_services);
             
        if(session()->get('locale')=="pap"){
            $title=  "title_in_papiamentu as title"; 
            $rules=  "rules_in_papiamentu as rules"; 
            $about=  "about_in_papiamentu as about";
        }elseif(session()->get('locale')=="nl"){
            $title=  "title_in_dutch as title";  
            $rules=  "rules_in_dutch as rules"; 
            $about=  "about_in_dutch as about";
        }else{
            $title=  "title as title"; 
            $rules=  "rules as rules"; 
            $about=  "about as about";
        }
        
        if(Session::get('user_type')){
            $service = Services::select(['id','latitude','logintude',$title,'thumbnail','category_id','rating','slug','service_number','service_time',$about,$rules,'qauntity','foreiner_price as price','foreiner_price_per_day as price_per_day','slug','foreiner_discount as discount'])->where('status',1)->whereIn('id',$favourite_services)->with(['images','slots','reviews','reviews.user'])->limit(5)->get();
        }else{
            $service = Services::select(['id','latitude','logintude',$title,'service_number','category_id','rating','slug','thumbnail','service_time',$about,$rules,'qauntity','price','price_per_day','discount','slug'])->where('status',1)->whereIn('id',$favourite_services)->with(['images','slots','reviews','reviews.user'])->limit(5)->get(); 
        } 
        
        $data['wishlist'] = $service;
        $data['userDetails']=$userDetails;
        return view('web.users.dashboard',$data);
    }

    public function user_notification(Request $request)
    {
        $user_id=Session::get('user_id');
        $userDetails=Users::where('id',$user_id)->first();
        $data['settings'] = GlobalSettings::first();
        
        $data['fee']=Fee::where('type','deposit')->first();
        
        $data['notifications'] = UserNotification::where('user_id',$user_id)->orderBy('id', 'DESC')->get();
        $data['userDetails']=$userDetails;
        
        return view('web.users.notification',$data);
    }

    public function account_verification_otp(Request $request)
    {
        $user_id=Session::get('user_id');
        $userDetails=Users::where('id',$user_id)->first();
        $this->sendOTP($userDetails['formated_number'],$userDetails['email']);
        Session::put('otp-sent',1);
        return redirect('account-verification');
    }
    
    public function account_verification(Request $request)
    {
        $user_id=Session::get('user_id');
        $userDetails=Users::where('id',$user_id)->first();
        $data['userDetails']=$userDetails;
        
        if($request->isMethod('post')){
            $rules = [
                'otp' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()){
                $messages = $validator->errors()->all();
                $msg = $messages[0];
                return back()->withErrors($validator)->withInput();
            }
            
            try{
                $get_data= DB::table("phone_verification")->where('phone',$userDetails['formated_number'])->where('otp',$request->otp)->where('verified',0)->latest()->first();
                if (!empty($get_data)){ 
                    DB::table("phone_verification")->where('phone',$userDetails['formated_number'])->where('otp',$request->otp)->update(['verified'=>1]);
                    $msg="OTP verified successfully";
                    Users::where('id',$userDetails['id'])->update(['is_verified'=>1]);
                    return redirect('/user-dashboard')->withSuccess($msg);
                }else{
                    $msg="Wrong OTP";
                    return back()->withErrors($msg)->withInput();
                }
            }catch(\Exception $e){
                return back()->withErrors($e->getMessage())->withInput();
            }
        }
        
        $data['countries']=DB::table('countries')->get();
        return view('web.users.account-verification',$data);
    }
    
    public function otp_verification(Request $request)
    {
        $rules= [
            'otp' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        } 
        
        $registrationDetails=  Session::get('registrationDetails');
        $formated_number= Session::get('formated_number');
        $formated_number=$formated_number??$registrationDetails['country'].$registrationDetails['phone_number'];

        try{
           $get_data= DB::table("phone_verification")->where('phone',$formated_number)->where('otp',$request->otp)->where('verified',0)->latest()->first();
            if (!empty($get_data)) { 
                DB::table("phone_verification")->where('phone',$formated_number)->where('otp',$request->otp)->update(['verified'=>1]);
                return response()->json(['status' => true, 'message' => 'OTP Verified and Registered successfully.']);
            } else {
                return response()->json(['status' => false, 'message' =>" Wrong OTP"]);
            }
        }catch(\Exception $e){
            return response()->json(['status' => false, 'message' =>$e->getMessage()]);
        }
    }
    
    public function processing_fee(Request $request)
    {
        $settings = GlobalSettings::first();
        $rules = [
            'amount' => 'required',
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        $fee=Fee::where('type','deposit')->first();
        if($fee->maximum < $request->amount ){
            $message= "Amount should be minimum ". $settings->currency.number_format($fee->minimum,2)." and maximum ". $settings->currency.number_format($fee->maximum,2);
            return response()->json(['status' => false, 'message' =>$message]);    
        }
        if($fee->minimum > $request->amount){
            $message= "Amount should be minimum ". $settings->currency.number_format($fee->minimum,2)." and maximum ". $settings->currency.number_format($fee->maximum,2);
            return response()->json(['status' => false, 'message' =>$message]);    
        }
        
        $amount=$request->amount;
        $total_amount=$request->amount+($request->amount*$fee->charge_percent)/100;
        $charge_amount=$total_amount-$amount;
        $data['amount']=number_format($amount,2);
        $data['charge_amount']=number_format($charge_amount,2);
        $data['total_amount']=number_format($total_amount,2);
        
        Session::put('total_amount',$total_amount);
        Session::put('amount',$amount);
        Session::put('charge_amount',$charge_amount);
        
        return GlobalFunction::sendDataResponse(true, 'confimation successfully', $data);
    }
    
    public function withdraw_processing_fee(Request $request)
    {
        $settings = GlobalSettings::first();
        $rules = [
            'amount'=>  'required',
            'bank_id' => 'required',
        ];
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        $fee=Fee::where('type','withdraw')->first();
        
        if($fee->maximum < $request->amount ){
            $message= "Amount should be minimum ". $settings->currency.number_format($fee->minimum,2)." and maximum ". $settings->currency.number_format($fee->maximum,2);
            return response()->json(['status' => false, 'message' =>$message]);    
        }
        
        if($fee->minimum > $request->amount){
            $message= "Amount should be minimum ". $settings->currency.number_format($fee->minimum,2)." and maximum ". $settings->currency.number_format($fee->maximum,2);
            return response()->json(['status' => false, 'message' =>$message]);    
        }
        
        $amount=$request->amount;
        $total_amount=$request->amount-($request->amount*$fee->charge_percent)/100;
        $charge_amount=$amount-$total_amount;
        $data['amount']=number_format($amount,2);
        $data['charge_amount']=number_format($charge_amount,2);
        $data['total_amount']=number_format($total_amount,2);
        
        Session::put('total_amount',$total_amount);
        Session::put('amount',$amount);
        Session::put('charge_amount',$charge_amount);
        
        return GlobalFunction::sendDataResponse(true, 'confimation successfully', $data);
    }
    
    function add_rating(Request $request)
    {   
        $user_id=Session::get('user_id');
        
        $rules = [
            'booking_id' => 'required',
            'comment' => 'required',
        ];
        
        $customMessages = [
            'comment.required' => 'Comment can not be empty',
            'rating.required' => 'Please select at leat one rating'
        ];
      
        $validator = Validator::make($request->all(), $rules,$customMessages);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $booking = Bookings::where('booking_id', $request->booking_id)->with(['salon', 'user'])->first();
        if ($booking == null) {
            return response()->json(['status' => false, 'message' => "Booking doesn't exists!"]);
        }
        if ($booking->user_id != $user_id) {
            return response()->json(['status' => false, 'message' => "This booking doesn't belong to this user"]);
        }
        if ($booking->status != Constants::orderCompleted) {
            return response()->json(['status' => false, 'message' => "This booking is not yet completed to rate!"]);
        }
        if ($booking->is_rated == 1) {
            return response()->json(['status' => false, 'message' => "This booking has been rated already!"]);
        }
        $booking->is_rated = 1;
        $booking->save();

        $review = new SalonReviews();
        $review->user_id = $booking->user_id;
        $review->salon_id = $booking->service_id;
        $review->booking_id = $booking->id;
        $review->rating = $request->rating;
        $review->comment = GlobalFunction::cleanString($request->comment);
        $review->save();

        $salon = $review->service;
        $salon->rating = $salon->avgRating();
        $salon->save();
         
         
        $total_avg= SalonReviews::avg('rating');
        Salons::where('id',1)->update(['rating'=>$total_avg]);
        $msg="Booking rated successfully!";
        return back()->withSuccess($msg)->withInput();
        return GlobalFunction::sendDataResponse(true, 'Booking rated successfully!', $booking);
    }
    
    public function user_cards(Request $request)
    {
        $user_id=Session::get('user_id');
        $userDetails=Users::where('id',$user_id)->first();
        $data['settings'] = GlobalSettings::first();
        
        $data['carddetails']=Card::where('assigned_to',$user_id)->first();
        $data['cardtransaction']=CardsTransaction::where('user_id',$user_id)->latest()->get();
        
        $data['fee']=Fee::where('type','deposit')->first();
        $data['userDetails']=$userDetails;
        
        return view('web.users.cards',$data);
    }
}

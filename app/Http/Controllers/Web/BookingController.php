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

class BookingController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService, Auth $auth)
    {  
        $this->middleware('CheckAccountVerification',['except' => ['accountVerification', 'accountVerificationOtp','login','logout']]);
        $this->twilioService = $twilioService;
    }
    
    public function booking_details(Request $request,$slug=null)
    {
        Session::put('date','');
        Session::put('booking_hours','');
        Session::put('booking_time','');
        Session::put('quantity',1);
        
        $settings = GlobalSettings::first();
        $data['categories']=SalonCategories::with('services')->where('is_deleted',0)->where('parent',null)->get();
        
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
            $service = Services::select(['id','latitude','service_type','logintude',$title,'thumbnail','category_id','rating','slug','service_number','service_time',$about,$rules,'qauntity','foreiner_price as price','foreiner_price_per_day as price_per_day','slug','foreiner_discount as discount'])->where('status',1)->where('slug',$slug)->with(['images','slots','reviews','reviews.user'])->first();
        }else{
            $service = Services::select(['id','latitude','service_type','logintude',$title,'service_number','category_id','rating','slug','thumbnail','service_time',$about,$rules,'qauntity','price','price_per_day','discount','slug'])->where('status',1)->where('slug',$slug)->with(['images','slots','reviews','reviews.user'])->first(); 
        } 
          
        $data['service_type']=$service->service_type??0;
        $data['maximum_quantity']=$service->qauntity;
          
        $service_image= ServiceImages::where('service_id',$service->id)->get();
        $data['category']  =SalonCategories::where('id',$service->category_id)->first();
        
        Session::put('service_id',$service->id);
        Session::put('slug',$slug);
        $data['user_details']=Users::where('id',Session::get('user_id'))->first();

        $serviceBookingHour= SalonBookingSlots::where('salon_id',$service->id)->pluck('booking_hours');
        $data['booking_hours']=$serviceBookingHour->sort()->unique();
        $data['company_details']=Salons::first();
        $data['service_image']=$service_image;
        $data['services']=$service;
        $data['settings']=$settings;
        
        return view('web.bookings.details',$data);
    }
    
    public function booking_confirmation(Request $request,$slug=null)
    {
        $date=  Session::get('date');
        $booking_hours=  Session::get('booking_hours');
        $booking_time=   Session::get('booking_time');
        $quantity=  Session::get('quantity');
        if(empty($booking_time) || empty($quantity) || empty($date) || empty($booking_hours) ){
            return back();
        }
        
        $service_id=  Session::get('service_id');
        $settings = GlobalSettings::first();
        $data['categories']=SalonCategories::with('services')->where('is_deleted',0)->where('parent',null)->get();
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
            $service = Services::select(['id','latitude','logintude','service_type',$title,'thumbnail','category_id','rating','slug','service_number','service_time',$about,$rules,'qauntity','foreiner_price as price','foreiner_price_per_day as price_per_day','slug','foreiner_discount as discount'])->where('status',1)->where('id',$service_id)->with(['images','slots','reviews','reviews.user'])->first();
        }else{
            $service = Services::select(['id','latitude','logintude','service_type',$title,'service_number','category_id','rating','slug','thumbnail','service_time',$about,$rules,'qauntity','price','price_per_day','discount','slug'])->where('status',1)->where('id',$service_id)->with(['images','slots','reviews','reviews.user'])->first(); 
        } 
          
        $service_image= ServiceImages::where('service_id',$service->id)->get();
        $data['category']  =SalonCategories::where('id',$service->category_id)->first();
        
        Session::put('service_id',$service->id);
        $data['user_details']=Users::where('id',Session::get('user_id'))->first();
        
        $to_time = date('h:i A',strtotime('+'.$booking_hours.'hour',strtotime($booking_time)));
         
        if($booking_hours==16){
            $booking_hours="Whole Day";
        }
         
        $data['company_details']=Salons::first();
        $data['service_image']=$service_image;
        $data['services']=$service;
        $data['settings']=$settings;
        $data['booking_time']=$booking_time;
        $data['date']=$date;
        $data['booking_hours']=$booking_hours;
        $data['to_time']=$to_time;
        $data['quantity']=$quantity;
        
        return view('web.bookings.order-confirm',$data);
    }
    
    public function booking_confirmed(Request $request,$slug=null)
    {
        $date=  Session::get('date');
        $booking_hours=  Session::get('booking_hours');
        $booking_time=   Session::get('booking_time');
        $quantity=  Session::get('quantity');
        
        if(empty($booking_time) || empty($quantity) || empty($date) || empty($booking_hours) ){
           return back();
        }  
        
        $service_id=  Session::get('service_id');
        $settings = GlobalSettings::first();
        $data['categories']=SalonCategories::with('services')->where('is_deleted',0)->where('parent',null)->get();
        
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
            $service = Services::select(['id','latitude','service_type','logintude',$title,'thumbnail','category_id','rating','slug','service_number','service_time',$about,$rules,'qauntity','foreiner_price as price','foreiner_price_per_day as price_per_day','slug','foreiner_discount as discount'])->where('status',1)->where('id',$service_id)->with(['images','slots','reviews','reviews.user'])->first();
        }else{
            $service = Services::select(['id','latitude','logintude','service_type',$title,'service_number','category_id','rating','slug','thumbnail','service_time',$about,$rules,'qauntity','price','price_per_day','discount','slug'])->where('status',1)->where('id',$service_id)->with(['images','slots','reviews','reviews.user'])->first(); 
        } 
        $service_image = ServiceImages::where('service_id',$service->id)->get();
        $data['category'] = SalonCategories::where('id',$service->category_id)->first();
        
        Session::put('service_id',$service->id);
        $data['user_details']=$user=Users::where('id',Session::get('user_id'))->first();

        $date=  Session::get('date');
        $booking_hours=  Session::get('booking_hours');
        $booking_time=   Session::get('booking_time');
        $quantity=  Session::get('quantity');
        $to_time = date('h:i A',strtotime('+'.$booking_hours.'hour',strtotime($booking_time)));
        $today=date('Y-m-d');
        
        $usedCoupons = UsedCoupon::where('user_id', Session::get('user_id'))
            ->select('coupon_id')
            ->groupBy('coupon_id')
            ->havingRaw('COUNT(*) >= ANY(SELECT available_user FROM coupons WHERE coupons.id = used_coupon_details.coupon_id)')
            ->pluck('coupon_id');
        
        $data['allcoupons'] =$allcoupon= Coupons::whereNotIn('id',$usedCoupons)->whereDate('expiry_date','>=',$today)->where('available','>',0)->orderBy('id', 'DESC')->get();

        if($service->service_type){
            $totalBookValue=($service->price*$booking_hours-($service->price*$booking_hours*$service->discount)/100);
        }else{
            $totalBookValue=($service->price*$booking_hours-($service->price*$booking_hours*$service->discount)/100)*$quantity;
        }
          
        Session::put('totalBookValue',$totalBookValue);

        $data['company_details']=Salons::first();
        $data['service_image']=$service_image;
        $data['services']=$service;
        $data['settings']=$settings;
        if($booking_hours==16){
            $booking_hours="Whole Day";
        }
        
        $data['booking_time']=$booking_time;
        $data['date']=$date;
        $data['booking_hours']=$booking_hours;
        $data['quantity']=$quantity;
        $data['to_time']=$to_time;
        $data['totalBookValue']=$totalBookValue;
        
        return view('web.bookings.checkout',$data);
    }
    
    function place_booking(Request $request)
    {
        $date=  Session::get('date');
        $booking_hours=  Session::get('booking_hours');
        $booking_time=   Session::get('booking_time');
        $quantity=  Session::get('quantity');
        $service_id=  Session::get('service_id');
        $user_id=Session::get('user_id');
        $coupon_discount=Session::get('coupon_discount');
        $settings = GlobalSettings::first();
        $service = Services::find($service_id);
          
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
          
        if ($service == null) {
            return response()->json(['status' => false, 'message' => "Service doesn't exists!"]);
        }
        
        if(Session::get('user_type')){
            $service = Services::select(['id',$title,'thumbnail','category_id','service_type','salon_id','slug','service_number','service_time',$about,$rules,'qauntity','foreiner_price as price','foreiner_price_per_day as price_per_day','slug','foreiner_discount as discount'])->where('status',1)->where('id',$service_id)->with(['images','salon','salon.images','salon.gallery'])->first();
        }else{
            $service = Services::select(['id',$title,'service_number','category_id','service_type','salon_id','slug','thumbnail','service_time',$about,$rules,'qauntity','price','price_per_day','discount','slug'])->where('status',1)->where('id',$service_id)->with(['images','salon','salon.images','salon.gallery'])->first(); 
        } 

        $user = Users::find($user_id);
        if ($user == null) {
            return response()->json(['status' => false, 'message' => "User doesn't exists!"]);
        }

        $bookingsCount = Bookings::where('user_id', $user_id)
            ->whereIn('status', [Constants::orderPlacedPending, Constants::orderAccepted])
            ->count();
        if ($bookingsCount >= $settings->max_order_at_once) {
            return response()->json(['status' => false, 'message' => "Maximum at a time booking limit reached!"]);
        }

        $salon = Salons::find($request->salon_id);
        
        $service_amount=number_format($service->price-($service->price*$service->discount)/100,2);
        if($service->service_type){
            $payable_amount=($service->price*Session::get('booking_hours')-($service->price*Session::get('booking_hours')*$service->discount)/100);
        }else{
            $payable_amount=($service->price*Session::get('booking_hours')-($service->price*Session::get('booking_hours')*$service->discount)/100)*$quantity;
        }
        $payable_amount=$payable_amount-$coupon_discount;

        if ($user->wallet < $payable_amount) {
            return GlobalFunction::sendSimpleResponse(false, 'Insufficient balance in IBP Account');
        }
        
        $discount_amount=$service->price*$service->discount/100;
        
        $taxes=Taxes::first();
            
        $taxes['tax_amount']=(float)$taxes->value;
        
        $service_details['service_amount']=(float)$service_amount;
        $service_details['discount_amount']=(float)$discount_amount+$coupon_discount;
        $service_details['subtotal']=(float)$payable_amount;
        
        $service_details['total_tax_amount']=(float)$taxes->value;
        $service_details['payable_amount']=(float)$payable_amount;
        
        $service_details['taxes']=array($taxes);
        $service_details['services']=array($service);
        
        $coupon_id =Session::get('coupon_id');
        
        $booking = new Bookings();
        $booking->booking_id = GlobalFunction::generateBookingId();
        $booking->completion_otp = rand(1000, 9999);
        $booking->user_id = $user_id;
        $booking->salon_id = 1;
        
        if($coupon_id){
            $couponDetails=Coupons::where('id',$coupon_id)->first();
            $couponDetails->used_coupon_cout=$couponDetails->used_coupon_cout+1;
            $couponDetails->available=$couponDetails->available-1;
            $couponDetails->save();
            
            $booking->is_coupon_applied = 1;
            $booking->coupon_title = $couponDetails['heading'];
            // add coupon to used coupon
            $discounts = explode(',', $user->coupons_used);
            array_push($discounts, $coupon_id);
            $user->coupons_used = implode(',', $discounts);
            $service_details['coupon_apply']=1;
            $service_details['coupon']=$couponDetails;
            
            $used_coupon =new UsedCoupon();
            $used_coupon->user_id=$user_id;
            $used_coupon->coupon_id=$coupon_id;
            $used_coupon->coupon_details=$couponDetails;
            $used_coupon->save();
        }
        
        $booking->payment_method="wallet";
        $booking->service_id  = $service_id ;
        $booking->booking_hours =$booking_hours;
        $booking->date = date('Y-m-d',strtotime($date));
        $booking->time = date("Hi",strtotime($booking_time));
        $booking->duration = $service->service_time;
        $booking->services = json_encode($service_details);
        $booking->quantity = $quantity;
        $booking->service_amount = $service_amount;
        $booking->discount_amount = $discount_amount;
        $booking->subtotal = $payable_amount;
        $booking->total_tax_amount =  $taxes->value; //$request->total_tax_amount;
        $booking->payable_amount = $payable_amount;
        $booking->save();

        $user->wallet = $user->wallet - $payable_amount;
        $user->save();

        GlobalFunction::addUserStatementEntry(
            $user->id,
            $booking->booking_id,
            $payable_amount,
            Constants::debit,
            Constants::purchase,
            null
        );

        $booking = Bookings::where('id', $booking->id)->first();
        $type="booking";
        
        try{
            if($booking_hours==16){
                $booking_hours="Whole Day";
            }else{
                $booking_hours= $booking_hours." hr"; 
            }
             
            $user_name=ucfirst($user->first_name.' '.$user->last_name);
            $booked_on=date('D, d F Y',strtotime($booking->created_at));
            $schedule_at= date('D, d F Y',strtotime($booking->date)).'   '.date('h:i A', strtotime($booking->time)) ;//.'-'.date('h:i A',strtotime('+'.$booking->booking_hours.'hour',strtotime($booking->time)));
            $item_price= $settings->currency.number_format($service_amount,2);
            $amount= $settings->currency.number_format($payable_amount,2);
            
            $check_device = Device::where('user_id', $user->id)->first();
            if(!empty($check_device->language)){
                $act_lang = $check_device->language;
            }else{
                $act_lang = '1';
            }
            
            $email_template = EmailTemplate::find(1);
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
             
            $content=str_replace(["{user}","{Booking_id}","{booked_on}","{item_name}","{item_price}","{quantity}","{duration}","{created_at}","{code}","{amount}","{tax}"],
            [$user_name,$booking->booking_id,$booked_on,$service->title,$item_price,$quantity,$booking_hours,$schedule_at,$booking->completion_otp,$amount,$taxes->value],$content);
            
            $details=[         
                "subject"=>$subject ,
                "message"=>$content,
                "to"=>$user->email,
            ];
            send_email($details);
          
            $superAdmin=Admin::where('user_type',1)->first();
            $adminbookingsuccesemail=AdminEmailTemplate::find(1);
            $admin_subject=$adminbookingsuccesemail->email_subjects??'';
            $admin_subject=str_replace(['{user}'],[$user_name],$admin_subject);
            $admin_content=$adminbookingsuccesemail->email_content??''; 
            $admin_content=str_replace(["{user}","{Booking_id}","{booked_on}","{item_name}","{item_price}","{quantity}","{duration}","{created_at}","{code}","{amount}","{tax}"],
            [$user_name,$booking->booking_id,$booked_on,$service->title,$item_price,$quantity,$booking_hours,$schedule_at,$booking->completion_otp,$amount,$taxes->value],$admin_content);
            
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
        
            $notification_template = NotificationTemplate::find(2);
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
            
            $title = str_replace(["{booking_id}"],[$booking->booking_id],$title);
            $message = str_replace(["{item_name}","{booking_id}"],[$service->title,$booking->booking_id],$message);
            
            $item = new UserNotification();
            $item->user_id = $user->id;
            $item->title = $title;
            $item->description = $message;
            $item->notification_type = $type;
            $item->temp_id = '2';
            $item->item_name = $service->title;
            $item->booking_id = $booking->booking_id;
            $item->save();
            
            GlobalFunction::sendPushToUser($title, $message, $user);

            $adminNotification=AdminNotificationTemplate::find(2);
            $title=$adminNotification->notification_subjects??'';
            $title=str_replace(["{user}","{booking_id}"],[$user_name,$booking->booking_id],$title);
            $message=strip_tags($adminNotification->notification_content??'');
            $message=str_replace(["{item_name}","{user}","{booking_id}"],[$service->title,$user_name,$booking->booking_id],$message);
            dispatch(new SendAdminNotification($title,$message,$type,$user->id));
        }catch(\Exception $e){
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
        
        $date=  Session::put('date','');
        $booking_hours=  Session::get('booking_hours','');
        $booking_time=   Session::get('booking_time','');
        $quantity=  Session::get('quantity','');
        $service_id=  Session::get('service_id','');
        $coupon_id= Session::put('coupon_id','');
        Session::put('coupon_discount',0);
        return response()->json(['status' => true, 'message' => "Booking placed successfully"]);
    }
    
    public function user_booking()
    {
        $user_id=Session::get('user_id');
        $userDetails=Users::where('id',$user_id)->first();
        $data['settings'] = GlobalSettings::first();
        $data['allbookings'] = Bookings::with(['user', 'salon','service','service.category','transaction','card_transaction'])->where('user_id',$user_id)->latest()->get();
        $favourite_services=explode(',',$userDetails->favourite_services);
        $data['wishlist'] = Services::whereIn('id',$favourite_services)->orderBy('id', 'DESC')->get();
        return view('web.bookings.bookings',$data);
    }
    
    public function user_ongoing()
    {
        $user_id=Session::get('user_id');
        $userDetails=Users::where('id',$user_id)->first();
        $data['settings'] = GlobalSettings::first();
        $data['allbookings'] = Bookings::with(['user', 'salon','service','service.category','transaction','card_transaction'])->where('user_id',$user_id)->where('status',1)->orderBy('id', 'DESC')->get();
        
        $favourite_services=explode(',',$userDetails->favourite_services);
        $data['wishlist'] = Services::whereIn('id',$favourite_services)->orderBy('id', 'DESC')->get();
        
        return view('web.bookings.ongoing',$data);
    }
    
    public function user_complete()
    {
        $user_id=Session::get('user_id');
        $userDetails=Users::where('id',$user_id)->first();
        $data['settings'] = GlobalSettings::first();
        $data['allbookings'] = Bookings::with(['user', 'salon','service','service.category','transaction','card_transaction'])->where('status',2)->where('user_id',$user_id)->orderBy('id', 'DESC')->get();
        
        $favourite_services=explode(',',$userDetails->favourite_services);
        $data['wishlist'] = Services::whereIn('id',$favourite_services)->orderBy('id', 'DESC')->get();
        
        return view('web.bookings.complete',$data);
    }
    
    public function user_cancelled()
    {
        $user_id=Session::get('user_id');
        $userDetails=Users::where('id',$user_id)->first();
        $data['settings'] = GlobalSettings::first();
        $data['allbookings'] = Bookings::with(['user', 'salon','service','service.category','transaction','card_transaction'])->where('status',4)->where('user_id',$user_id)->orderBy('id', 'DESC')->get();
        
        $favourite_services=explode(',',$userDetails->favourite_services);
        $data['wishlist'] = Services::whereIn('id',$favourite_services)->orderBy('id', 'DESC')->get();
        return view('web.bookings.cancelled',$data);
    }
    
    function reschedule_booking(Request $request)
    {
        $rules = [
            'booking_id' => 'required',
            'date' => 'required',
            'time' => 'required',
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        $settings = GlobalSettings::first();
        $taxes = Taxes::first();
        $user_id = Session::get('user_id');

        $booking = Bookings::where('booking_id', $request->booking_id)->with(['salon', 'user'])->first();
        $booked_on = date('D, d F Y',strtotime($booking->created_at));

        if ($booking == null) {
            return response()->json(['status' => false, 'message' => "Booking doesn't exists!"]);
        }
        $user = Users::find($user_id);
        if ($user == null) {
            return response()->json(['status' => false, 'message' => "User doesn't exists!"]);
        }
        if ($booking->user_id != $user_id) {
            return response()->json(['status' => false, 'message' => "This booking doesn't belong to this user"]);
        }
        
        $booking->date = date('Y-m-d',strtotime($request->date));
        $booking->time = date("Hi",strtotime($request->time));
        $booking->status = Constants::orderAccepted;
        $booking->save();
        
        try{
            if($booking->booking_hours==16){
                $booking_hours="Whole Day";
            }else{
                $booking_hours= $booking->booking_hours." hr"; 
            }
               
            $service = Services::find($booking->service_id);
            $user_name=ucfirst($user->first_name.' '.$user->last_name);
            $booked_on=date('D, d F Y',strtotime($booking->created_at));
            $schedule_at= date('D, d F Y',strtotime($booking->date)).'   '.date('h:i A', strtotime($booking->time));
            $item_price= $settings->currency.number_format($booking->service_amount,2);
            $amount= $settings->currency.number_format($booking->payable_amount,2);
            
            $check_device = Device::where('user_id', $user->id)->first();
            if(!empty($check_device->language)){
                $act_lang = $check_device->language;
            }else{
                $act_lang = '1';
            }
            
            $email_template = EmailTemplate::find(6);
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
            
            $content = str_replace(["{user}","{Booking_id}","{booked_on}","{item_name}","{item_price}","{quantity}","{duration}","{created_at}","{code}","{amount}","{tax}"],
            [$user_name,$booking->booking_id,$booked_on,$service->title,$item_price,$booking->quantity,$booking_hours,$schedule_at,$booking->completion_otp,$amount,$taxes->value],$content);
            
            $details=[         
                "subject"=>$subject ,
                "message"=>$content,
                "to"=>$user->email,
            ];
            send_email($details);
          
            $superAdmin=Admin::where('user_type',1)->first();
            $adminbookingsuccesemail=AdminEmailTemplate::find(6);
            $admin_subject=$adminbookingsuccesemail->email_subjects??'';
            $admin_subject=str_replace(['{user}'],[$user_name],$admin_subject);
            $admin_content=$adminbookingsuccesemail->email_content??''; 
            $admin_content=str_replace(["{user}","{Booking_id}","{booked_on}","{item_name}","{item_price}","{quantity}","{duration}","{created_at}","{code}","{amount}","{tax}"],
            [$user_name,$booking->booking_id,$booked_on,$service->title,$item_price,$booking->quantity,$booking_hours,$schedule_at,$booking->completion_otp,$amount,$taxes->value],$admin_content);
            
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
          
            $type="booking";
            
            $notification_template = NotificationTemplate::find(5);
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
            
            $title = str_replace(["{booking_id}"],[$booking->booking_id],$title);
            $message = str_replace(["{booking_id}"],[$booking->booking_id],$message);
            
            $item = new UserNotification();
            $item->user_id = $booking->user_id;
            $item->title = $title;
            $item->description = $message;
            $item->notification_type = $type;
            $item->temp_id = '5';
            $item->booking_id = $booking->booking_id;
            $item->save();
            
            GlobalFunction::sendPushToUser($title, $message, $user);
  
            $adminNotification=AdminNotificationTemplate::find(4);
            $title=$adminNotification->notification_subjects??'';
            $title=str_replace(["{user}","{booking_id}"],[$user_name,$booking->booking_id],$title);
            $message=strip_tags($adminNotification->notification_content??'');
            $message=str_replace(["{booking_id}","{user}",],[$booking->booking_id,$user_name,],$message);
            dispatch(new SendAdminNotification($title,$message,$type,$booking->user_id));
        }catch(\Exception $e){
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
        return GlobalFunction::sendDataResponse(true, 'Booking rescheduled successfully!', $booking);
    }
    
    function cancel_booking(Request $request)
    {
        $rules = [
            'booking_id' => 'required',
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        $settings = GlobalSettings::first();
        $taxes = Taxes::first();
        
        $user_id=Session::get('user_id');
        
        $booking = Bookings::where('booking_id', $request->booking_id)->with(['salon', 'user'])->first();
        
        $booked_on = date('D, d F Y',strtotime($booking->created_at));
       
        if ($booking == null) {
            return response()->json(['status' => false, 'message' => "Booking doesn't exists!"]);
        }
        $user = Users::find($user_id);
        if ($user == null) {
            return response()->json(['status' => false, 'message' => "User doesn't exists!"]);
        }
        if ($booking->user_id != $user_id) {
            return response()->json(['status' => false, 'message' => "This booking doesn't belong to this user"]);
        }
        if ($booking->status == Constants::orderCancelled || $booking->status == Constants::orderDeclined || $booking->status == Constants::orderCompleted) {
            return response()->json(['status' => false, 'message' => "This booking is not eligible to be cancelled!"]);
        }
        
        $service = Services::find($booking->service_id);
        if($user->user_type==1){
            $charge_percent=$service->foreiner_cancellation_charges;
            $cancelletion_charge  =  ($booking->payable_amount*$service->foreiner_cancellation_charges)/100;
        }else{
            $cancelletion_charge  = ($booking->payable_amount*$service->local_cancellation_charges)/100; 
            $charge_percent=$service->local_cancellation_charges;
        }
        $payable_amount = $booking->payable_amount-$cancelletion_charge;
       
        $booking->status = Constants::orderCancelled;
        $booking->save();
        
        $user->wallet = $user->wallet + $payable_amount;
        $user->save();

        $summary = 'Booking Cancelled By User: ' . $booking->booking_id . ' Refund';
        GlobalFunction::addUserStatementEntry($user->id, $booking->booking_id, $payable_amount, Constants::credit, Constants::refund, $summary,$charge_percent,$cancelletion_charge,$booking->payable_amount);

        try{
            if($booking->booking_hours==16){
                $booking_hours="Whole Day";
            }else{
                $booking_hours= $booking->booking_hours." hr"; 
            }
               
            $service = Services::find($booking->service_id);
            $user_name=ucfirst($user->first_name.' '.$user->last_name);
            $booked_on=date('D, d F Y',strtotime($booking->created_at));
            $cancelled_at=date('D, d F Y');
            $schedule_at= date('D, d F Y',strtotime($booking->date)).'   '.date('h:i A', strtotime($booking->time)).'-'.date('h:i A',strtotime('+'.$booking->booking_hours.'hour',strtotime($booking->time)));
            $item_price= $settings->currency.number_format($booking->service_amount,2);
            $amount= $settings->currency.number_format($booking->payable_amount,2);
            
            $cancelletion_charge=$settings->currency.number_format($cancelletion_charge,2);
            $refund_amount=$settings->currency.number_format($payable_amount,2);
            
            $check_device = Device::where('user_id', $user->id)->first();
            if(!empty($check_device->language)){
                $act_lang = $check_device->language;
            }else{
                $act_lang = '1';
            }
            
            $email_template = EmailTemplate::find(5);
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
             
            $content = str_replace(["{user}","{Booking_id}","{booked_on}","{item_name}","{item_price}","{quantity}","{duration}","{created_at}","{code}","{amount}","{tax}","{charge_amount}","{refund_amount}","{cancelled_at}"],
            [$user_name,$booking->booking_id,$booked_on,$service->title,$item_price,$booking->quantity,$booking_hours,$schedule_at,$booking->completion_otp,$amount,$taxes->value,$cancelletion_charge,$refund_amount,$cancelled_at],$content);

            $details=[         
                "subject"=>$subject ,
                "message"=>$content,
                "to"=>$user->email,
            ];
            send_email($details);
          
            $superAdmin=Admin::where('user_type',1)->first();
            $adminbookingsuccesemail=AdminEmailTemplate::find(5);
            $admin_subject=$adminbookingsuccesemail->email_subjects??'';
            $admin_subject=str_replace(['{user}'],[$user_name],$admin_subject);
            $admin_content=$adminbookingsuccesemail->email_content??''; 
            $admin_content=str_replace(["{user}","{Booking_id}","{booked_on}","{item_name}","{item_price}","{quantity}","{duration}","{created_at}","{code}","{amount}","{tax}"],
            [$user_name,$booking->booking_id,$booked_on,$service->title,$item_price,$booking->quantity,$booking_hours,$schedule_at,$booking->completion_otp,$amount,$taxes->value],$admin_content);
            
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

            $type="booking";
            
            $notification_template = NotificationTemplate::find(4);
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
            
            $title = str_replace(["{booking_id}"],[$booking->booking_id],$title);
            $message = str_replace(["{booking_id}"],[$booking->booking_id],$message);
            
            $item = new UserNotification();
            $item->user_id = $booking->user_id;
            $item->title = $title;
            $item->description = $message;
            $item->notification_type = $type;
            $item->temp_id = '4';
            $item->booking_id = $booking->booking_id;
            $item->save();
            
            GlobalFunction::sendPushToUser($title, $message, $user);
 
            $adminNotification=AdminNotificationTemplate::find(8);
            $title=$adminNotification->notification_subjects??'';
            $title=str_replace(["{user}"],[$user_name],$title);
            $message=strip_tags($adminNotification->notification_content??'');
            $message=str_replace(["{booking_id}","{user}",],[$booking->booking_id,$user_name,],$message);
            dispatch(new SendAdminNotification($title,$message,$type,$booking->user_id));
        }catch(\Exception $e){
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
        return GlobalFunction::sendDataResponse(true, 'Booking cancelled successfully!', $booking);
    }
    
    public function booking_success(Request $request)
    {
        $user_id=Session::get('user_id');
        $settings = GlobalSettings::first();
        $data['booking_details'] = Bookings::with(['user', 'salon','service','service.category','transaction','card_transaction'])->where('user_id',$user_id)->orderBy('id', 'DESC')->first();
        $data['settings']=$settings;
        return view('web.bookings.success',$data);
    }
    
    function bookings_slots(Request $request)
    {
        Session::put('booking_time',$request->booking_time);
        Session::put('quantity',$request->quantity??1);
        $bookings=['booking_time'=>$request->booking_time,'quantity'=>$request->quantity];
        return GlobalFunction::sendDataResponse(true, 'Store successfully', $bookings);
    }
    
    public function booked_slots(Request $request)
    {
        $rules = [
            'date' => 'required',
        ];
        Session::put('quantity',1);
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        $salon = Services::where('id',Session::get('service_id'))->first();
        if ($salon == null) {
            return response()->json(['status' => false, 'message' => "Service doesn't exists!"]);
        }
        $bookings = Bookings::select(['id','booking_hours','service_id','date','time','quantity','status'])->where('service_id',Session::get('service_id'))
            ->where('date', date('Y-m-d',strtotime($request->date)))
            ->whereIn('status', [Constants::orderPlacedPending, Constants::orderAccepted])
            ->get();
            
        $date = date('l', strtotime($request->date));    
        $days = array(1=>'Monday',2=>'Tuesday',3=>'Wednesday',4=>'Thursday',5=>'Friday',6=>'Saturday',7=>'Sunday');
        $key = array_search($date, $days); 
        
        Session::put('date',$request->date);
        Session::put('booking_hours',$request->booking_hours);
            
        $serviceBookingHourSlots= SalonBookingSlots::select(['id','booking_hours','salon_id as service_id','time','booking_limit'])->where('salon_id',Session::get('service_id'))->where('booking_hours',$request->booking_hours)->where('weekday',$key)->orderBy('time','ASC')->get();
        
        if(count($bookings) > 0){
            foreach($serviceBookingHourSlots as $updateslots){
                $current_time = date('H:i');
                if(date('H:i',strtotime($updateslots->time)) < $current_time){
                    $updateslots->booking_limit=0 ;
                }
                
                if($salon->service_type){
                    $bookings_slots = Bookings::select(['id','booking_hours','service_id','date','time','quantity','status'])->where('service_id',Session::get('service_id'))
                    ->where('date', date('Y-m-d',strtotime($request->date)))
                    ->where('booking_hours',$request->booking_hours)
                    ->whereIn('status', [Constants::orderPlacedPending, Constants::orderAccepted])
                    ->get();
                    
                    foreach($bookings_slots as $bookings_slot){
                        $next_time = date('H:i',strtotime($bookings_slot->time. '+'.$request->booking_hours.' hours'));
                        $prev_time = date('H:i',strtotime('-'.$request->booking_hours.' hours', strtotime($bookings_slot->time)));
                        
                        if(date('H:i',strtotime($bookings_slot->time)) <= date('H:i',strtotime($updateslots->time)) && date('H:i',strtotime($updateslots->time)) < $next_time){
                            $updateslots->booking_limit = 0;
                        }
                        if(date('H:i',strtotime($bookings_slot->time)) >= date('H:i',strtotime($updateslots->time)) && date('H:i',strtotime($updateslots->time)) > $prev_time){
                            $updateslots->booking_limit = 0;
                        }
                    }
                }else{
                    $bookings_slots = Bookings::select(['id','booking_hours','service_id','date','time','quantity','status'])->where('service_id',Session::get('service_id'))
                    ->where('date', date('Y-m-d',strtotime($request->date)))
                    ->where('booking_hours',$request->booking_hours)
                    ->whereIn('status', [Constants::orderPlacedPending, Constants::orderAccepted])
                    ->get();
                    
                    foreach($bookings_slots as $bookings_slot){
                        if($bookings_slot->booking_hours!=16){
                            $next_time = date('H:i',strtotime($bookings_slot->time. '+'.$request->booking_hours.' hours'));
                            $prev_time = date('H:i',strtotime('-'.$request->booking_hours.' hours', strtotime($bookings_slot->time)));
                        
                            if($bookings_slot->time==$updateslots->time){
                                $updateslots->booking_limit=$updateslots->booking_limit-$bookings_slot->quantity;
                            }
                            if(date('H:i',strtotime($bookings_slot->time)) < date('H:i',strtotime($updateslots->time)) && date('H:i',strtotime($updateslots->time)) < $next_time){
                                $updateslots->booking_limit=$updateslots->booking_limit-$bookings_slot->quantity;
                                if($updateslots->booking_limit<=0){
                                   $updateslots->booking_limit=0 ;
                                }
                            }
                            if(date('H:i',strtotime($bookings_slot->time)) > date('H:i',strtotime($updateslots->time)) && date('H:i',strtotime($updateslots->time)) > $prev_time){
                                $updateslots->booking_limit=$updateslots->booking_limit-$bookings_slot->quantity;
                                if($updateslots->booking_limit<=0){
                                    $updateslots->booking_limit=0 ;
                                }
                            }
                        }
                        
                        if($bookings_slot->booking_hours==16){
                            if($updateslots->booking_limit-$bookings_slot->quantity<=0){
                                $updateslots->booking_limit=0 ;
                            }else{
                                $updateslots->booking_limit=$updateslots->booking_limit-$bookings_slot->quantity;
                            }
                        }
                    };  
                }
                $updateslots->time = date('h:i A',strtotime($updateslots->time));
            }
        }else{
            foreach($serviceBookingHourSlots as $updateslots){
                $updateslots->time=date('h:i A',strtotime($updateslots->time)); 
            }
        }
         
        $bookings['slots']=$serviceBookingHourSlots;
        $serviceBookingHour= SalonBookingSlots::where('salon_id',Session::get('service_id'))->where('weekday',$key)->pluck('booking_hours');
        $booking_hours=$serviceBookingHour->sort()->unique()->toArray();
         
        $New_start_index = 0; 
        $booking_hours = array_combine(range($New_start_index,  
        count($booking_hours) + ($New_start_index-1)), 
        array_values($booking_hours));
        $bookings['booking_hours']=$booking_hours;

        return GlobalFunction::sendDataResponse(true, 'Bookings fetched successfully', $bookings);
    }
    
    public function reschedule_booked_slots(Request $request)
    {
        $rules = [
            'booking_id' => 'required',
            'date' => 'required',
        ];
        Session::put('quantity',1);
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        $booking_services = Bookings::where('booking_id',$request->booking_id)->first();
        $bookings = Bookings::where('date', date('Y-m-d',strtotime($request->date)))
            ->where('service_id',$booking_services->service_id)
            ->where('booking_hours',$booking_services->booking_hours)
            ->whereIn('status', [Constants::orderPlacedPending, Constants::orderAccepted])
            ->get();
            
        $date = date('l', strtotime($request->date));    
        $days = array(1=>'Monday',2=>'Tuesday',3=>'Wednesday',4=>'Thursday',5=>'Friday',6=>'Saturday',7=>'Sunday');
        $key = array_search($date, $days); 
        
        Session::put('date',$request->date);
        Session::put('booking_hours',$request->booking_hours);
        
        $serviceBookingHourSlots= SalonBookingSlots::where('salon_id',$booking_services->service_id)->where('booking_hours',$request->booking_hours)->where('weekday',$key)->orderBy('time','ASC')->get();

        if(count($bookings)>0){
            foreach($serviceBookingHourSlots as $updateslots){
                 
                $current_time = date('H:i');
                if(date('H:i',strtotime($updateslots->time)) < $current_time){
                    $updateslots->booking_limit=0 ;
                }
                 
                $salon = Services::where('id',$booking_services->service_id)->first();

                if($salon->service_type){
                    $bookings_slots = Bookings::select(['id','booking_hours','service_id','date','time','quantity','status'])->where('service_id',$booking_services->service_id)
                    ->where('date', date('Y-m-d',strtotime($request->date)))
                    ->where('booking_hours',$request->booking_hours)
                    ->whereIn('status', [Constants::orderPlacedPending, Constants::orderAccepted])
                    ->get();
                    foreach($bookings_slots as $bookings_slot){
                        $next_time = date('H:i',strtotime($bookings_slot->time. '+'.$request->booking_hours.' hours'));
                        $prev_time = date('H:i',strtotime('-'.$request->booking_hours.' hours', strtotime($bookings_slot->time)));
                        
                        if(date('H:i',strtotime($bookings_slot->time)) <= date('H:i',strtotime($updateslots->time)) && date('H:i',strtotime($updateslots->time)) < $next_time){
                            $updateslots->booking_limit = 0;
                        }
                        if(date('H:i',strtotime($bookings_slot->time)) >= date('H:i',strtotime($updateslots->time)) && date('H:i',strtotime($updateslots->time)) > $prev_time){
                            $updateslots->booking_limit = 0;
                        }
                    }
                }else{
                     $bookings_slots = Bookings::select(['id','booking_hours','service_id','date','time','quantity','status'])->where('service_id',$booking_services->service_id)
                    ->where('date', date('Y-m-d',strtotime($request->date)))
                    ->where('booking_hours',$request->booking_hours)
                    ->whereIn('status', [Constants::orderPlacedPending, Constants::orderAccepted])
                    ->get();
                    
                    foreach($bookings_slots as $bookings_slot){
                        if($bookings_slot->booking_hours!=16){
                            $next_time = date('H:i',strtotime($bookings_slot->time. '+'.$request->booking_hours.' hours'));
                            $prev_time = date('H:i',strtotime('-'.$request->booking_hours.' hours', strtotime($bookings_slot->time)));
                            
                            if($bookings_slot->time==$updateslots->time){
                                $updateslots->booking_limit=$updateslots->booking_limit-$bookings_slot->quantity;
                            }
                            
                            if(date('H:i',strtotime($bookings_slot->time)) < date('H:i',strtotime($updateslots->time)) && date('H:i',strtotime($updateslots->time)) < $next_time){
                                $updateslots->booking_limit=$updateslots->booking_limit-$bookings_slot->quantity;
                                if($updateslots->booking_limit<=0){
                                   $updateslots->booking_limit=0 ;
                                }
                            }
                            
                            if(date('H:i',strtotime($bookings_slot->time)) > date('H:i',strtotime($updateslots->time)) && date('H:i',strtotime($updateslots->time)) > $prev_time){
                                $updateslots->booking_limit=$updateslots->booking_limit-$bookings_slot->quantity;
                                if($updateslots->booking_limit<=0){
                                   $updateslots->booking_limit=0 ;
                                }
                            }
                        }
                        
                        if($bookings_slot->booking_hours==16){
                            if($updateslots->booking_limit-$bookings_slot->quantity<=0){
                                 $updateslots->booking_limit=0 ;
                            }else{
                            $updateslots->booking_limit=$updateslots->booking_limit-$bookings_slot->quantity;
                            }
                        }
                    }
                }
                $updateslots->time = date('h:i A',strtotime($updateslots->time));
            }
        }else{
            foreach($serviceBookingHourSlots as $updateslots){
                $updateslots->time=date('h:i A',strtotime($updateslots->time)); 
            }
        }
         
        $bookings['slots']=$serviceBookingHourSlots;
        $serviceBookingHour= SalonBookingSlots::where('salon_id',$booking_services->service_id)->where('weekday',$key)->pluck('booking_hours');
        $bookings['booking_hours']=$serviceBookingHour->sort()->unique();
        
        return GlobalFunction::sendDataResponse(true, 'Bookings fetched successfully', $bookings);
    }
}

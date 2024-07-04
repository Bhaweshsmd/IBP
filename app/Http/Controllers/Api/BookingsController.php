<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Bookings;
use App\Models\Coupons;
use App\Models\GlobalFunction;
use App\Models\Salons;
use App\Models\Users;
use App\Models\UserWalletStatements;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Constants;
use App\Models\GlobalSettings;
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
use App\Models\Services;
use App\Jobs\SendNotification;
use App\Models\SalonBookingSlots;
use App\Models\EmailTemplate;
use App\Models\Taxes;
use App\Models\Fee;
use App\Jobs\SendEmail;
use App\Models\RevenueSetting;
use App\Models\Card;
use App\Models\LoyalityPoints;
use App\Models\UsedCoupon;
use App\Models\NotificationTemplate;
use App\Models\AdminNotificationTemplate;
use App\Jobs\SendAdminNotification;
use App\Models\Admin; 
use App\Models\AdminEmailTemplate;
use App\Models\UserNotification;
use App\Models\Device;
use App\Models\Language;

class BookingsController extends Controller
{
    function place_booking(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'salon_id' => 'required',
            'service_id'=>'required',
            'date' => 'required',
            'time' => 'required',
            'duration' => 'required',
            'services' => 'required',
            'is_coupon_applied' => [Rule::in(1, 0)],
            'service_amount' => 'required',
            'discount_amount' => 'required',
            'subtotal' => 'required',
            'total_tax_amount' => 'required',
            'payable_amount' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $settings = GlobalSettings::first();
        $taxes=Taxes::first();

        $user = Users::find($request->user_id);
        if ($user == null) {
            return response()->json(['status' => false, 'message' => "User doesn't exists!"]);
        }

        $bookingsCount = Bookings::where('user_id', $user->id)
            ->whereIn('status', [Constants::orderPlacedPending, Constants::orderAccepted])
            ->count();
        if ($bookingsCount >= $settings->max_order_at_once) {
            return response()->json(['status' => false, 'message' => "Maximum at a time order limit reached!"]);
        }

        $salon = Salons::find($request->salon_id);
        
        $service = Services::find($request->service_id);
        if ($service == null) {
            return response()->json(['status' => false, 'message' => "Service doesn't exists!"]);
        }
        
        if ($salon->on_vacation == 1) {
            return response()->json(['status' => false, 'message' => "this salon is on vacation!"]);
        }
        if ($salon->status != Constants::statusSalonActive) {
            return response()->json(['status' => false, 'message' => "this salon is not active!"]);
        }

        if ($user->wallet < $request->payable_amount) {
            return GlobalFunction::sendSimpleResponse(false, 'Insufficient balance in wallet');
        }

        $booking = new Bookings();
        $booking->booking_id = GlobalFunction::generateBookingId();
        $booking->completion_otp = rand(1000, 9999);
        $booking->user_id = $request->user_id;
        $booking->salon_id = $request->salon_id;
        $booking->service_id  = $request->service_id ;
        $booking->booking_hours =$request->booking_hours;
        $booking->date = $request->date;
        $booking->payment_method="wallet";
        $booking->time =$request->time;
        $booking->duration = $request->duration;
        $booking->services = $request->services;
        $booking->is_coupon_applied = $request->is_coupon_applied;
        $booking->quantity = $request->quantity;
        $booking->service_amount = $request->service_amount;
        $booking->discount_amount = $request->discount_amount;
        $booking->subtotal = $request->subtotal;
        $booking->total_tax_amount = $request->total_tax_amount;
        $booking->payable_amount = $request->payable_amount;

        if ($request->is_coupon_applied == 1) {
            $couponDetails=Coupons::where('id',$request->coupon_id)->first();
            $couponDetails->used_coupon_cout=$couponDetails->used_coupon_cout+1;
            $couponDetails->available=$couponDetails->available-1;
            $couponDetails->save();
            
            $booking->coupon_title = $request->coupon_title;

            $discounts = explode(',', $user->coupons_used);
            array_push($discounts, $request->coupon_id);
            $user->coupons_used = implode(',', $discounts);
            
            $used_coupon =new UsedCoupon();
            $used_coupon->user_id=$request->user_id;
            $used_coupon->coupon_id=$request->coupon_id;
            $used_coupon->coupon_details=$couponDetails;
            $used_coupon->save();
        }

        $booking->save();

        $user->wallet = $user->wallet - $request->payable_amount;
        $user->save();

        GlobalFunction::addUserStatementEntry(
            $user->id,
            $booking->booking_id,
            $request->payable_amount,
            Constants::debit,
            Constants::purchase,
            null
        );

        $booking = Bookings::where('id', $booking->id)->first();
        
        try{
            $booking_hours= $request->booking_hours;
            if($booking_hours==16){
                $booking_hours="Whole Day";
            }else{
                $booking_hours= $booking_hours." hr"; 
            }     
             
            $user_name=ucfirst($user->first_name.' '.$user->last_name);
            $booked_on=date('D, d F Y',strtotime($booking->created_at));
            $schedule_at= date('D, d F Y',strtotime($booking->date)).'   '.date('h:i A', strtotime($booking->time)).'-'.date('h:i A',strtotime('+'.$booking->booking_hours.'hour',strtotime($booking->time)));
            $item_price= $settings->currency.number_format($request->service_amount,2);
            $amount= $settings->currency.number_format($request->payable_amount,2);
            
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
            [$user_name,$booking->booking_id,$booked_on,$service->title,$item_price,$request->quantity,$booking_hours,$schedule_at,$booking->completion_otp,$amount,$taxes->value],$content);
            
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
            [$user_name,$booking->booking_id,$booked_on,$service->title,$item_price,$request->quantity,$booking_hours,$schedule_at,$booking->completion_otp,$amount,$taxes->value],$admin_content);
            
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
          
        return GlobalFunction::sendDataResponse(true, 'Booking placed successfully', $booking);
    }
    
    function booking_details(Request $request)
    {
        $rules = [
            'booking_id' => 'required',
            'user_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        if($request->lan_id=="pap"){
            $title=  "title_in_papiamentu as title"; 
            $rules=  "rules_in_papiamentu as rules"; 
            $about=  "about_in_papiamentu as about";
        }elseif($request->lan_id=="nl"){
            $title=  "title_in_dutch as title";  
            $rules=  "rules_in_dutch as rules"; 
            $about=  "about_in_dutch as about";
        }else{
            $title=  "title as title"; 
            $rules=  "rules as rules"; 
            $about=  "about as about";
        }

        $booking = Bookings::where('booking_id', $request->booking_id)->with(['salon', 'user', 'review', 'salon.images', 'salon.slots','service.slots','service'=>function($query) use ($title,$rules,$about,$request) {
            if($request->user_type == 1){  
                $query->select(['id','latitude','logintude','salon_id','rating','thumbnail',$title,'service_time','service_number',$about,$rules,'qauntity','foreiner_price as price','foreiner_price_per_day as price_per_day','foreiner_discount as discount','local_cancellation_charges as cancellation_charge']) ;
            }else{
                $query->select(['id','latitude','logintude','salon_id','rating','thumbnail',$title,'service_time','service_number',$about,$rules,'qauntity','price','price_per_day','discount','local_cancellation_charges as cancellation_charge']);
            }      
        }])->first();
        
        $service_details = json_decode($booking->services);
        $booking['service_type']= $service_details->services[0]->service_type;
        
        $service_image = Services::where('id', $booking->service_id)->first();
        $booking['service_image'] = url('storage/'.$service_image->thumbnail);
        $booking['invoice_url'] = route('booking.invoice', $booking->id);
        
        $booking->refund_details=UserWalletStatements::where('type',3)->where('booking_id',$request->booking_id)->first();
        
        if ($booking == null) {
            return response()->json(['status' => false, 'message' => "Booking doesn't exists!"]);
        }
        
        if ($booking->user_id != $request->user_id) {
            return response()->json(['status' => false, 'message' => "This booking doesn't belong to this user"]);
        }
        
        return GlobalFunction::sendDataResponse(true, 'details fetched successfully', $booking);
    }
    
    function cancel_booking(Request $request)
    {
        $rules = [
            'booking_id' => 'required',
            'user_id' => 'required',
        ];
         
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        $settings = GlobalSettings::first();
        $taxes=Taxes::first();

        $booking = Bookings::where('booking_id', $request->booking_id)->with(['salon', 'user'])->first();
        $booked_on=date('D, d F Y',strtotime($booking->created_at));

        if ($booking == null) {
            return response()->json(['status' => false, 'message' => "Booking doesn't exists!"]);
        }
        $user = Users::find($request->user_id);
        if ($user == null) {
            return response()->json(['status' => false, 'message' => "User doesn't exists!"]);
        }
        if ($booking->user_id != $request->user_id) {
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
            $schedule_at= date('D, d F Y',strtotime($booking->date)).'   '.date('h:i A', strtotime($booking->time));
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
            $admin_content=str_replace(["{user}","{Booking_id}","{booked_on}","{item_name}","{item_price}","{quantity}","{duration}","{created_at}","{code}","{amount}","{tax}","{charge_amount}","{refund_amount}","{cancelled_at}"],
            [$user_name,$booking->booking_id,$booked_on,$service->title,$item_price,$booking->quantity,$booking_hours,$schedule_at,$booking->completion_otp,$amount,$taxes->value,$cancelletion_charge,$refund_amount,$cancelled_at],$admin_content);
            
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
    
    function all_bookings(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'start' => 'required',
            'count' => 'required',
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

        $bookings = Bookings::with(['salon', 'salon.images', 'user'])
            ->where('user_id', $user->id)
            ->offset($request->start)
            ->limit($request->count)
            ->orderBy('id', 'DESC')
            ->get();
            
        foreach($bookings as $booking){
            $service_details = Services::where('id', $booking->service_id)->first();
            $booking['service_image'] = url('storage/'.$service_details->thumbnail);
        }

        return GlobalFunction::sendDataResponse(true, 'bookings fetched successfully', $bookings);
    }
    
    function bookings_by_date(Request $request)
    {
        $rules = [
            'service_id' => 'required',
            'date' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        $salon = Services::where('id', $request->service_id)->first();
        if ($salon == null) {
            return response()->json(['status' => false, 'message' => "Service doesn't exists!"]);
        }
        
        $bookings = Bookings::where('service_id', $request->service_id)
            ->where('date', $request->date)
            ->whereIn('status', [Constants::orderPlacedPending, Constants::orderAccepted])
            ->with(['user'])
            ->get();

        return GlobalFunction::sendDataResponse(true, 'Bookings fetched successfully', $bookings);
    }
    
    function reschedule_booking(Request $request)
    {
        $rules = [
            'booking_id' => 'required',
            'user_id' => 'required',
            'date' => 'required',
            'time' => 'required',
            'booking_hours'=>'required',
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        $settings = GlobalSettings::first();
        $taxes=Taxes::first();

        $booking = Bookings::where('booking_id', $request->booking_id)->with(['salon', 'user'])->first();
        $booked_on=date('D, d F Y',strtotime($booking->created_at));

        if ($booking == null) {
            return response()->json(['status' => false, 'message' => "Booking doesn't exists!"]);
        }
        $user = Users::find($request->user_id);
        if ($user == null) {
            return response()->json(['status' => false, 'message' => "User doesn't exists!"]);
        }
        if ($booking->user_id != $request->user_id) {
            return response()->json(['status' => false, 'message' => "This booking doesn't belong to this user"]);
        }
         
        $booking->booking_hours =$request->booking_hours; 
        $booking->date = $request->date;
        $booking->time = $request->time;
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
    
    function get_coupons(Request $request)
    {
        $rules = [
            'user_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()){
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $user = Users::find($request->user_id);
        if ($user == null) {
            return response()->json(['status' => false, 'message' => "User doesn't exists!"]);
        }
        
        $today=date('Y-m-d');

        $usedCoupons = UsedCoupon::where('user_id',$request->user_id)
            ->select('coupon_id')
            ->groupBy('coupon_id')
            ->havingRaw('COUNT(*) >= ANY(SELECT available_user FROM coupons WHERE coupons.id = used_coupon_details.coupon_id)')
            ->pluck('coupon_id');
        
        $data = Coupons::whereNotIn('id',$usedCoupons)->whereDate('expiry_date','>=',$today)->orderBy('id', 'DESC')->get();
        return GlobalFunction::sendDataResponse(true, 'coupons fetched successfully', $data);
    }
    
    function add_ratings(Request $request)
    {
        $rules = [
            'booking_id' => 'required',
            'user_id' => 'required',
            'comment' => 'required',
            'rating' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $booking = Bookings::where('booking_id', $request->booking_id)->with(['salon', 'user'])->first();
        if ($booking == null) {
            return response()->json(['status' => false, 'message' => "Booking doesn't exists!"]);
        }
        if ($booking->user_id != $request->user_id) {
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

        return GlobalFunction::sendDataResponse(true, 'Booking rated successfully!', $booking);
    }
}

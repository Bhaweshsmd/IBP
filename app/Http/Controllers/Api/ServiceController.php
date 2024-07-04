<?php

namespace App\Http\Controllers\Api;
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
use App\Models\Device;
use App\Models\Language;
use App\Models\NotificationTemplate;
use App\Models\AdminNotificationTemplate;
use App\Models\ChatMessage;
use Pusher\Pusher;

class ServiceController extends Controller
{
    function get_services(Request $request)
    { 
        $rules = [
            'user_type' => 'required',
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
        
        $total = 10;
        
        if($request->user_type == 1){
            $service = Services::select(['id','latitude','service_type','logintude',$title,'thumbnail','category_id','rating','service_number','service_time',$about,$rules,'qauntity','foreiner_price as price','foreiner_price_per_day as price_per_day','foreiner_discount as discount'])->where('status',1)->with(['images','slots','reviews'])->paginate($total);
        }else{
            $service = Services::select(['id','latitude','logintude','service_type',$title,'service_number','category_id','rating','thumbnail','service_time',$about,$rules,'qauntity','price','price_per_day','discount'])->where('status',1)->with(['images','slots','reviews'])->paginate($total); 
        }       

        if ($service == null) {
            return response()->json(['status' => false, 'message' => "Service doesn't exists!"]);
        }

        return GlobalFunction::sendDataResponse(true, 'data fetched successfully', $service);
    }
    
    function service_details(Request $request)
    {
        $rules = [
            'service_id' => 'required',
            'user_type'=>'required'
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
            $history=  "history_in_papiamentu as history";
            $included_items="included_items_in_papiamentu as included_items";
        }elseif($request->lan_id=="nl"){
            $title=  "title_in_dutch as title";  
            $rules=  "rules_in_dutch as rules"; 
            $about=  "about_in_dutch as about";
            $history=  "history_in_dutch as history";
            $included_items="included_items_in_dutch as included_items";
        }else{
            $title=  "title as title"; 
            $rules=  "rules as rules"; 
            $about=  "about as about";
            $history=  "history as history";
            $included_items="included_items as included_items";
        }
           
        if($request->user_type==1){
            $service = Services::select(['id','latitude','logintude','service_type',$included_items,'salon_id','category_id','rating','thumbnail',$title,$history,'service_time','service_number',$about,$rules,'qauntity','foreiner_price as price','foreiner_price_per_day as price_per_day','foreiner_discount as discount'])->where('id',$request->service_id)->where('status',1)->with(['images','map_images','slots','salon','salon.images','salon.gallery','reviews'])->first();
        }else{
            $service = Services::select(['id','latitude','logintude','service_type',$included_items,'salon_id','category_id','thumbnail','rating',$title,$history,'service_time','service_number',$about,$rules,'qauntity','price','price_per_day','discount'])->where('id',$request->service_id)->where('status',1)->with(['images','map_images','slots','salon','salon.images','salon.gallery','reviews'])->first(); 
        }

        if ($service == null) {
            return response()->json(['status' => false, 'message' => "Service doesn't exists!"]);
        }
        
        $count_reviews = count($service->reviews);
        if($count_reviews > 0){
            $sum_reviews = 0;
            foreach($service->reviews as $review){
                $sum_reviews += $review->rating;
            }
            $service['ratings'] = $sum_reviews/$count_reviews;
        }else{
            $service['ratings'] = 0;
        }
        
        return GlobalFunction::sendDataResponse(true, 'Service details fetched successfully', $service);
    }
    
    function favorite_services(Request $request)
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
    
        if($request->user_type==1){
            $services = Services::select(['id','latitude','logintude','salon_id','category_id','rating','thumbnail',$title,'service_time','service_number',$about,$rules,'qauntity','foreiner_price as price','foreiner_price_per_day as price_per_day','foreiner_discount as discount'])->whereIn('id', explode(',', $user->favourite_services))->with(['images','slots','salon','salon.images','salon.gallery','reviews'])->get();
        }else{
            $services = Services::select(['id','latitude','logintude','salon_id','category_id','thumbnail','rating',$title,'service_time','service_number',$about,$rules,'qauntity','price','price_per_day','discount'])->whereIn('id', explode(',', $user->favourite_services))->with(['images','slots','salon','salon.images','salon.gallery','reviews'])->get(); 
        }
        
        $salons = Salons::whereIn('id', explode(',', $user->favourite_salons))->with(['images', 'slots'])->get();
        foreach ($salons as $salon) {
            $salonCats = SalonCategories::whereIn('id', explode(',', $salon->salon_categories))->get();
            $salon->salonCats = $salonCats;
        }
        $data = array(
            'services' => $services,
            'salons' => $salons,
        );
        return GlobalFunction::sendDataResponse(true, 'Favorite services fetched successfully!', $data);
    }
    
    function add_favorite_services(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'favourite_services' => 'required',
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
        
        if ($request->has('favourite_salons')) {
            $user->favourite_salons = GlobalFunction::cleanString($request->favourite_salons);
        }
        
        if ($request->has('favourite_services')) {
            $user->favourite_services = GlobalFunction::cleanString($request->favourite_services);
        }
        
        $user->save();

        $user = Users::where('id', $user->id)->withCount('bookings')->first();
        return GlobalFunction::sendDataResponse(true, 'Favorite services added successfully', $user);
    }
    
    public function remove_favorite_services(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'service_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        $user_id = $request->user_id;

        $user = Users::find($request->user_id);
        if ($user == null) {
            return response()->json(['status' => false, 'message' => "User doesn't exists!"]);
        }
        
        if($user->favourite_services){
            $fav_service=explode(',', $user->favourite_services);
            
            if (($key = array_search($request->service_id, $fav_service)) !== false) {
                unset($fav_service[$key]);
                $user->favourite_services  =implode(',',$fav_service);
                $user->save();
            }
        }
        
        return response()->json(['status' => true, 'message' =>"Favorite services removed successfully"]);
    }
}
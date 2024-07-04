<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Banners;
use App\Models\Constants;
use App\Models\GlobalFunction;
use App\Models\GlobalSettings;
use App\Models\SalonAvailability;
use App\Models\SalonAwards;
use App\Models\SalonBankAccounts;
use App\Models\SalonBookingSlots;
use App\Models\SalonCategories;
use App\Models\SalonEarningHistory;
use App\Models\SalonGallery;
use App\Models\SalonImages;
use App\Models\SalonNotifications;
use App\Models\SalonPayoutHistory;
use App\Models\SalonReviews;
use App\Models\Salons;
use App\Models\ServiceImages;
use App\Models\Services;
use App\Models\Taxes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Bookings;
use App\Models\Fee;
use App\Models\SalonMapImage;

class HomeController extends Controller
{
    function home_data(Request $request)
    {
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
        
        $banners = Banners::orderBy('id', 'DESC')->get();
                
        $categories = SalonCategories::select(['id',$title,'parent','icon','is_deleted','sort','status','created_at','updated_at'])->where('status',1)->where('is_deleted', 0)->orderBy('sort','asc')->get();
        
        $categoriesWithServices = SalonCategories::select(['id',$title,'parent','icon','is_deleted','sort','status','created_at','updated_at'])->where('status',1)->where('is_deleted', 0)->orderBy('sort','asc')->get();
        
        $salonsTopRated = Salons::with(['images', 'slots'])
            ->where('top_rated', 1)
            ->where('on_vacation', 0)
            ->where('status', Constants::statusSalonActive)
            ->inRandomOrder()
            ->get();

        foreach ($salonsTopRated as $salon) {
            $saloncategories = SalonCategories::whereIn('id', explode(',', $salon->salon_categories))->where('status',1)->orderBy('sort','asc')->get();
            $salon->salonCats = $saloncategories;
        }
         
        if($request->user_type==1){
            $servicesTopRated = Services::select(['id','latitude','logintude','salon_id','rating','thumbnail',$title,'service_time','qauntity','service_number',$about,$rules,'qauntity','foreiner_price as price','foreiner_price_per_day as price_per_day','foreiner_discount as discount'])->where('status',1)->with(['images','slots','salon','salon.images','salon.gallery','reviews'])->orderBy('rating','desc')->latest()->get();
        }else{
            $servicesTopRated = Services::select(['id','salon_id','latitude','logintude','rating','thumbnail',$title,'service_time','qauntity','service_number',$about,$rules,'qauntity','price','price_per_day','discount'])->where('status',1)->with(['images','slots','salon','salon.images','salon.gallery','reviews'])->orderBy('rating','desc')->latest()->get(); 
        }
        
        foreach($servicesTopRated as $service){
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
        }
        
        foreach ($categories as $cat) {
            if($request->user_type==1){
                $services = Services::select(['id','latitude','logintude','salon_id','rating','thumbnail',$title,'service_time','service_number',$about,$rules,'qauntity','foreiner_price as price','foreiner_price_per_day as price_per_day','foreiner_discount as discount'])->where('category_id', $cat->id)->where('status',1)->with(['images','slots','salon','salon.images','salon.gallery','reviews'])->latest()->get();
            }else{
                $services = Services::select(['id','latitude','logintude','salon_id','rating','thumbnail',$title,'service_time','service_number',$about,$rules,'qauntity','price','price_per_day','discount'])->where('category_id', $cat->id)->where('status',1)->with(['images','slots','salon','salon.images','salon.gallery','reviews'])->latest()->get(); 
            }
            
            foreach($services as $service){
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
            }
            
            $cat->services = $services;
        }
        
        foreach ($categoriesWithServices as $cat) {
            if($request->user_type==1){
                $services = Services::select(['id','latitude','logintude','salon_id','rating','thumbnail',$title,'service_time','service_number',$about,$rules,'qauntity','foreiner_price as price','foreiner_price_per_day as price_per_day','foreiner_discount as discount'])->where('category_id', $cat->id)->where('status',1)->with(['images','slots','salon','salon.images','salon.gallery','reviews'])->latest()->get();
            }else{
                $services = Services::select(['id','latitude','logintude','salon_id','rating','thumbnail',$title,'service_time','service_number',$about,$rules,'qauntity','price','price_per_day','discount'])->where('category_id', $cat->id)->where('status',1)->with(['images','slots','salon','salon.images','salon.gallery','reviews'])->latest()->get(); 
            }
            
            foreach($services as $service){
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
            }
            
            $cat->services = $services;
        }
        
        $data = array(
            "banners" => $banners,
            "categories" => $categories,
            "topRatedSalons" => $salonsTopRated,
            "categoriesWithService" => $categoriesWithServices,
            'servicesTopRated' => $servicesTopRated,
        );

        return GlobalFunction::sendDataResponse(true, 'Data fetched successfully', $data);
    }
    
    function services_by_category(Request $request)
    {
        $rules = [
            'category_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        $cat = SalonCategories::find($request->category_id);
        if ($cat == null) {
            return response()->json(['status' => false, 'message' => "Category doesn't exists!"]);
        }
        
        $topRatedSalons = Salons::whereRaw("find_in_set($request->category_id , salon_categories)")
            ->where('top_rated', 1)
            ->where('on_vacation', 0)
            ->where('status', Constants::statusSalonActive)
            ->with(['images', 'slots'])
            ->inRandomOrder()
            ->limit(10)
            ->get();
         
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
            $services= Services::select(['id','latitude','logintude','salon_id','thumbnail','category_id','rating',$title,'service_time','service_number',$about,$rules,'qauntity','foreiner_price as price','foreiner_price_per_day as price_per_day','foreiner_discount as discount'])->where('status',1)->where('category_id', $request->category_id)->inRandomOrder()->with(['images','slots','salon','salon.images','salon.gallery','reviews'])->limit(20)->get();
        }else{
            $services = Services::select(['id','latitude','logintude','salon_id','thumbnail','rating','category_id',$title,'service_time','service_number',$about,$rules,'qauntity','price','price_per_day','discount'])->where('status',1)->where('category_id', $request->category_id)->inRandomOrder()->with(['images','slots','salon','salon.images','salon.gallery','reviews'])->limit(20)->get(); 
        }    

        $data = array(
            "topRatedSalons" => $topRatedSalons,
            "services" => $services,
        );
        
        return GlobalFunction::sendDataResponse(true, 'Services fetched successfully', $data);
    }
    
    function search_services(Request $request)
    {
        $rules = [
            'start' => 'required',
            'count' => 'required',
            'category_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $result =  Services::with(['images', 'salon'])
            ->Where('title', 'LIKE', "%{$request->keyword}%")
            ->where('category_id', $request->category_id)
            ->where('status', Constants::statusServiceOn)
            ->whereHas('salon', function ($query) {
                $query->where('on_vacation', 0)
                    ->where('status', Constants::statusSalonActive);
            })
            ->offset($request->start)
            ->limit($request->count)
            ->get();

        return GlobalFunction::sendDataResponse(true, 'Services fetched successfully', $result);
    }
    
    function search_categories(Request $request)
    {
        $rules = [
            'start' => 'required',
            'count' => 'required',
            'category_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $topRatedSalons = Salons::where('top_rated', 1)
            ->where('on_vacation', 0)
            ->where('status', Constants::statusSalonActive)
            ->Where('salon_name', 'LIKE', "%{$request->keyword}%")
            ->with(['images', 'slots'])
            ->whereRaw("find_in_set($request->category_id , salon_categories)")
            ->offset($request->start)
            ->limit($request->count)
            ->get();

        foreach ($topRatedSalons as $salon) {
            $categories = SalonCategories::whereIn('id', explode(',', $salon->salon_categories))->orderBy('sort','asc')->get();
            $salon->salonCats = $categories;
        }

        return GlobalFunction::sendDataResponse(true, 'Categories fetched successfully', $topRatedSalons);
    }
    
    function search_by_coordinates(Request $request)
    {
        $rules = [
            'lat' => 'required',
            'long' => 'required',
            'km' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $salons = Salons::with(['images', 'slots'])
            ->where('on_vacation', 0)
            ->where('status', Constants::statusSalonActive)
            ->get();

        if ($request->has('category_id')) {
            $salons = Salons::with(['images', 'slots'])
                ->whereRaw("find_in_set($request->category_id , salon_categories)")
                ->where('on_vacation', 0)
                ->where('status', Constants::statusSalonActive)
                ->get();
        }

        $salonData = [];
        foreach ($salons as $salon) {
            $distance = GlobalFunction::point2point_distance($request->lat, $request->long, $salon->salon_lat, $salon->salon_long, "K", $request->km);

            $categories = SalonCategories::whereIn('id', explode(',', $salon->salon_categories))->orderBy('sort','asc')->get();
            $salon->salonCats = $categories;

            if ($distance) {
                array_push($salonData, $salon);
            }
        }

        return GlobalFunction::sendDataResponse(true, 'Data fetched successfully', $salonData);
    }
    
    function filter_services(Request $request)
    {
        $rules = [
            'start' => 'required',
            'count' => 'required',
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

        if($request->user_type==1){
            $result= Services::select(['id','salon_id','latitude','logintude','thumbnail','category_id','rating',$title,'service_time','service_number',$about,$rules,'qauntity','foreiner_price as price','foreiner_price_per_day as price_per_day','foreiner_discount as discount'])
                ->where('status',1)
                ->Where('title', 'LIKE', "%{$request->keyword}%")
                ->inRandomOrder()
                ->with(['images','slots','salon','salon.images','salon.gallery','reviews'])
                ->offset($request->start)
                ->limit($request->count)
                ->get();
        }else{
            $result = Services::select(['id','salon_id','latitude','logintude','thumbnail','rating','category_id',$title,'service_time','service_number',$about,$rules,'qauntity','price','price_per_day','discount'])
                ->where('status',1)
                ->Where('title', 'LIKE', "%{$request->keyword}%")
                ->inRandomOrder()
                ->with(['images','slots','salon','salon.images','salon.gallery','reviews'])
                ->offset($request->start)
                ->limit($request->count)
                ->get(); 
        } 

        if ($request->has('category_id')) {
            if($request->user_type==1){
                $result= Services::select(['id','salon_id','latitude','logintude','thumbnail','category_id','rating',$title,'service_time','service_number',$about,$rules,'qauntity','foreiner_price as price','foreiner_price_per_day as price_per_day','foreiner_discount as discount'])
                    ->where('status',1)
                    ->Where('title', 'LIKE', "%{$request->keyword}%")
                    ->where('category_id', $request->category_id)
                    ->inRandomOrder()
                    ->with(['images','slots','salon','salon.images','salon.gallery','reviews'])
                    ->offset($request->start)
                    ->limit($request->count)
                    ->get();
            }else{
                $result = Services::select(['id','latitude','logintude','salon_id','thumbnail','rating','category_id',$title,'service_time','service_number',$about,$rules,'qauntity','price','price_per_day','discount'])
                    ->where('status',1)
                    ->Where('title', 'LIKE', "%{$request->keyword}%")
                    ->where('category_id', $request->category_id)
                    ->inRandomOrder()
                    ->with(['images','slots','salon','salon.images','salon.gallery','reviews'])
                    ->offset($request->start)
                    ->limit($request->count)
                    ->get(); 
            }
        }

        return GlobalFunction::sendDataResponse(true, 'data fetched successfully', $result);
    }
    
    function search_platform(Request $request)
    {
        $rules = [
            'start' => 'required',
            'count' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $result =  Salons::with(['images', 'slots'])
            ->Where('salon_name', 'LIKE', "%{$request->keyword}%")
            ->where('on_vacation', 0)
            ->where('status', Constants::statusSalonActive)
            ->offset($request->start)
            ->limit($request->count)
            ->get();

        if ($request->has('category_id')) {
            $result =  Salons::with(['images', 'slots'])
                ->Where('salon_name', 'LIKE', "%{$request->keyword}%")
                ->where('on_vacation', 0)
                ->where('status', Constants::statusSalonActive)
                ->whereRaw("find_in_set($request->category_id , salon_categories)")
                ->offset($request->start)
                ->limit($request->count)
                ->get();
        }

        foreach ($result as $salon) {
            $categories = SalonCategories::whereIn('id', explode(',', $salon->salon_categories))->orderBy('sort','asc')->get();
            $salon->salonCats = $categories;
        }

        return GlobalFunction::sendDataResponse(true, 'Pltform fetched successfully', $result);
    }
    
    function platform_details(Request $request)
    {
        $rules = [
            'platform_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $salon = Salons::with(['gallery', 'awards', 'images','area_map', 'slots'])
            ->withCount('reviews')
            ->where('id', $request->platform_id)
            ->first();
            
        if ($salon == null) {
            return response()->json(['status' => false, 'message' => "Salon doesn't exists!"]);
        }
        
        $categories = SalonCategories::whereIn('id', explode(',', $salon->salon_categories))->orderBy('sort','asc')->get();
        foreach ($categories as $category) {
            $services = Services::where('category_id', $category->id)
                ->where('salon_id', $salon->id)
                ->where('status', Constants::statusServiceOn)
                ->with(['images', 'category'])
                ->orderBy('id', 'DESC')
                ->get();
            $category->services = $services;
        }
        
        $reviews = SalonReviews::where('salon_id', $salon->id)->where('status', '1')->orderBy('id', 'DESC')->with('user')->limit(5)->get();
        $salon->categories = $categories;
        $salon->reviews = $reviews;
        
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
            $services = Services::select(['id','latitude','logintude','salon_id','rating','thumbnail',$title,'service_time','service_number',$about,$rules,'qauntity','foreiner_price as price','foreiner_price_per_day as price_per_day','foreiner_discount as discount'])->where('status',1)->with(['images','slots','salon','salon.images','salon.gallery','reviews'])->latest()->get();
        }else{
            $services = Services::select(['id','latitude','logintude','salon_id','rating','thumbnail',$title,'service_time','service_number',$about,$rules,'qauntity','price','price_per_day','discount'])->where('status',1)->with(['images','slots','salon','salon.images','salon.gallery','reviews'])->latest()->get(); 
        }
        
        foreach($services as $service){
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
        }
            
        $salon->services= $services;
        return GlobalFunction::sendDataResponse(true, 'Platform details fetched successfully', $salon);
    }
    
    function service_reviews(Request $request)
    {
        $rules = [
            'start' => 'required',
            'count' => 'required',
            'service_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        $result = SalonReviews::with(['user', 'salon'])
            ->Where('salon_id', $request->service_id)
            ->whereHas('user')
            ->whereHas('service')
            ->where('status', '1')
            ->orderBy('id', 'DESC')
            ->offset($request->start)
            ->limit($request->count)
            ->get();
        
        return GlobalFunction::sendDataResponse(true, 'Platform Reviews fetched successfully', $result);
    }
    
    function company_reviews(Request $request)
    {
        $rules = [
            'start' => 'required',
            'count' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        $total_avg= SalonReviews::where('status', '1')->avg('rating');
        $total_rating= SalonReviews::where('status', '1')->count();
        
        $result =  SalonReviews::with(['user','service','salon'])
            ->whereHas('user')
            ->whereHas('service')
            ->where('status', '1')
            ->orderBy('id', 'DESC')
            ->offset($request->start)
            ->limit($request->count)
            ->get();
       
        $total_avg=number_format($total_avg??0,2);
        $total_rating=$total_rating??0;
       
        $data=[
            'result'=>$result,
            'total_avg'=>$total_avg,
            'total_rating'=>$total_rating
        ];
       
        return GlobalFunction::sendDataResponse(true, 'Company Reviews fetched successfully', $data);
    }
    
    function platform_categories(Request $request)
    { 
        if($request->lan_id=="pap"){
            $title=  "title_in_papiamentu as title"; 
        }elseif($request->lan_id=="nl"){
            $title=  "title_in_dutch as title";  
        }else{
            $title=  "title as title"; 
        }
        
        $salonCats = SalonCategories::select(['id',$title,'parent','status','icon','is_deleted','sort','created_at','updated_at'])->where('status',1)->where('is_deleted', 0)->orderBy('sort','asc')->get();
        return response()->json(['status' => true, 'message' => 'Platform Categories fetched successfully!', 'data' => $salonCats]);
    }
    
    function get_settings(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'lan_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        $settings = GlobalSettings::first();
        $categories = SalonCategories::where('is_deleted', 0)->orderBy('sort','asc')->get();
        $taxes = Taxes::where('status', 1)->get();
        $fee=Fee::get();
        $settings->taxes = $taxes;
        $settings->categories = $categories;
        $settings->fee=$fee;
        return GlobalFunction::sendDataResponse(true, 'fetched successfully', $settings);
    }
    
}
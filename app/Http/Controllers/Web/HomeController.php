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

class HomeController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService, Auth $auth)
    {  
        $this->middleware('CheckAccountVerification',['except' => ['accountVerification', 'accountVerificationOtp','login','logout']]);
        $this->twilioService = $twilioService;
    }
    
    public function index(Request $request)
    {
        $settings = GlobalSettings::first();
        
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
            $service = Services::select(['id','latitude','logintude',$title,'thumbnail','slug','category_id','rating','service_number','service_time',$about,$rules,'qauntity','foreiner_price as price','foreiner_price_per_day as price_per_day','foreiner_discount as discount'])->where('status',1)->with(['images','slots','reviews','category'])->orderBy('rating','DESC')->get();
        }else{
            $service = Services::select(['id','latitude','logintude',$title,'service_number','slug','category_id','rating','thumbnail','service_time',$about,$rules,'qauntity','price','price_per_day','discount'])->where('status',1)->with(['images','slots','reviews','category'])->orderBy('rating','DESC')->get(); 
        } 
          
        $data['categories']=SalonCategories::select(['id',$title,'slug','parent','is_deleted','status','sort','web_icon','icon'])->where('status',1)->where('is_deleted',0)->where('parent',null)->orderBy('sort','asc')->get();
        
        $data['featured_facilities'] = $service;
        $data['settings'] = $settings;
        $data['banner'] = WebBanner::orderBy('id', 'desc')->first();
        
        $data['lang'] = session()->get('locale');
        $data['faqs'] = Faqs::where('status', '1')->limit(5)->get();
        
        return view('web.home.index',$data);
    }
    
    public function service_map(Request $request)
    {
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
            $data = Services::select(['id','latitude','logintude',$title,'thumbnail','slug','category_id','rating','service_number','service_time',$about,$rules,'qauntity','foreiner_price as price','foreiner_price_per_day as price_per_day','foreiner_discount as discount'])->where('latitude','!=','')->where('latitude','!=',null)->where('status',1)->get();
        }else{
            $data = Services::select(['id','latitude','logintude',$title,'service_number','slug','category_id','rating','thumbnail','service_time',$about,$rules,'qauntity','price','price_per_day','discount'])->where('latitude','!=','')->where('latitude','!=',null)->where('status',1)->get(); 
        } 
    
        return view('web.home.service_map',compact("data"));
    }
    
    public function contact_us(Request $request)
    {
        $data['company_details'] = Salons::first();
        $data['settings'] = GlobalSettings::first();
        return view('web.home.contact-us', $data);
    }
    
    public function submit_contact_us(Request $request)
    {
        $rules = [
            'firstname' => 'required',
            'lastname'  => 'required',
            'email'     => 'required',
            'phone'     => 'required',
            'comments'  => 'required',
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return back()->with(['contact_fail_message' => $msg]);
        }
        
        $recaptcha = $request->input('g-recaptcha-response');

        if (is_null($recaptcha)) {
            return back()->with(['contact_fail_message' => 'Please complete the recaptcha again to proceed']);
        }
        
        $settings = GlobalSettings::first();
        
        $admin_temp = AdminEmailTemplate::find(14);
        $admin_subject = $admin_temp->email_subjects;
        $admin_content = $admin_temp->email_content; 
        $admin_content = str_replace(["{first_name}","{last_name}","{email}","{mobile_number}","{message}"], [$request->firstname,$request->lastname,$request->email,$request->phone,$request->comments],$admin_content);

        $admin_details = [         
            "subject" => $admin_subject ,
            "message" => $admin_content,
            "to" => $settings->contact_email,
        ];
        send_email($admin_details);
        
        $user_temp = EmailTemplate::find(14);
        $user_subject = $user_temp->email_subjects;
        $user_content = $user_temp->email_content; 
        
        $user_details = [         
            "subject" => $user_subject ,
            "message" => $user_content,
            "to" => $request->email,
        ];
        send_email($user_details);
        return back()->with(['contact_success_message' => 'Message Sent Successfully']);
    }
    
    public function categories(Request $request)
    {
        $settings = GlobalSettings::first();
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
            $service = Services::select(['id','latitude','logintude',$title,'thumbnail','category_id','slug','rating','service_number','service_time',$about,$rules,'qauntity','foreiner_price as price','foreiner_price_per_day as price_per_day','foreiner_discount as discount'])->where('status',1)->with(['images','slots','reviews','category'])->get();
        }else{
            $service = Services::select(['id','latitude','logintude',$title,'service_number','category_id','slug','rating','thumbnail','service_time',$about,$rules,'qauntity','price','price_per_day','discount'])->where('status',1)->with(['images','slots','reviews','category'])->get(); 
        } 
        
        $data['categories']=SalonCategories::select(['id',$title,'slug','parent','is_deleted','sort','status','web_icon','icon'])->where('status',1)->where('is_deleted',0)->where('parent',null)->orderBy('sort','asc')->get();

        $data['services']=$service;
        $data['settings']=$settings;
        return view('web.home.services',$data);
    }
    
    public function items_facilities(Request $request)
    {
        $settings = GlobalSettings::first();
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
            $service = Services::select(['id','latitude','logintude',$title,'thumbnail','category_id','slug','rating','service_number','service_time',$about,$rules,'qauntity','foreiner_price as price','foreiner_price_per_day as price_per_day','foreiner_discount as discount'])->where('status',1)->with(['images','slots','reviews','category']);
        }else{
            $service = Services::select(['id','latitude','logintude',$title,'service_number','category_id','slug','rating','thumbnail','service_time',$about,$rules,'qauntity','price','price_per_day','discount'])->where('status',1)->with(['images','slots','reviews','category']); 
        }
        
        if($request->category){
            $search= $request->category;
            
            $service->where(function ($query) use ($search){
                $query->Where('category_id', $search);
            });
        }
        
        if($request->keyword){
            $keyword=$request->keyword;
            $service->where(function ($query) use ($keyword){
                $query->where('title', 'LIKE', "%{$keyword}%");
            });
        }
        $data['categories']=SalonCategories::select(['id',$title,'slug','parent','is_deleted','sort','web_icon','status','icon'])->where('status',1)->where('is_deleted',0)->where('parent',null)->orderBy('sort','asc')->get();
        $data['services']=$service->get();
        $data['settings']=$settings;
        return view('web.home.items',$data);
    }
    
    public function items_facilities_list(Request $request)
    {
        $settings = GlobalSettings::first();
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
            $service = Services::select(['id','latitude','logintude',$title,'thumbnail','slug','category_id','slug','rating','service_number','service_time',$about,$rules,'qauntity','foreiner_price as price','foreiner_price_per_day as price_per_day','foreiner_discount as discount'])->where('status',1)->with(['images','slots','reviews','category']);
        }else{
            $service = Services::select(['id','latitude','logintude',$title,'service_number','slug','category_id','slug','rating','thumbnail','service_time',$about,$rules,'qauntity','price','price_per_day','discount'])->where('status',1)->with(['images','slots','reviews','category']); 
        } 
        
        if($request->category){
            $search= $request->category;
            $service->where(function ($query) use ($search){
                $query->Where('category_id', $search);
            });
        }
        
        if($request->keyword){
            $keyword=$request->keyword;
            $service->where(function ($query) use ($keyword){
                $query->where('title', 'LIKE', "%{$keyword}%");
            });
        }
        
        $data['categories']=SalonCategories::select(['id',$title,'slug','parent','is_deleted','sort','web_icon','icon','status'])->where('status',1)->where('is_deleted',0)->where('parent',null)->orderBy('sort','asc')->get();
        $data['services']=$service->get();
        $data['settings']=$settings;
        
        return view('web.home.coaches-list',$data);
    }
    
    public function item_details(Request $request,$slug=null)
    {
        $settings = GlobalSettings::first();
        $data['user_details']=Users::where('id',Session::get('user_id'))->first();
        
        if(session()->get('locale')=="pap"){
            $title=  "title_in_papiamentu as title"; 
            $rules=  "rules_in_papiamentu as rules"; 
            $about=  "about_in_papiamentu as about";
            $history=  "history_in_papiamentu as history";
            $included_items=  "included_items_in_papiamentu as included_items";
        }elseif(session()->get('locale')=="nl"){
            $title=  "title_in_dutch as title";  
            $rules=  "rules_in_dutch as rules"; 
            $about=  "about_in_dutch as about";
            $history=  "history_in_dutch as history";
            $included_items=  "included_items_in_dutch as included_items";
        }else{
            $title=  "title as title"; 
            $rules=  "rules as rules"; 
            $about=  "about as about";
            $history=  "history as history";
            $included_items=  "included_items as included_items";
        }
        
        if(Session::get('user_type')){
            $service = Services::select(['id','latitude','service_type','logintude',$title,$history,$included_items,'thumbnail','category_id','rating','slug','service_number','service_time',$about,$rules,'qauntity','foreiner_price as price','foreiner_price_per_day as price_per_day','slug','foreiner_discount as discount'])->where('status',1)->where('slug',$slug)->with(['images','slots','reviews','reviews.user'])->first();
        }else{
            $service = Services::select(['id','latitude','service_type','logintude',$title,$history,$included_items,'service_number','category_id','rating','slug','thumbnail','service_time',$about,$rules,'qauntity','price','price_per_day','discount','slug'])->where('status',1)->where('slug',$slug)->with(['images','slots','reviews','reviews.user'])->first(); 
        } 
          
        if(Session::get('user_type')){
            $similar_service = Services::select(['id','service_type','latitude','logintude',$title,$history,$included_items,'thumbnail','category_id','rating','slug','service_number','service_time',$about,$rules,'qauntity','foreiner_price as price','foreiner_price_per_day as price_per_day','slug','foreiner_discount as discount'])->where('status',1)->where('category_id',$service->category_id)->with(['images','slots','reviews'])->get();
        }else{
            $similar_service = Services::select(['id','service_type','latitude','logintude',$title,$history,$included_items,'service_number','category_id','rating','slug','thumbnail','service_time',$about,$rules,'qauntity','price','price_per_day','discount','slug'])->where('status',1)->where('category_id',$service->category_id)->with(['images','slots','reviews'])->get(); 
        } 
          
        $serviceBookingHour= SalonBookingSlots::where('salon_id',$service->id)->pluck('booking_hours');
         
        $data['booking_hours']=$serviceBookingHour->sort()->unique();
        $data['categories']=SalonCategories::select(['id',$title,'slug','parent','is_deleted','sort','web_icon','icon','status'])->where('status',1)->where('is_deleted',0)->where('parent',null)->orderBy('sort','asc')->get();
 
        $service_image= ServiceImages::where('service_id',$service->id)->get();
        $data['category']  =SalonCategories::where('id',$service->category_id)->first();
        $data['similar_service']=$similar_service;
        $data['company_details']=Salons::first();
        $data['service_image']=$service_image;
        
        $data['total_company_review']=SalonReviews::count();
        
        $data['services']=$service;
        $data['settings']=$settings;
        return view('web.home.venue-details',$data);
    }
    
    public function company_details(Request $request)
    {
        $settings = GlobalSettings::first();
        $data['user_details']=Users::where('id',Session::get('user_id'))->first();
        
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
            $service = Services::select(['id','latitude','logintude',$title,'thumbnail','category_id','rating','slug','service_number','service_time',$about,$rules,'qauntity','foreiner_price as price','foreiner_price_per_day as price_per_day','slug','foreiner_discount as discount'])->where('status',1)->with(['images','slots','reviews','reviews.user'])->get();
        }else{
            $service = Services::select(['id','latitude','logintude',$title,'service_number','category_id','rating','slug','thumbnail','service_time',$about,$rules,'qauntity','price','price_per_day','discount','slug'])->where('status',1)->with(['images','slots','reviews','reviews.user'])->get(); 
        } 

        $data['company_details']=Salons::first();
        $data['total_review']= SalonReviews::count();
        $data['reviews']=SalonReviews::get();
        $data['images']  =SalonImages::get();
        $data['gallery']  =SalonGallery::get();
        $data['map_images']  =SalonMapImage::get();
        $data['featured_facilities']=$service;
        $data['settings']=$settings;
        
        return view('web.home.company-profile',$data);
    }
    
    public function all_blogs()
    {
        $data['lang'] = session()->get('locale');
        $data['blogs'] = Blog::where('status', '1')->paginate(10);
        return view('web.home.blog-grid',$data);
    }
    
    public function all_faqs()
    {
        $data['lang'] = session()->get('locale');
        $data['faqs'] = Faqs::where('status', '1')->get();
        return view('web.home.faq',$data);
    }
    
    public function web_pages($slug)
    {
        $check_page = Page::where('slug', $slug)->first();
        $check_blog = Blog::where('slug', $slug)->first();
        
        $data['lang'] = session()->get('locale');
        
        if(!empty($check_page)){
            $data['page'] = Page::where('slug', $slug)->first();
            return view('web.home.page',$data);
        }elseif(!empty($check_blog)){
            $data['blog'] = Blog::where('slug', $slug)->first();
            return view('web.home.blog-details',$data);
        }
    }
}
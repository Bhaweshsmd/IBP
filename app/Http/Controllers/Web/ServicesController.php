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

class ServicesController extends Controller
{
    public function favourite_services(Request $request)
    {
        $user_id=Session::get('user_id');
        $userDetails=Users::where('id',$user_id)->first();
        $data['settings'] = GlobalSettings::first();
        
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
            $service = Services::select(['id','latitude','logintude',$title,'thumbnail','category_id','rating','slug','service_number','service_time',$about,$rules,'qauntity','foreiner_price as price','foreiner_price_per_day as price_per_day','slug','foreiner_discount as discount'])->where('status',1)->whereIn('id',$favourite_services)->with(['images','slots','reviews','reviews.user'])->get();
        }else{
            $service = Services::select(['id','latitude','logintude',$title,'service_number','category_id','rating','slug','thumbnail','service_time',$about,$rules,'qauntity','price','price_per_day','discount','slug'])->where('status',1)->whereIn('id',$favourite_services)->with(['images','slots','reviews','reviews.user'])->get(); 
        } 
        
        $data['wishlist'] = $service;
        $data['userDetails']=$userDetails;
        return view('web.services.favourite',$data);
    }
    
    public function add_remove_fav_services($service_id=null)
    {
        $user_id = Session::get('user_id');
        $user = Users::find($user_id);
        if ($user == null){
            return response()->json(['status' => false, 'message' => "Please login to add as favourite"]);
        }
        
        $fav="";
        $msg="";
        $addrem="";
        
        try{
            if($user->favourite_services??1){
                
                $fav_service=explode(',', $user->favourite_services??$fav);
                
                if (($key = array_search($service_id, $fav_service)) !== false) {
                    unset($fav_service[$key]);
                    $user->favourite_services  =implode(',',$fav_service);
                    $user->save();
                    
                    $msg="item removed from you favourite service";
                    $addrem="removed";
                }else{
                    $fav_service=explode(',', $user->favourite_services);
                    $fav_services= array_push($fav_service,$service_id);
                    $user->favourite_services  =implode(',',$fav_service);
                    $user->save();
                    
                    $msg="item added from you favourite service";
                    $addrem="added";
                }
                
                return response()->json(['status' => true, 'message' =>$msg, 'addrem'=>$addrem]);
            }
        }catch(\Exception $e){
             return response()->json(['status' => false, 'message' =>$e->getMessage()]);
        }
    }
}
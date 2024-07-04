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

class EventController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService, Auth $auth)
    {  
        $this->middleware('CheckAccountVerification',['except' => ['accountVerification', 'accountVerificationOtp','login','logout']]);
        $this->twilioService = $twilioService;
    }
    
    public function index(Request $request)
    {
        $user_id=Session::get('user_id');
        $data['company_details']=Salons::first();
        $data['event_type']=EventType::where('status','Active')->orderBy('short_id','ASC')->get();
        $data['event_inquiries']=DB::table('event_inquiries')->where('user_id',$user_id)->orderBy('id','desc')->get();
        $user_id=Session::get('user_id');
        $userDetails=Users::where('id',$user_id)->first();
        $data['settings'] = GlobalSettings::first();
        $data['allbookings'] = Bookings::with(['user', 'salon','service','service.category','transaction','card_transaction'])->where('user_id',$user_id)->orderBy('id', 'DESC')->get();
        
        $favourite_services=explode(',',$userDetails->favourite_services);
        $data['wishlist'] = Services::whereIn('id',$favourite_services)->orderBy('id', 'DESC')->get();
        
        return view('web.events.index',$data);
    }
    
    public function view(Request $request)
    {
        $user_id=Session::get('user_id');
        $data['company_details']=Salons::first();
        $data['event_type']=EventType::where('status','Active')->orderBy('short_id','ASC')->get();
        $data['event_inquiries']=DB::table('event_inquiries')->where('user_id',$user_id)->orderBy('id','desc')->get();
        if(!$user_id){
           return redirect()->to('login');
        }
        
        return view('web.events.view',$data);
    }
    
    public function store(Request $request)
    {
        $rules = [
            'event_type' => 'required',
            'no_of_people' => 'required',
            'message' => 'required',
            'event_date' => 'required',
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        $settings = GlobalSettings::first();
        $user_id = Session::get('user_id');
        $userDetails = $user = Users::where('id',$user_id)->first();
        
        $data=[
            'user_id'=>$user_id,
            'event_type'=>$request->event_type,
            'no_of_people'=>$request->no_of_people,
            'message'=>$request->message,
            'event_date'=>$request->event_date.' '.date('h:i:s',strtotime($request->event_time)),
        ];
        DB::table('event_inquiries')->insert($data);
        
        try{
            $user_name= $userDetails['first_name'].' '.$userDetails['last_name'];
            $email=$userDetails['email'];
            $phone=$userDetails['formated_number'];
            $date_time=$request->event_date.' '.date('h:i: A',strtotime($request->event_time));
          
            $superAdmin=Admin::where('user_type',1)->first();    
            $admin_temp = AdminEmailTemplate::find(7);
            $admin_subject = $admin_temp->email_subjects;
            $admin_content = $admin_temp->email_content; 
            $admin_content = str_replace(["{user}","{email}","{phone}","{event_type}","{date_time}","{guest}","{message}"], [$user_name,$email,$phone,$request->event_type,$date_time,$request->no_of_people,$request->message],$admin_content);

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
            
            $check_device = Device::where('user_id', $user_id)->first();
            if(!empty($check_device->language)){
                $act_lang = $check_device->language;
            }else{
                $act_lang = '1';
            }
            
            $email_template = EmailTemplate::find(15);
            if($act_lang == '1'){
                $user_subject = $email_template->email_subjects;
                $user_content = $email_template->email_content;
            }elseif($act_lang == '2'){
                $user_subject = $email_template->email_subject_pap;
                $user_content = $email_template->email_content_pap;
            }elseif($act_lang == '3'){
                $user_subject = $email_template->email_subject_nl;
                $user_content = $email_template->email_content_nl;
            }
            
            $user_content = str_replace(["{event_type}","{date_time}","{guest}","{message}"], [$request->event_type,$date_time,$request->no_of_people,$request->message],$user_content);
    
            $user_details = [         
                "subject" => $user_subject ,
                "message" => $user_content,
                "to" => $email,
            ];
            send_email($user_details);
        
            $type="event_inquiry";
            
            $notification_template = NotificationTemplate::find(13);
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
            
            $item = new UserNotification();
            $item->user_id = $user->id;
            $item->title = $title;
            $item->description = $message;
            $item->notification_type = $type;
            $item->temp_id = '13';
            $item->save();
            
            GlobalFunction::sendPushToUser($title, $message, $user);

            $adminNotification=AdminNotificationTemplate::find(9);
            $title=$adminNotification->notification_subjects??'';
            $title=str_replace(["{user}"],[$user_name],$title);
            $message=strip_tags($adminNotification->notification_content??'');
            $message=str_replace(["{user}"],[$user_name],$message);
            dispatch(new SendAdminNotification($title,$message,$type,$user->id));
        }catch(\Exception $e){
             return response()->json(['status' => false, 'message' =>$e->getMessage()]);
        }
        return response()->json([
            'status' => true,
            'message' => 'Enquiry submitted successfully',
        ]);
    }
    
}
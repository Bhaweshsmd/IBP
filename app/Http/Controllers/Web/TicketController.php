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

class TicketController extends Controller
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
        
        $data['tickets'] =SupportTicket::where('user_id',$user_id)->get();
        $data['suppprtTicketReason']=DB::table('ticket_reasons')->where('status','Active')->get();
        return view('web.tickets.index',$data);        
    }
    
    public function create(Request $request)
    {
        $rules =[
            'subject' => 'required|string|max:191',
            'priority' => 'required|string|max:191',
            'reason' => 'required',
            'description' => 'required|string',
        ];
   
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        } 
        
        $settings = GlobalSettings::first();
        
        $user_id=Session::get('user_id');
        $user = Users::where('id',$user_id)->first();
        if(is_null($user)){
             return response()->json(['status' => false, 'message' => $msg]);
        }
        $support = SupportTicket::insert([
            'title' => 'Ticket Created By '.$user->first_name .' '.$user->last_name,
            'ticket_id'=>GlobalFunction::generateSupportTicketNumber(),
            'description' => $request->description,
            'subject' => $request->subject,
            'user_id'=>$user_id,
            'reason'=>$request->reason,
            'status' => 'open',
            'priority' => $request->priority,
            'created_at'=>date("Y-m-d H:i:s")
        ]);
        $last_inserted_id =DB::getPdo()->lastInsertId();
        SupportTicketMessage::insert([
            'support_ticket_id' => $last_inserted_id,
            'message' => $request->description,
            'type' => 'user',
            'is_read' => 0,
            'created_at'=>date("Y-m-d H:i:s")
        ]);
        
        $check_ticket = SupportTicket::where('id', $last_inserted_id)->first();
        
        $check_device = Device::where('user_id', $user->id)->first();
        if(!empty($check_device->language)){
            $act_lang = $check_device->language;
        }else{
            $act_lang = '1';
        }
        
        $email_template = EmailTemplate::find(18);
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
        
        $subject = str_replace(["{ticket_id}"], [$check_ticket->ticket_id],$subject);
        $content = str_replace(["{ticket_id}"], [$check_ticket->ticket_id],$content);
        
        $details=[         
            "subject"=>$subject ,
            "message"=>$content,
            "to"=>$user->email,
        ];
        send_email($details);
        
        $user_name = ucfirst($user->first_name.' '.$user->last_name);
        
        $superAdmin=Admin::where('user_type',1)->first();
        $adminbookingsuccesemail=AdminEmailTemplate::find(16);
        $admin_subject=$adminbookingsuccesemail->email_subjects??'';
        $admin_subject=str_replace(['{user}'],[$user_name],$admin_subject);
        $admin_content=$adminbookingsuccesemail->email_content??''; 
        $admin_content=str_replace(["{user}","{ticket_id}"], [$user_name,$check_ticket->ticket_id],$admin_content);
        
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
        
        $type = 'ticket';
        
        $notification_template = NotificationTemplate::find(11);
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
        
        $message = str_replace(["{ticket_id}"],[$check_ticket->ticket_id],$message);
        
        $item = new UserNotification();
        $item->user_id = $user->id;
        $item->title = $title;
        $item->description = $message;
        $item->notification_type = $type;
        $item->temp_id = '11';
        $item->ticket_id = $check_ticket->ticket_id;
        $item->save();
        
        GlobalFunction::sendPushToUser($title, $message, $user);
        
        $user_name=ucfirst($user->first_name.' '.$user->last_name);
        
        $adminNotification=AdminNotificationTemplate::find(7);
        $title=$adminNotification->notification_subjects??'';
        $title=str_replace(["{user}"],[$user_name],$title);
        $message=strip_tags($adminNotification->notification_content??'');
        $message=str_replace(["{user}","{ticket_id}"],[$user_name,$check_ticket->ticket_id],$message);
        dispatch(new SendAdminNotification($title,$message,$type,$user->id));
        
        return response()->json(['status' => true, 'message' => "Support Ticket Created Successfully"]);
    }     
            
    public function view(Request $request,$ticket_id)
    {   
        Session::put('ticket_id',$ticket_id);
        $data['ticket_message'] =SupportTicketMessage::where('support_ticket_id',$ticket_id)->get();
        $user_id=Session::get('user_id');
        $data['userDetails']=Users::where('id',$user_id)->first();
        
        $data['tickets'] =SupportTicket::where('user_id',$user_id)->where('id',$ticket_id)->first();
        $data['company_details']=Salons::first();
        return view('web.tickets.view',$data);  
    }      
         
    public function reply(Request $request)
    {
        $rules =[
            'message' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
             return back()->withErrors($validator)->withInput();
        } 
        $attachment=null;
        if ($request->has('attachment')) {
            $attachment= GlobalFunction::saveFileAndGivePath($request->attachment);
        }
        $ticket_id= Session::get('ticket_id');
        SupportTicketMessage::insert([
            'support_ticket_id' => $ticket_id,
            'message' => $request->message,
            'attachment'=>$attachment,
            'type' => 'user',
            'is_read' => 0,
            'created_at'=>date("Y-m-d H:i:s")
        ]);
        
        $ticket = SupportTicket::where('id', $ticket_id)->first();
        $user = Users::where('id', $ticket->user_id)->first();
        $user_name = ucfirst($user->first_name.' '.$user->last_name);
        
        $superAdmin = Admin::where('user_type',1)->first();
        $adminbookingsuccesemail=AdminEmailTemplate::find(15);
        $admin_subject=$adminbookingsuccesemail->email_subjects??'';
        $admin_subject=str_replace(['{ticket_id}'],[$check_ticket->ticket_id],$admin_subject);
        $admin_content=$adminbookingsuccesemail->email_content??''; 
        $admin_content=str_replace(["{ticket_id}", "{ticket_reply}"], [$check_ticket->ticket_id, $request->message],$admin_content);
        
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
        
        $type = 'ticket';
        $adminNotification=AdminNotificationTemplate::find(10);
        $title=$adminNotification->notification_subjects;
        $message=strip_tags($adminNotification->notification_content);
        $message=str_replace(["{ticket_id}"],[$ticket->ticket_id],$message);
        dispatch(new SendAdminNotification($title,$message,$type,$user->id));
        
        $msg="Message Send Successfully";
        return back()->withSuccess($msg)->withInput();   
    } 
}
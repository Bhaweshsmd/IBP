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
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth\SignInResult\SignInResult;
use Kreait\Firebase\Exception\FirebaseException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Session;
use Kreait\Laravel\Firebase\Facades\Firebase;
use App\Jobs\SendNotification;
use Kreait\Firebase\Contract\Firestore;
use Google\Cloud\Firestore\FirestoreClient;
use App\Models\Fee;
use Google\Cloud\Firestore\FireStoreDocument;
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
use App\Jobs\SendAdminNotification;
use App\Models\NotificationTemplate;
use App\Models\AdminNotificationTemplate;
use App\Models\Device;
use App\Models\Language;

class TicketController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService, Auth $auth)
    {
        $this->twilioService = $twilioService;
    }
    
    public function ticket_reasons(Request $request)
    {
        $suppprtTicketReason = DB::table('ticket_reasons')->where('status','Active')->get();
        return GlobalFunction::sendDataResponse(true, 'Data fetched successfully', $suppprtTicketReason);
    }
    
    public function index(Request $request)
    {   
        $rules = [
            'start' => 'required',
            'count' => 'required',
            'user_id'=>'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $notifications =SupportTicket::select('id','title','description','subject','priority','status')->where('user_id',$request->user_id)->offset($request->start)
            ->limit($request->count)
            ->orderBy('id', 'DESC')
            ->get();

        return GlobalFunction::sendDataResponse(true, 'Data fetched successfully', $notifications);
    }

    public function create(Request $request)
    {
        $rules =[
            'subject' => 'required|string|max:191',
            'priority' => 'required|string|max:191',
            'description' => 'required|string',
            'user_id'=>'required',
        ];
        $settings = GlobalSettings::first();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        } 
        
        $user = Users::where('id',$request->user_id)->first();
        if(is_null($user)){
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        $support = SupportTicket::insert([
            'title' => 'Ticket Created By '.$user->first_name .' '.$user->last_name,
            'ticket_id'=>GlobalFunction::generateSupportTicketNumber(),
            'description' => $request->description,
            'subject' => $request->subject,
            'user_id'=>$request->user_id,
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
        
        SupportTicketMessage::insert([
            'support_ticket_id' => $last_inserted_id,
            'message' => 'Thanks for contacting us. Our support team will contact you back within 4 hours.',
            'attachment' => null,
            'type' => 'admin',
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
        
        return response()->json(['status' => true, 'message' =>"Support Ticket Created Success"]);
    }    
            
    public function details(Request $request)
    {
        $rules =[
            'user_id'=>'required',
            'ticket_id'=>'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        } 
        
        $user = Users::where('id',$request->user_id)->first();
        if(is_null($user)){
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        $notifications = SupportTicketMessage::where('support_ticket_id', $request->ticket_id)->get();
        foreach($notifications as $notification){
            $notif[] = [
                "id" => $notification->id,
                "message" => html_entity_decode(strip_tags($notification->message)),
                "notify" => $notification->notify,
                "attachment" => $notification->attachment,
                "type" => $notification->type,
                "support_ticket_id" => $notification->support_ticket_id,
                "is_read" => $notification->is_read,
                "created_at" => \Carbon\Carbon::parse($notification->created_at)->format('d-M-Y h:i:s'),
                "updated_at" => $notification->updated_at,
            ];
        }
        return GlobalFunction::sendDataResponse(true, 'Data fetched successfully', $notif);
    }         
         
    public function reply(Request $request)
    {
        $rules =[
            'ticket_id' => 'required|string|max:191',
            'message' => 'required|string',
        ];
        $settings = GlobalSettings::first();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        } 
        
        $attachment=null;
        if ($request->has('attachment')) {
            $attachment= GlobalFunction::saveFileAndGivePath($request->attachment);
        }
      
        SupportTicketMessage::insert([
            'support_ticket_id' => $request->ticket_id,
            'message' => $request->message,
            'attachment'=>$attachment,
            'type' => 'user',
            'is_read' => 0,
            'created_at'=>date("Y-m-d H:i:s")
        ]);
        
        $ticket = SupportTicket::where('id', $request->ticket_id)->first();
        $user = Users::where('id', $ticket->user_id)->first();
        $user_name = ucfirst($user->first_name.' '.$user->last_name);
        
        $superAdmin = Admin::where('user_type',1)->first();
        $adminbookingsuccesemail=AdminEmailTemplate::find(15);
        $admin_subject=$adminbookingsuccesemail->email_subjects??'';
        $admin_subject=str_replace(['{ticket_id}'],[$ticket->ticket_id],$admin_subject);
        $admin_content=$adminbookingsuccesemail->email_content??''; 
        $admin_content=str_replace(["{ticket_id}", "{ticket_reply}"], [$ticket->ticket_id, $request->message],$admin_content);
        
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
        
        $msg="Message Send Success";
        return response()->json(['status' => true, 'message' => $msg]);
    }   
}
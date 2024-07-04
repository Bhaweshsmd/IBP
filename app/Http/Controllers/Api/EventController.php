<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Banners;
use App\Models\Bookings;
use App\Models\Constants;
use App\Models\Coupons;
use App\Models\FaqCats;
use App\Models\Faqs;
use App\Models\GlobalFunction;
use App\Models\GlobalSettings;
use App\Models\PlatformEarningHistory;
use App\Models\SalonCategories;
use App\Models\SalonNotifications;
use App\Models\SalonPayoutHistory;
use App\Models\SalonReviews;
use App\Models\Salons;
use App\Models\Taxes;
use App\Models\UserNotification;
use App\Models\UserWalletRechargeLogs;
use App\Models\UserWithdrawRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Google\Client;
use Illuminate\Support\Facades\File;
Use DB;
use App\Models\Language;
use App\Models\LanguageContent;
use App\Models\User;
use App\Models\EmailTemplate;
use App\Models\Fee;
use App\Models\NotificationTemplate;
use App\Models\SupportTicket;
use App\Models\RevenueSetting;
use App\Models\AdminEmailTemplate;
use App\Models\CardFee;
use App\Models\CardTopup;
use App\Models\SupportTicketMessage;
use App\Models\Users;
use App\Models\Card;
use App\Models\TansactionType;
use Carbon\Carbon;
use App\Models\AdminNotificationTemplate;
use App\Models\MaintenanceFee;
use App\Models\CardmembershipFee;
use App\Jobs\SendEmail;
use App\Jobs\SendAdminNotification;
use App\Models\Device;

class EventController extends Controller
{
    public function events()
    {
        $object=DB::table('event_type')->where('status','Active')->orderBy('short_id','ASC')->get();
        return GlobalFunction::sendDataResponse(true, 'Data fetch successfully', $object);
    }
    
    public function index(Request $request)
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
      
        $object=DB::table('event_inquiries')->where('user_id',$request->user_id)->orderBy('id','desc')->get();
        return GlobalFunction::sendDataResponse(true, 'Data fetch successfully', $object);
    }
  
    public function create(Request $request)
    {
       $rules = [
            'user_id' => 'required',
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
        
        $data=[
            'user_id'=>$request->user_id,
            'event_type'=>$request->event_type,
            'no_of_people'=>$request->no_of_people,
            'message'=>$request->message,
            'event_date'=>$request->event_date,
        ];
        
        DB::table('event_inquiries')->insert($data);
        
        $settings = GlobalSettings::first();
        
        try{
            $user=Users::where('id',$request->user_id)->first();
            $user_name= $user['first_name'].' '.$user['last_name'];
            $email=$user['email'];
            $phone=$user['formated_number'];
            $date_time=date('d-m-Y h:i: A',strtotime($request->event_date));
            
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
            'message' => 'Inquiry submitted successfully',
        ]);
    }
}
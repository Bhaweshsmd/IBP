<?php

namespace App\Http\Controllers\Admin;
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
use App\Models\Device;

class TicketsController extends Controller
{
    public function index(){
        return view('tickets.index');
    }
    
    function list(Request $request)
    {
        $totalData =  SupportTicket::count();
        $rows = SupportTicket::orderBy('id', 'DESC')->get();

        $result = $rows;

        $columns = array(
            0 => 'id',
            1 => 'fullname',
            2 => 'identity',
            3 => 'username',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalFiltered = $totalData;
        if (empty($request->input('search.value'))) {
            $result = SupportTicket::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  SupportTicket::with(['user'])->where(function ($query) use ($search) {
                $query->Where('ticket_id', 'LIKE', "%{$search}%")
                    ->orWhere('reason', 'LIKE', "%{$search}%")
                     ->orWhere('subject', 'LIKE', "%{$search}%");
            })->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = SupportTicket::where(function ($query) use ($search) {
                $query->Where('ticket_id', 'LIKE', "%{$search}%")
                    ->orWhere('reason', 'LIKE', "%{$search}%")
                     ->orWhere('subject', 'LIKE', "%{$search}%");
            })->count();
        
                 
            if(!$totalFiltered){
                    
                   $users_ids= Users::where('profile_id','LIKE', "%{$search}%")
                    ->orWhere('first_name', 'LIKE', "%{$search}%")
                   ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->pluck('id');
                  
                 $result =  SupportTicket::whereIn('user_id',$users_ids)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
                 $totalFiltered = SupportTicket::whereIn('user_id',$users_ids)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->count();
            }  
        }
        $data = array();
        foreach ($result as $k=>$item) {

            $type = '';
            if ($item->type == Constants::taxFixed) {
                $type = '<span class="badge bg-primary text-white">' . __('Fixed') . '</span>';
            }
            if ($item->type == Constants::taxPercent) {
                $type = '<span class="badge bg-primary text-white">' . __('Percent') . '</span>';
            }

            $onOff = "";
            if ($item->status == 1) {
                $onOff = '<label class="switch ">
                                <input rel=' . $item->id . ' type="checkbox" class="onoff" checked>
                                <span class="slider round"></span>
                            </label>';
            } else {
                $onOff = '<label class="switch ">
                                <input rel=' . $item->id . ' type="checkbox" class="onoff">
                                <span class="slider round"></span>
                            </label>';
            }
            
            if(has_permission(session()->get('user_type'), 'view_tickets')){
                $view = '<a href="' . route('view.ticket', $item->id) . '" class="mr-2 btn btn-info text-white  viewBtn" rel=' . $item->user_id . ' ><i class="fa fa-eye"></i></a>';
            }else{
                $view = '';
            }
            
            $action = $view;
             $userName = '<a href="' . route('users.profile', $item->user_id) . '"><span class="badge bg-primary text-white">' . $item->user->first_name.' '.$item->user->last_name . '</span></a>';

            $data[] = array(
                ++$k,
                $item->ticket_id,
                $userName,
                $item->reason,
                // $item->description,
                $item->subject,
                $item->priority,
                $item->status,
                $action,
            );
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => $totalFiltered,
            "data"            => $data
        );
        echo json_encode($json_data);
        exit();
    }
    
    function view($id)
    {
        $tickets = SupportTicket::find($id);
        $ticket_details = SupportTicketMessage::where('support_ticket_id', $id)->orderBy('id', 'asc')->get();
        $settings = GlobalSettings::first();
        $user = User::find($tickets->user_id);
        $totalBookings = Bookings::where('user_id', $id)->count();
        
        if ($user->profile_image == null) {
            $profile_image = '<img src="https://placehold.jp/150x150.png" style="width: 50px; height:50px; border-radius: 50px;">';
        } else {
            $imgUrl = GlobalFunction::createMediaUrl($user->profile_image);
            $profile_image = '<img src="' . $imgUrl . '" style="width: 50px; height:50px; border-radius: 50px;">';
        }
        
        $admin = DB::table('admin_user')->where('user_name', session()->get('user_name'))->first();
        
        if ($user->profile_image == null) {
            $admin_image = '<img src="https://placehold.jp/150x150.png" style="width: 50px; height:50px; border-radius: 50px;">';
        } else {
            $imgUrl = GlobalFunction::createMediaUrl($admin->picture);
            $admin_image = '<img src="' . $imgUrl . '" style="width: 50px; height:50px; border-radius: 50px;">';
        }
        
        return view('tickets.view', [
            'tickets' => $tickets,
            'ticket_details' => $ticket_details,
            'settings' => $settings,
            'totalBookings' => $totalBookings,
            'user' => $user,
            'profile_image' => $profile_image,
            'admin_image' => $admin_image,
        ]);
    }
    
    public function reply(Request $request, $id)
    {
        $rules =[
            'message' => 'required',
        ]; 

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return back()->with(['ticket_message' => $msg]);
        } 
        
        if ($request->has('attachment')) {
            $attachment = GlobalFunction::saveFileAndGivePath($request->attachment);
        }else{
            $attachment = null;
        }
      
        SupportTicketMessage::insert([
            'support_ticket_id' => $id,
            'message' => $request->message,
            'attachment'=>$attachment,
            'type' => 'admin',
            'is_read' => 0,
            'created_at'=>date("Y-m-d H:i:s")
        ]);
        
        $check_ticket = SupportTicket::where('id', $id)->first();
        $user = Users::where('id', $check_ticket->user_id)->first();
        
        $type = 'ticket';
        
        $check_device = Device::where('user_id', $user->id)->first();
        if(!empty($check_device->language)){
            $act_lang = $check_device->language;
        }else{
            $act_lang = '1';
        }
        
        $notification_template = NotificationTemplate::find(15);
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
        
        $title = str_replace(["{ticket_id}"],[$check_ticket->ticket_id],$title);
        $message = str_replace(["{ticket_id}"],[$check_ticket->ticket_id],$message);
        
        $item = new UserNotification();
        $item->user_id = $check_ticket->user_id;
        $item->title = $title;
        $item->description = $message;
        $item->notification_type = $type;
        $item->temp_id = '11';
        $item->ticket_id = $check_ticket->ticket_id;
        $item->save();
        
        GlobalFunction::sendPushToUser($title, $message, $user);
        
        return back()->with(['ticket_message' => 'Message Sent Successfully.']);
    } 
    
    public function status(Request $request, $id)
    {
        $rules =[
            'status' => 'required',
        ]; 

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return back()->with(['ticket_message' => $msg]);
        } 
        
        SupportTicket::where('id', $id)->update(['status' => $request->status]);
        
        return back()->with(['ticket_message' => 'Status Updated Successfully.']);
    } 
}
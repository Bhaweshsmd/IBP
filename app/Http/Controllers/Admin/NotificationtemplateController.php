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

class NotificationtemplateController extends Controller
{
    public function index()
    {    
        $email_template=DB::table('notification_template')->get();
        $settings = GlobalSettings::first();
        return view('templates.notification.index', [
            'settings' => $settings,'email_template'=>$email_template
        ]);
    }
    
    function list(Request $request)
    {
        $totalData =  NotificationTemplate::count();
        $rows = NotificationTemplate::orderBy('id', 'DESC')->get();
        $settings = GlobalSettings::first();

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
            $result = NotificationTemplate::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  NotificationTemplate::where(function ($query) use ($search) {
                $query->Where('notification_subjects', 'LIKE', "%{$search}%");
            })->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = NotificationTemplate::where(function ($query) use ($search) {
                $query->Where('notification_subjects', 'LIKE', "%{$search}%");
            })->count();
        }
        $data = array();
        foreach ($result as $k=>$item) {
            $editurl= url('notification-templates-update/'.$item->id);
            
            if(has_permission(session()->get('user_type'), 'view_notification')){
                $edit = '<a   href="'.$editurl.'" class="mr-2 btn btn-primary text-white editBtn " rel=' . $item->id . ' ><i class="fa fa-edit"></i></a>';
            }else{
                $edit = '';
            }
            
            $action = $edit;
            
            if($item->active){
                $status="Active";
            }else{
                $status="Inactive";
            }

            $data[] = array(
                ++$k,
                $item->title,
                $item->notification_subjects,
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
    
    function store(Request $request)
    {
        if($request->isMethod('post')){
            $rules = [
                'notification_subjects' => 'required|unique:notification_template',
                'notification_content' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $messages = $validator->errors()->all();
                $msg = $messages[0];
                return back()->with(['email_message' => $msg]);
            }
             
            $item = new NotificationTemplate();
            $item->title=$request->title;
            $item->notification_subjects=$request->notification_subjects;
            $item->notification_subject_pap=$request->notification_subject_pap;
            $item->notification_subject_nl=$request->notification_subject_nl;
            $item->notification_content=$request->notification_content;
            $item->notification_content_pap=$request->notification_content_pap;
            $item->notification_content_nl=$request->notification_content_nl;
            $item->save();
            return redirect('notification-templates')->with(['email_message' => 'Notification Template Added Successfully']);
        }
        $settings = GlobalSettings::first();
        return view('templates.notification.create', [
        'settings' => $settings
        ]);
    }
    
    function update(Request $request,$id)
    {
        if($request->isMethod('post')){
            $rules = [
                'notification_subjects' => 'required',
                'notification_content' => 'required',
                'id'=>'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $messages = $validator->errors()->all();
                $msg = $messages[0];
                return back()->with(['email_message' => $msg]);
            }
            $item['title']=$request->title;   
            $item['notification_subjects']=$request->notification_subjects;
            $item['notification_subject_pap']=$request->notification_subject_pap;
            $item['notification_subject_nl']=$request->notification_subject_nl;
            $item['notification_content']=$request->notification_content;
            $item['notification_content_pap']=$request->notification_content_pap;
            $item['notification_content_nl']=$request->notification_content_nl;
                
            NotificationTemplate::where('id',$request->id)->update($item);
            return redirect('notification-templates')->with(['email_message' => 'Notification Template Updated Successfully']);
        }
        
        $emailTemplate=NotificationTemplate::find($id);
        $settings = GlobalSettings::first();
        return view('templates.notification.edit', [
        'settings' => $settings,'emailTemplate'=>$emailTemplate
        ]);
    }
    
    public function admin_notification()
    {    
        $email_template=DB::table('admin_notification_template')->get();
        $settings = GlobalSettings::first();
        return view('templates.notification.adminnotification', [
            'settings' => $settings,'email_template'=>$email_template
        ]);
    }
    
    function admin_notification_list(Request $request)
    {
        $totalData =  AdminNotificationTemplate::count();
        $rows = AdminNotificationTemplate::orderBy('id', 'DESC')->get();
        $settings = GlobalSettings::first();

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
            $result = AdminNotificationTemplate::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  AdminNotificationTemplate::where(function ($query) use ($search) {
                $query->Where('notification_subjects', 'LIKE', "%{$search}%");
            })->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = AdminNotificationTemplate::where(function ($query) use ($search) {
                $query->Where('notification_subjects', 'LIKE', "%{$search}%");
            })->count();
        }
        $data = array();
        foreach ($result as $k=>$item) {
            $editurl= url('admin-notification-templates-update/'.$item->id);
            
            if(has_permission(session()->get('user_type'), 'view_notification')){
                $edit = '<a   href="'.$editurl.'" class="mr-2 btn btn-primary text-white editBtn " rel=' . $item->id . ' ><i class="fa fa-edit"></i></a>';
            }else{
                $edit = '';
            }
            
            $action = $edit;
            
             if($item->active){
                  $status="Active";
             }else{
                  $status="Inactive";
             }

            $data[] = array(
                ++$k,
                $item->title,
                $item->notification_subjects,
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
    
    function admin_notification_store(Request $request)
    {
        if($request->isMethod('post')){
            $rules = [
                'notification_subjects' => 'required|unique:notification_template',
                'notification_content' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $messages = $validator->errors()->all();
                $msg = $messages[0];
                return back()->with(['email_message' => $msg]);
            }
             
            $item = new AdminNotificationTemplate();
            $item->title=$request->title;
            $item->notification_subjects=$request->notification_subjects;
            $item->notification_subject_pap=$request->notification_subject_pap;
            $item->notification_subject_nl=$request->notification_subject_nl;
            $item->notification_content=$request->notification_content;
            $item->notification_content_pap=$request->notification_content_pap;
            $item->notification_content_nl=$request->notification_content_nl;
            $item->save();
            return redirect('admin-notification-templates')->with(['email_message' => 'Notification Template Added Successfully']);
        }
        $settings = GlobalSettings::first();
        return view('templates.notification.admincreate', [
        'settings' => $settings
        ]);
    }
    
    function admin_notification_update(Request $request,$id)
    {
        if($request->isMethod('post')){
            $rules = [
                'notification_subjects' => 'required',
                'notification_content' => 'required',
                'id'=>'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $messages = $validator->errors()->all();
                $msg = $messages[0];
                return back()->with(['email_message' => $msg]);
            }
            $item['title']=$request->title;   
            $item['notification_subjects']=$request->notification_subjects;
            $item['notification_subject_pap']=$request->notification_subject_pap;
            $item['notification_subject_nl']=$request->notification_subject_nl;
            $item['notification_content']=$request->notification_content;
            $item['notification_content_pap']=$request->notification_content_pap;
            $item['notification_content_nl']=$request->notification_content_nl;
                
            AdminNotificationTemplate::where('id',$request->id)->update($item);
            return redirect('admin-notification-templates')->with(['email_message' => 'Notification Template Updated Successfully']);
        }
        
        $emailTemplate=AdminNotificationTemplate::find($id);
        $settings = GlobalSettings::first();
        return view('templates.notification.adminedit', [
        'settings' => $settings,'emailTemplate'=>$emailTemplate
        ]);
    }
}
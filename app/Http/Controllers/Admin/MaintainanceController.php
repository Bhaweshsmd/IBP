<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Language;
use App\Models\Blog;
use App\Models\Admin;
use App\Models\GlobalFunction;
use App\Models\GlobalSettings;
use App\Models\MaintenanceSetting;
use App\Jobs\SendEmail;
use App\Models\Users;
use App\Models\EmailTemplate;
use App\Models\UserNotification;
use App\Models\NotificationTemplate;
use App\Models\Device;

class MaintainanceController extends Controller
{
    public function index()
    {
        $data['languages'] = Language::get();
        $data['settings'] = MaintenanceSetting::orderBy('id', 'desc')->get();
        return view('maintainance.index', $data);
    }
    
    function list(Request $request)
    {
        $totalData = MaintenanceSetting::count();
        $rows = MaintenanceSetting::orderBy('id', 'DESC')->get();

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
            $result = MaintenanceSetting::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result = MaintenanceSetting::where(function ($query) use ($search) {
                    $query->Where('subject_en', 'LIKE', "%{$search}%")
                        ->orWhere('status', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
                $totalFiltered = MaintenanceSetting::where(function ($query) use ($search) {
                    $query->Where('subject_en', 'LIKE', "%{$search}%")
                        ->orWhere('status', 'LIKE', "%{$search}%");
                })
                ->count();
           
            if(!$totalFiltered){
                $result =  MaintenanceSetting::where('subject_en', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
                
                $totalFiltered = MaintenanceSetting::where('subject_en', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->count();
            }       
        }
        
        $data = array();
        foreach ($result as $k=>$item) 
        {
            if($item->status == '1'){
                $status = 'Active';
            }else{
                $status = 'Inactive';
            }
            
            if($item->type == '1'){
                $platformm = 'Web';
            }elseif($item->type == '2'){
                $platformm = 'App';
            }else{
                $platformm = 'Both';
            }
            
            $admin_detials = Admin::where('user_name',session()->get('user_name'))->first();
            
            if(has_permission(session()->get('user_type'), 'view_maintenance')){
                $view = '<a href="" class="mr-2 btn btn-success text-white remind remindBtn" rel=' . $item->id . ' ><i class="fa fa-reply faPostion"></i></a>';
            }else{
                $view = '';
            }
            
            if(has_permission(session()->get('user_type'), 'edit_maintenance')){
                $edit = '<a href="' . route('edit.maintainance', $item->id) . '" class="mr-2 btn btn-info text-white editBtn" rel=' . $item->id . ' ><i class="fa fa-edit"></i></a>';
            }else{
                $edit = '';
            }
            
            if(has_permission(session()->get('user_type'), 'delete_maintenance')){
                $delete = '<a href="" class="mr-2 btn btn-danger text-white delete deleteBtn" rel=' . $item->id . ' ><i class="fa fa-trash faPostion"></i></a>';
            }else{
                $delete = '';
            }
            
            $action = $view.$edit.$delete;
            
            $data[] = array(
                ++$k,
                $item->subject_en,
                date('D, d F Y',strtotime($item->date)),
                GlobalFunction::formateTimeString($item->from_time).' - '.GlobalFunction::formateTimeString($item->to_time),
                $platformm,
                $status,
                date('D, d F Y',strtotime($item->created_at)),
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
    
    public function create()
    {
        $data['languages'] = Language::get();
        return view('maintainance.create', $data);
    }
    
    public function store(Request $request)
    {
        $setting = MaintenanceSetting::create([
            'subject_en' => $request->subject_en,
            'subject_pap' => $request->subject_pap,
            'subject_nl' => $request->subject_nl,
            'date' => $request->date,
            'from_time' => $request->from_time,
            'to_time' => $request->to_time,
            'message_en' => $request->message_en,
            'message_pap' => $request->message_pap,
            'message_nl' => $request->message_nl,
            'type' => $request->type,
            'status' => '1',
        ]); 
        
        $maintainance_mail = EmailTemplate::find(16);
        $subject = $maintainance_mail->email_subjects;
        $content = $maintainance_mail->email_content;
        $subject = str_replace(["{subject}"], [$setting->subject_en],$subject);
        $content = str_replace(["{body}"], [$setting->message_en],$content);
        
        $users = Users::get();
     
        foreach($users as $user){
            $details = [         
                "subject" => $subject ,
                "message" => $content,
                "to" => $user->email,
            ];
            send_email($details);
            
            $type = 'maintenance';
            $date_time = $request->date.' from '.$request->from_time.' to '.$request->to_time;
            
            $check_device = Device::where('user_id', $user->id)->first();
            if(!empty($check_device->language)){
                $act_lang = $check_device->language;
            }else{
                $act_lang = '1';
            }
            
            $notification_template = NotificationTemplate::find(12);
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
            
            $message = str_replace(["{date_time}"],[$date_time],$message);
            
            $item = new UserNotification();
            $item->user_id = $user->id;
            $item->title = $title;
            $item->description = $message;
            $item->notification_type = $type;
            $item->temp_id = '12';
            $item->date_time = $date_time;
            $item->save();
            
            GlobalFunction::sendPushToUser($title, $message, $user);
        }
        
        return redirect('maintainance')->with(['maintenance_message' => 'Maintenance Setting Created Successfully']);
    }
    
    public function edit($id)
    {
        $data['languages'] = Language::get();
        $data['maintainance'] = MaintenanceSetting::where('id', $id)->first();
        return view('maintainance.edit', $data);
    }
    
    public function update(Request $request, $id)
    {
        $rs = MaintenanceSetting::where('id', $id)->update([
            'subject_en' => $request->subject_en,
            'subject_pap' => $request->subject_pap,
            'subject_nl' => $request->subject_nl,
            'date' => $request->date,
            'from_time' => $request->from_time,
            'to_time' => $request->to_time,
            'message_en' => $request->message_en,
            'message_pap' => $request->message_pap,
            'message_nl' => $request->message_nl,
            'type' => $request->type,
        ]);
        
        return redirect('maintainance')->with(['maintenance_message' => 'Maintenance Setting Updated Successfully']);
    }
    
    public function delete($id)
    {
        $setting = MaintenanceSetting::where('id', $id)->delete();
        
        return redirect('maintainance')->with(['maintenance_delete' => 'Maintenance Setting Deleted Successfully']);
    }
    
    public function remind($id)
    {
        $setting = MaintenanceSetting::where('id', $id)->first();
        
        $maintainance_mail = EmailTemplate::find(16);
        $subject = $maintainance_mail->email_subjects;
        $content = $maintainance_mail->email_content;
        $subject = str_replace(["{subject}"], [$setting->subject_en],$subject);
        $content = str_replace(["{body}"], [$setting->message_en],$content);
        
        $users = Users::get(); 
     
        foreach($users as $user){
            $details = [         
                "subject" => $subject ,
                "message" => $content,
                "to" => $user->email,
            ];
            send_email($details);
            
            $type = 'maintenance';
            $date_time = $setting->date.' from '.$setting->from_time.' to '.$setting->to_time;
            
            $check_device = Device::where('user_id', $user->id)->first();
            if(!empty($check_device->language)){
                $act_lang = $check_device->language;
            }else{
                $act_lang = '1';
            }
            
            $notification_template = NotificationTemplate::find(12);
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
            
            $message = str_replace(["{date_time}"],[$date_time],$message);
            
            $item = new UserNotification();
            $item->user_id = $user->id;
            $item->title = $title;
            $item->description = $message;
            $item->notification_type = $type;
            $item->temp_id = '12';
            $item->date_time = $date_time;
            $item->save();
            
            GlobalFunction::sendPushToUser($title, $message, $user);
        }
        
	    return redirect('maintainance')->with(['maintenance_message' => 'Maintenance Setting Reminder Sent Successfully']);
    }
    
    public function break($id)
    {
        $data['maintainance'] = MaintenanceSetting::where('id', $id)->first();
        return view('maintainance.break', $data);
    }
}


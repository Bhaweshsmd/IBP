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

class EmailtemplateController extends Controller
{
    public function index()
    {    
        $email_template = EmailTemplate::get();
        $settings = GlobalSettings::first();
        return view('templates.email.index', [
            'settings' => $settings,'email_template'=>$email_template
        ]);
    }
    
    function list(Request $request)
    {
        $totalData =  EmailTemplate::count();
        $rows = EmailTemplate::orderBy('id', 'DESC')->get();
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
            $result = EmailTemplate::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  EmailTemplate::where(function ($query) use ($search) {
                $query->Where('email_subjects', 'LIKE', "%{$search}%");
            })->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = EmailTemplate::where(function ($query) use ($search) {
                $query->Where('email_subjects', 'LIKE', "%{$search}%");
            })->count();
        }
        $data = array();
        foreach ($result as $k=>$item) {
            $editurl= url('email-templates-update/'.$item->id);
            if(has_permission(session()->get('user_type'), 'view_emails')){
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
                $item->email_subjects,
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
                'email_subjects' => 'required|unique:email_template',
                'email_content' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $messages = $validator->errors()->all();
                $msg = $messages[0];
                return back()->with(['email_message' => $msg]);
            }
             
            $item = new EmailTemplate();
            $item->title=$request->title;
            $item->email_subjects=$request->email_subjects;
            $item->email_subject_pap=$request->email_subject_pap;
            $item->email_subject_nl=$request->email_subject_nl;
            $item->email_content=$request->email_content;
            $item->email_content_pap=$request->email_content_pap;
            $item->email_content_nl=$request->email_content_nl;
            $item->save();
            return redirect('email-templates')->with(['email_message' => 'Email Template Added Successfully']);
        }
        $settings = GlobalSettings::first();
        return view('templates.email.create', [
        'settings' => $settings
        ]);
    }
    
    function update(Request $request,$id)
    {
        if($request->isMethod('post')){
            $rules = [
                'email_subjects' => 'required',
                'email_content' => 'required',
                'id'=>'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $messages = $validator->errors()->all();
                $msg = $messages[0];
                return back()->with(['email_message' => $msg]);
            }
            
            $item['title']=$request->title;
            $item['email_subjects']=$request->email_subjects;
            $item['email_subject_pap']=$request->email_subject_pap;
            $item['email_subject_nl']=$request->email_subject_nl;
            $item['email_content']=$request->email_content;
            $item['email_content_pap']=$request->email_content_pap;
            $item['email_content_nl']=$request->email_content_nl;
            EmailTemplate::where('id',$request->id)->update($item);
            return redirect('email-templates')->with(['email_message' => 'Email Template Updated Successfully']);
        }
        
        $emailTemplate=EmailTemplate::find($id);
        $settings = GlobalSettings::first();
        return view('templates.email.edit', [
        'settings' => $settings,'emailTemplate'=>$emailTemplate
        ]);
    }
    
    public function admin_email()
    {    
        $email_template = AdminEmailTemplate::get();
        $settings = GlobalSettings::first();
        return view('templates.email.adminemail', [
            'settings' => $settings,'email_template'=>$email_template
        ]);
    }

    function admin_email_list(Request $request)
    {
        $totalData = AdminEmailTemplate::count();
        $rows = AdminEmailTemplate::orderBy('id', 'DESC')->get();
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
            $result = AdminEmailTemplate::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  AdminEmailTemplate::where(function ($query) use ($search) {
                $query->Where('email_subjects', 'LIKE', "%{$search}%");
            })->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = AdminEmailTemplate::where(function ($query) use ($search) {
                $query->Where('email_subjects', 'LIKE', "%{$search}%");
            })->count();
        }
        $data = array();
        foreach ($result as $k=>$item) {
            $editurl= url('admin-email-templates-update/'.$item->id);
            if(has_permission(session()->get('user_type'), 'view_admin_emails')){
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
                $item->email_subjects,
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
     
    function admin_email_store(Request $request)
    {
        if($request->isMethod('post')){
            $rules = [
                'email_subjects' => 'required|unique:email_template',
                'email_content' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $messages = $validator->errors()->all();
                $msg = $messages[0];
                return back()->with(['email_message' => $msg]);
            }
             
            $item = new AdminEmailTemplate();
            $item->title=$request->title;
            $item->email_subjects=$request->email_subjects;
            $item->email_subject_pap=$request->email_subject_pap;
            $item->email_subject_nl=$request->email_subject_nl;
            $item->email_content=$request->email_content;
            $item->email_content_pap=$request->email_content_pap;
            $item->email_content_nl=$request->email_content_nl;
            $item->save();
            return redirect('admin-email-templates')->with(['email_message' => 'Email Template Added Successfully']);
        }
        $settings = GlobalSettings::first();
        return view('templates.email.admincreate', [
        'settings' => $settings
        ]);
    }
    
    function admin_email_update(Request $request,$id)
    {
        if($request->isMethod('post')){
            $rules = [
                'email_subjects' => 'required',
                'email_content' => 'required',
                'id'=>'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $messages = $validator->errors()->all();
                $msg = $messages[0];
                return back()->with(['email_message' => $msg]);
            }
             
            $item['title']=$request->title;
            $item['email_subjects']=$request->email_subjects;
            $item['email_subject_pap']=$request->email_subject_pap;
            $item['email_subject_nl']=$request->email_subject_nl;
            $item['email_content']=$request->email_content;
            $item['email_content_pap']=$request->email_content_pap;
            $item['email_content_nl']=$request->email_content_nl;
            AdminEmailTemplate::where('id',$request->id)->update($item);
            return redirect('admin-email-templates')->with(['email_message' => 'Email Template Updated Successfully']);
        }
        
        $emailTemplate=AdminEmailTemplate::find($id);
        $settings = GlobalSettings::first();
        return view('templates.email.adminedit', [
        'settings' => $settings,'emailTemplate'=>$emailTemplate
        ]);
    }
}
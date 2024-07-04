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

class NotificationController extends Controller
{
    function index()
    {  
        SalonNotifications::where('is_read',null)->orWhere('is_read',0)->update(['is_read'=>1]);
        $users = Users::orderBy('first_name', 'asc')->get();
        return view('notifications.index', ['users'=>$users]);
    }
    
    function list(Request $request)
    {
        $totalData =  UserNotification::where('notification_type','promotional')->count();
        $rows = UserNotification::where('notification_type','promotional')->orderBy('id', 'DESC')->get();

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
            $result = UserNotification::where('notification_type','promotional')->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  UserNotification::where('notification_type','promotional')->orWhere('title', 'LIKE', "%{$search}%")
                ->orWhere('description', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = UserNotification::where('notification_type','promotional')->where('title', 'LIKE', "%{$search}%")
                ->orWhere('description', 'LIKE', "%{$search}%")
                ->count();
        }
        $data = array();
        $i=1;
        foreach ($result as $item) {
            $title = '<span class="text-dark font-weight-bold font-16">' . $item->title . '</span><br>';
            $desc = '<span>' . $item->description . '</span>';
            $notification = $title . $desc;

            $edit = '<a href="" data-description="' . $item->description . '" data-title="' . $item->title . '" class="mr-2 btn btn-primary text-white edit editBtn" rel=' . $item->id . ' ><i class="fa fa-edit"></i></a>';
            $delete = '<a href="" class="mr-2 btn btn-danger text-white delete deleteBtn" rel=' . $item->id . ' ><i class="fa fa-trash"></i></a>';
            $action =  $edit . $delete;


            $data[] = array(
                $i++,
                $notification,
                "All Customers",
                date('d-m-Y h:i A',strtotime($item->created_at)),
                $action
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
        $users = $request->user;
        if (in_array('all', $users)) {
            $item = new UserNotification();
            $item->title = $request->title;
            $item->description = $request->description;
            $item->notification_type = "promotional";
            $item->save();
            
            $items = new SalonNotifications();
            $items->title = $request->title;
            $items->description = $request->description;
            $items->notification_type = "promotional";
            $items->save();
            
            GlobalFunction::sendPushNotificationToUsers($item->title, $item->description);
        }else{
            foreach($users as $user){
                
                $check_user = Users::where('id', $user)->first();
                
                $item = new UserNotification();
                $item->user_id = $user;
                $item->title = $request->title;
                $item->description = $request->description;
                $item->notification_type = "promotional";
                $item->save();
                
                $items = new SalonNotifications();
                $items->user_id = $user;
                $items->title = $request->title;
                $items->description = $request->description;
                $items->notification_type = "promotional";
                $items->save();
                
                GlobalFunction::sendPushToUser($item->title, $item->description, $check_user);
            }
            
            return GlobalFunction::sendSimpleResponse(true, 'User Notification added successfully');
        }
    }
    
    function update(Request $request)
    {
        $item = UserNotification::find($request->id);
        $item->title = $request->title;
        $item->description = $request->description;
        $item->save();
        return GlobalFunction::sendSimpleResponse(true, 'User Notification edited successfully');
    }
    
    function delete($id)
    {
        $item = UserNotification::find($id);
        $item->delete();

        return redirect('notifications')->with(['notification_message' => 'User Notification Deleted Successfully']);
    }
    
    function platform_notification_list(Request $request)
    {
        $totalData =  SalonNotifications::where('notification_type','!=','promotional')->count();
        $rows = SalonNotifications::where('notification_type','!=','promotional')->orderBy('id', 'DESC')->get();

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
            $result = SalonNotifications::with('user')->where('notification_type','!=','promotional')->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  SalonNotifications::with('user')->where('notification_type','!=','promotional')->Where('title', 'LIKE', "%{$search}%")
                ->orWhere('description', 'LIKE', "%{$search}%")
                ->orWhere('title', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = SalonNotifications::where('notification_type','!=','promotional')->where('id', 'LIKE', "%{$search}%")
                ->orWhere('description', 'LIKE', "%{$search}%")
                ->orWhere('title', 'LIKE', "%{$search}%")
                ->count();
                
            if(!$totalFiltered){
                $users_ids= Users::where('profile_id','LIKE', "%{$search}%")
                    ->orWhere('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->pluck('id');
                  
                $result =  SalonNotifications::with(['user'])->where('notification_type','!=','promotional')->whereIn('user_id',$users_ids)
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhere('title', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
                    
                $totalFiltered = SalonNotifications::with(['user'])->where('notification_type','!=','promotional')->whereIn('user_id',$users_ids)
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhere('title', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->count();
            }   
        }
        
        $data = array();
        $i=1;
        foreach ($result as $item) {
            $title = '<span class="text-dark font-weight-bold font-16">' . $item->title . '</span><br>';
            $date=  '<span>' . $item->created_at . '</span><br>';
            $desc = '<span>' . $item->description . '</span>';
            $notification = $title  . $desc;
            
            if(has_permission(session()->get('user_type'), 'view_notifications')){
                $edit = '<a href="" data-description="' . $item->description . '" data-title="' . $item->title . '" class="mr-2 btn btn-primary text-white edit editBtn" rel=' . $item->id . ' ><i class="fa fa-edit"></i></a>';
            }else{
                $edit = '';
            }
            
            if(has_permission(session()->get('user_type'), 'delete_notifications')){
                $delete = '<a href="" class="mr-2 btn btn-danger text-white delete deleteBtn" rel=' . $item->id . ' ><i class="fa fa-trash"></i></a>';
            }else{
                $delete = '';
            }
                
            if($item->notification_type=="promotional"){
                $action =  $edit . $delete;
            }else{
                $action =   $delete;  
            }
            
            if($item->user_id){
              $customer = '<a href="' . route('users.profile', $item->user_id) . '"  >'.$item->user->first_name .' '.$item->user->last_name.'</a>';
            }

            $data[] = array(
                $i++,
                $notification,
                $customer??'All Customers',
                 date('d-m-Y h:i A',strtotime($item->created_at)),
                 ucfirst(str_replace('_',' ',$item->notification_type)),
                $action
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
    
    function platform_notification_delete($id)
    {
        $item = SalonNotifications::find($id);
        $item->delete();
        
        return back()->with(['notification_message' => 'Notification Deleted Successfully']);
    }
}
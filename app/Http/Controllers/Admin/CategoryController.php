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
use App\Models\Services;

class CategoryController extends Controller
{
    function index()
    {    
        $data['categories'] = SalonCategories::where('is_deleted', 0)->where('parent',null)->get();
        return view('categories.index',$data);
    }
    
    function list(Request $request)
    {
        $totalData =  SalonCategories::where('is_deleted', 0)->count();
        $rows = SalonCategories::where('is_deleted', 0)->orderBy('sort', 'asc')->get();
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
            $result = SalonCategories::where('is_deleted', 0)->offset($start)
                ->limit($limit)
                ->orderBy('sort', 'asc')
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  SalonCategories::where('is_deleted', 0)
                ->Where('title', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy('sort', 'asc')
                ->get();
            $totalFiltered = SalonCategories::where('is_deleted', 0)
                ->Where('title', 'LIKE', "%{$search}%")
                ->count();
        }
        
        $data = array();
        $i=1;
        foreach ($result as $item) {
            $imgUrl = "http://placehold.jp/150x150.png";
            if ($item->icon == null) {
                $img = '<img src="http://placehold.jp/150x150.png" width="50" height="50">';
            } else {
                $imgUrl = GlobalFunction::createMediaUrl($item->icon);
                $img = '<img src="' . $imgUrl . '" width="50" height="50">';
            }
            
            if ($item->web_icon == null) {
                $web_img = '<img src="http://placehold.jp/150x150.png" width="50" height="50">';
            } else {
                $web_imgUrl = GlobalFunction::createMediaUrl($item->web_icon);
                $web_img = '<img src="' . $web_imgUrl . '" width="50" height="50">';
            }
            
            if(has_permission(session()->get('user_type'), 'view_categories')){
                $edit = '<a data-icon="' . $imgUrl . '"    data-web="' . $web_imgUrl . '"  data-parent="' . $item->parent . '"  data-sort="' . $item->sort . '"   data-title="' . $item->title . '" data-titlepapiamentu="' . $item->title_in_papiamentu . '"  data-titledutch="' . $item->title_in_dutch . '"      href="" class="mr-2 btn btn-primary text-white edit editBtn" rel=' . $item->id . ' ><i class="fa fa-edit"></i></a>';
            }else{
                $edit = '';
            }
            
            if(has_permission(session()->get('user_type'), 'delete_categories')){
                $delete = '<a href="" class="mr-2 btn btn-danger text-white delete deleteBtn " rel=' . $item->id . ' ><i class="fa fa-trash"></i></a>';
            }else{
                $delete = '';
            }
            
            if(has_permission(session()->get('user_type'), 'view_services')){
                $services = '<a href="' . route('categorised.services', $item->id) . '" class="mr-2 btn btn-info text-white viewserviceBtn " rel=' . $item->id . ' ><i class="fa fa-eye faPostion"></i></a>';
            }else{
                $services = '';
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
            
            $action =  $edit . $delete. $services;
            
            $data[] = array(
                $i++,
                $img,
                $web_img,
                $item->title,
                $item->sort,
                $onOff,
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
        $cat = new SalonCategories();
        $cat->title = $request->title;
        $cat->slug = $request->title;
        $cat->sort = $request->sort;
        $cat->title_in_papiamentu = $request->title_in_papiamentu;
        $cat->title_in_dutch = $request->title_in_dutch;
        $cat->parent = $request->parent;
        $cat->icon = GlobalFunction::saveFileAndGivePath($request->icon);
        $cat->web_icon=GlobalFunction::saveFileAndGivePath($request->web_icon);
        $cat->save();

        return GlobalFunction::sendSimpleResponse(true, 'Category Added Successfully');
    }
    
    function update(Request $request)
    {
        $item = SalonCategories::find($request->id);
        $item->title = $request->title;
        $item->slug = $request->title;
        $item->sort = $request->sort;
        $item->title_in_papiamentu = $request->title_in_papiamentu;
        $item->title_in_dutch = $request->title_in_dutch;
       
        if ($request->has('icon')) {
            $item->icon = GlobalFunction::saveFileAndGivePath($request->icon);
        }
        
         if ($request->has('web_icon')) {
            $item->web_icon = GlobalFunction::saveFileAndGivePath($request->web_icon);
        }

        $item->save();
        return GlobalFunction::sendSimpleResponse(true, 'Salon Cat edited successfully');
    }
    
    function delete($id)
    {
        $cat = SalonCategories::find($id);
        $cat->is_deleted = 1;
        $cat->save();

        return back()->with(['category_message' => 'Category Deleted Successfully']);
    }
    
    function status($id, $status)
    {
        $service = SalonCategories::find($id);
        $service->status = $status;
        $service->save();
        if($status==0){
           Services::where('category_id',$id)->where('status',1)->update(['status'=>$status]);
        }
        return GlobalFunction::sendSimpleResponse(true, 'Status changed successfully');
    }
}
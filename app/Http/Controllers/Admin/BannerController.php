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
use App\Models\WebBanner;

class BannerController extends Controller
{
    function banners()
    {
        return view('banners.app');
    }
    
    function list(Request $request)
    {
        $totalData =  Banners::count();
        $rows = Banners::orderBy('id', 'DESC')->get();

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
            $result = Banners::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  Banners::Where('id', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Banners::Where('id', 'LIKE', "%{$search}%")
                ->count();
        }
        $data = array();
        $i=1;
        foreach ($result as $item) {


            $imgUrl = "http://placehold.jp/150x150.png";

            $imgUrl = GlobalFunction::createMediaUrl($item->image);
            $img = '<img src="' . $imgUrl . '" width="300" height="120">';
            
            if(has_permission(session()->get('user_type'), 'delete_banners')){
                $delete = '<a href="" class="mr-2 btn btn-danger text-white delete deleteBtn" rel=' . $item->id . ' ><i class="fa fa-trash"></i></a>';
            }else{
                $delete = '';
            }
            $action =  $delete;

            $data[] = array(
                $i++,
                $img,
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
        $item = new Banners();
        $item->image = GlobalFunction::saveFileAndGivePath($request->image);
        $item->save();

        return GlobalFunction::sendSimpleResponse(true, 'Banner added successfully');
    }
    
    function delete($id)
    {
        $item = Banners::find($id);
        GlobalFunction::deleteFile($item->image);
        $item->delete();
        
        return back()->with(['banner_message' => 'Banner Deleted Successfully']);
    }
    
    function web_banners()
    {
        return view('banners.web');
    }
    
    function web_banner_list(Request $request)
    {
        $totalData =  WebBanner::count();
        $rows = WebBanner::orderBy('id', 'DESC')->get();

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
            $result = WebBanner::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  WebBanner::Where('id', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = WebBanner::Where('id', 'LIKE', "%{$search}%")
                ->count();
        }
        $data = array();
        $i=1;
        foreach ($result as $item) {


            $imgUrl = "http://placehold.jp/150x150.png";

            $imgUrl = GlobalFunction::createMediaUrl($item->image);
            $img = '<img src="' . $imgUrl . '" width="300" height="120">';
            
            if(has_permission(session()->get('user_type'), 'delete_web_banners')){
                $delete = '<a href="" class="mr-2 btn btn-danger text-white delete deleteBtn" rel=' . $item->id . ' ><i class="fa fa-trash"></i></a>';
            }else{
                $delete = '';
            }
            $action =  $delete;

            $data[] = array(
                $i++,
                $img,
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
    
    function add_web_banner(Request $request)
    {
        $item = new WebBanner();
        $item->image = GlobalFunction::saveFileAndGivePath($request->image);
        $item->save();

        return GlobalFunction::sendSimpleResponse(true, 'Banner added successfully');
    }
    
    function delete_web_banner($id)
    {
        $item = WebBanner::find($id);
        GlobalFunction::deleteFile($item->image);
        $item->delete();
        
        return back()->with(['banner_message' => 'Banner Deleted Successfully']);
    }
}
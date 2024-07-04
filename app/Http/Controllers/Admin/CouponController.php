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

class CouponController extends Controller
{
    function index()
    {
        $settings = GlobalSettings::first();
        return view('coupons.index', [
            'settings' => $settings
        ]);
    }
    
    function list(Request $request)
    {
        $totalData =  Coupons::count();
        $rows = Coupons::orderBy('id', 'DESC')->get();
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
            $result = Coupons::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  Coupons::where(function ($query) use ($search) {
                $query->Where('coupon', 'LIKE', "%{$search}%")
                    ->orWhere('heading', 'LIKE', "%{$search}%")
                    ->orWhere('min_order_amount', 'LIKE', "%{$search}%")
                    ->orWhere('max_discount_amount', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            })->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Coupons::where(function ($query) use ($search) {
                $query->Where('coupon', 'LIKE', "%{$search}%")
                    ->orWhere('heading', 'LIKE', "%{$search}%")
                    ->orWhere('max_discount_amount', 'LIKE', "%{$search}%")
                    ->orWhere('min_order_amount', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            })->count();
        }
        $data = array();
        $i=1;
        foreach ($result as $item) {
            
            if(has_permission(session()->get('user_type'), 'view_coupons')){
                $edit = '<a data-description="' . $item->description . '" data-heading="' . $item->heading . '" data-minOrderAmount="' . $item->min_order_amount . '" data-maxDiscAmount="' . $item->max_discount_amount . '" data-coupon="' . $item->coupon . '" data-percentage="' . $item->percentage . '" data-expirydate="' . $item->expiry_date . '" data-available="' . $item->available . '" data-availableuser="' . $item->available_user . '" href="" class="mr-2 btn btn-primary text-white edit editBtn" rel=' . $item->id . ' ><i class="fa fa-edit faPostion"></i></a>';
            }else{
                $edit = '';
            }
            
            if(has_permission(session()->get('user_type'), 'delete_coupons')){
                $delete = '<a href="" class="mr-2 btn btn-danger text-white delete deleteBtn" rel=' . $item->id . ' ><i class="fa fa-trash faPostion"></i></a>';
            }else{
                $delete = '';
            }
            
            $action = $edit  . $delete;

            $data[] = array(
                $i++,
                $item->coupon,
                $item->percentage . '%',
                $item->expiry_date,
                $item->available,
                $item->available_user,
                $settings->currency . number_format($item->max_discount_amount, 2, '.', ','), 
                $settings->currency . number_format($item->min_order_amount, 2, '.', ','), 
                $item->heading,
                $item->description,
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
        $coupon = new Coupons();
        $coupon->coupon = $request->coupon;
        $coupon->percentage = $request->percentage;
        $coupon->max_discount_amount = $request->max_discount_amount;
        $coupon->min_order_amount = $request->min_order_amount;
        $coupon->heading = $request->heading;
        $coupon->description = $request->description;
        $coupon->expiry_date = $request->expiry_date;
        $coupon->available = $request->available;
        $coupon->available_user = $request->available_user;
        $coupon->save();

        return GlobalFunction::sendSimpleResponse(true, 'coupon added successfully!');
    }
    
    function update(Request $request)
    {
        $coupon = Coupons::find($request->id);
        $coupon->coupon = $request->coupon;
        $coupon->percentage = $request->percentage;
        $coupon->max_discount_amount = $request->max_discount_amount;
        $coupon->min_order_amount = $request->min_order_amount;
        $coupon->heading = $request->heading;
        $coupon->description = $request->description;
        $coupon->expiry_date = $request->expiry_date;
        $coupon->available = $request->available;
        $coupon->available_user = $request->available_user;
        $coupon->save();

        return GlobalFunction::sendSimpleResponse(true, 'coupon edited successfully!');
    }
    
    function delete($id)
    {
        $coupon = Coupons::find($id);
        $coupon->delete();
        return back()->with(['coupon_message' => 'Coupon Deleted Successfully']);
    }
}
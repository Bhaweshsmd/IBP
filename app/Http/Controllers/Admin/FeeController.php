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

class FeeController extends Controller
{
    function index(Request $request)
    {
        $settings = GlobalSettings::first();
        return view('settings.fees', ['data' => $settings]);
    }
    
    function list(Request $request)
    {
        $totalData =  Fee::count();
        $rows = Fee::orderBy('id', 'DESC')->get();

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
            $result = Fee::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  Fee::where(function ($query) use ($search) {
                $query->Where('type', 'LIKE', "%{$search}%")
                    ->orWhere('charge_percent', 'LIKE', "%{$search}%");
            })->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Fee::where(function ($query) use ($search) {
                $query->Where('type', 'LIKE', "%{$search}%")
                    ->orWhere('charge_percent', 'LIKE', "%{$search}%");
            })->count();
        }
        $data = array();
        foreach ($result as $item) {

            $type = $item->type;
            
            if(has_permission(session()->get('user_type'), 'view_fees')){
                $edit = '<a data-taxtitle="' . $item->type . '" data-type="' . $item->type . '" href="" data-chargepercent="' . $item->charge_percent . '"   data-maximum="' . $item->maximum . '"   data-minimum="' . $item->minimum . '"  data-day="' . $item->day_wise . '"  data-week="' . $item->week_wise . '" data-month="' . $item->month_wise . '"                            class="mr-2 btn btn-primary text-white edit editBtn" rel=' . $item->id . ' ><i class="fa fa-edit"></i></a>';
            }else{
                $edit = '';
            }
            
            $action = $edit ;

            $data[] = array(
                 ucfirst(str_replace("_"," ",$type)),
                 $item->charge_percent,
                   $item->minimum,
                 $item->maximum,
               
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
    
    function update(Request $request)
    {  
        $item = Fee::find($request->id);
        $item->type = $request->type;
        $item->charge_percent = $request->charge_percent;
        $item->maximum = $request->maximum;
        $item->minimum = $request->minimum;
        
        $item->day_wise = $request->day_wise;
        $item->week_wise = $request->week_wise;
        $item->month_wise = $request->month_wise;
        
        
        $item->save();

        return GlobalFunction::sendSimpleResponse(true, 'item edited successfully!');
    }
    
    function calculate_fees(Request $request)
    {
        $bookings['booking_price'] = $request->booking_price;
        $bookings['booking_discount'] = $request->booking_discount;
        $bookings['item_name'] = $request->item_name;
        $bookings['quantity'] = $request->quantity;
        $bookings['booking_tax'] = $request->booking_tax;
        $bookings['booking_hours'] = $request->booking_hours;
        $bookings['discounted_price'] = number_format($request->discounted_price, 2, '.', '');
        
        $service = Services::where('id',$request->service_id)->first();
        if($service->service_type == '1'){
            $bookings['payable_amount'] = number_format($request->discounted_price * $request->booking_hours, 2, '.', '');
            $bookings['total_amount'] = number_format($request->booking_price * $request->booking_hours, 2, '.', '');
        }else{
            $bookings['payable_amount'] = number_format($request->discounted_price * $request->quantity * $request->booking_hours, 2, '.', '');
            $bookings['total_amount'] = number_format($request->booking_price * $request->quantity * $request->booking_hours, 2, '.', '');
        }
            
        return GlobalFunction::sendDataResponse(true, 'Services fetched successfully', $bookings);
    }
    
    function assign_card_fees(Request $request)
    {
        $settings = CardFee::get();
        return view('settings.cardfees', ['settings' => $settings]);
    }
    
    function assign_card_fees_list(Request $request)
    {
        $totalData =  CardFee::count();
        $rows = CardFee::orderBy('id', 'DESC')->get();

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
            $result = CardFee::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  CardFee::where(function ($query) use ($search) {
                    $query->Where('fee', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = CardFee::where(function ($query) use ($search) {
                    $query->Where('fee', 'LIKE', "%{$search}%");
                })
                ->count();
        }
        $data = array();

        $settings = GlobalSettings::first();
        
        foreach ($result as $k=>$item) 
        {
            $admin = DB::table('admin_user')->where('user_id', $item->updated_by)->first();
            
            if($item->status == '1'){
                $status = 'Active';
            }else{
                $status = 'Inactive';
            }
            
            if(!empty($item->updated_by)){
                if(!empty($admin)){
                    $username = $admin->first_name.' '.$admin->last_name;
                }else{
                    $username = 'N/A';
                }
            }else{
                $username = 'N/A';
            }
            
            $data[] = array(
                ++$k,
                 $settings->currecny . number_format($item->fee, 2, '.', ','),
                $username,
                !empty($item->created_at) ? Carbon::parse($item->created_at)->format('d-M-Y') : 'N/A',
                $status,
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
    
    function assign_card_fees_store(Request $request)
    {
        $admin = DB::table('admin_user')->where('user_name', session()->get('user_name'))->first();
        
        $rs = CardFee::create([
            'fee' => $request->fee,
            'updated_by' => $admin->user_id,
        ]);
        
        
        CardFee::where('id', '!=', $rs->id)->update(['status' => '0']);
        
        return back()->with('card_fee', 'Assign Card Fee Updated Successfully');
    }
    
    function maintenance_card_fees(Request $request)
    {
        $settings = MaintenanceFee::get();
        return view('settings.maintenancefees', ['settings' => $settings]);
    }
    
    function maintenance_card_fees_list(Request $request)
    {
        $totalData =  MaintenanceFee::count();
        $rows = MaintenanceFee::orderBy('id', 'DESC')->get();

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
            $result = MaintenanceFee::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  MaintenanceFee::where(function ($query) use ($search) {
                    $query->Where('fee', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = MaintenanceFee::where(function ($query) use ($search) {
                    $query->Where('fee', 'LIKE', "%{$search}%");
                })
                ->count();
        }
        $data = array();

        $settings = GlobalSettings::first();
        
        foreach ($result as $k=>$item) 
        {
            $admin = DB::table('admin_user')->where('user_id', $item->updated_by)->first();
            
            if($item->status == '1'){
                $status = 'Active';
            }else{
                $status = 'Inactive';
            }
            
            if(!empty($item->updated_by)){
                if(!empty($admin)){
                    $username = $admin->first_name.' '.$admin->last_name;
                }else{
                    $username = 'N/A';
                }
            }else{
                $username = 'N/A';
            }
            
            $data[] = array(
                ++$k,
                 $settings->currecny . number_format($item->fee, 2, '.', ','),
                $username,
                !empty($item->created_at) ? Carbon::parse($item->created_at)->format('d-M-Y') : 'N/A',
                $status,
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
    
    function maintenance_card_fees_store(Request $request)
    {
        $admin = DB::table('admin_user')->where('user_name', session()->get('user_name'))->first();
        
        $rs = MaintenanceFee::create([
            'fee' => $request->fee,
            'updated_by' => $admin->user_id,
        ]);
        
        
        MaintenanceFee::where('id', '!=', $rs->id)->update(['status' => '0']);
        
        return back()->with('card_fee', 'Membership Card Fee Updated Successfully');
    }
}
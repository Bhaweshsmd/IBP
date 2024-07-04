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

class TaxController extends Controller
{
    function index(Request $request)
    {
        $settings = GlobalSettings::first();
        return view('settings.taxes', ['data' => $settings]);
    }
    
    function list(Request $request)
    {
        $totalData =  Taxes::count();
        $rows = Taxes::orderBy('id', 'DESC')->get();

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
            $result = Taxes::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  Taxes::where(function ($query) use ($search) {
                $query->Where('tax_title', 'LIKE', "%{$search}%")
                    ->orWhere('value', 'LIKE', "%{$search}%");
            })->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Taxes::where(function ($query) use ($search) {
                $query->Where('tax_title', 'LIKE', "%{$search}%")
                    ->orWhere('value', 'LIKE', "%{$search}%");
            })->count();
        }
        $data = array();
        foreach ($result as $item) {

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
                                <input rel=' . $item->id . ' type="checkbox" class="onoff">se
                                <span class="slider round"></span>
                            </label>';
            }
            
            if(has_permission(session()->get('user_type'), 'view_taxes')){
                $edit = '<a data-taxtitle="' . $item->tax_title . '" data-type="' . $item->type . '" href="" data-value="' . $item->value . '"  class="mr-2 btn btn-primary text-white edit editBtn" rel=' . $item->id . ' ><i class="fa fa-edit"></i></a>';
            }else{
                $edit = '';
            }
            
            if(has_permission(session()->get('user_type'), 'delete_taxes')){
                $delete = '<a href="" class="mr-2 btn btn-danger text-white delete deleteBtn" rel=' . $item->id . ' ><i class="fa fa-trash"></i></a>';
            }else{
                $delete = '';
            }
            
            $action = $edit . $delete;

            $data[] = array(
                $item->tax_title,
                $type,
                $item->value,
                $onOff,
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
    
    function status($id, $value)
    {
        $item = Taxes::find($id);
        $item->status = $value;
        $item->save();

        return response()->json(['status' => true, 'message' => 'value changes successfully']);
    }

    function store(Request $request)
    {
        $item = new Taxes();
        $item->tax_title = $request->tax_title;
        $item->value = $request->value;
        $item->type = $request->type;
        $item->status = 1;
        $item->save();

        return GlobalFunction::sendSimpleResponse(true, 'item added successfully!');
    }
    
    function update(Request $request)
    {
        $item = Taxes::find($request->id);
        $item->tax_title = $request->tax_title;
        $item->value = $request->value;
        $item->type = $request->type;
        $item->save();

        return GlobalFunction::sendSimpleResponse(true, 'item edited successfully!');
    }
    
    function delete($id)
    {
        $item = Taxes::find($id);
        $item->delete();
        return back()->with(['settings_message' => 'Tax Deleted Successfully']);
    }
}
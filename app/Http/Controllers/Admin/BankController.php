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
use App\Models\BankDetail;

class BankController extends Controller
{
    function index()
    {
        return view('banks.index');
    }
    
    function list(Request $request)
    {
        $admin_detials = Admin::where('user_name',session()->get('user_name'))->first();
        
        $totalData =  BankDetail::where('user_id', $admin_detials->user_id)->where('user_type', 'admin')->count();
        $rows = BankDetail::where('user_id', $admin_detials->user_id)->where('user_type', 'admin')->orderBy('id', 'DESC')->get();

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
            $result = BankDetail::where('user_id', $admin_detials->user_id)->where('user_type', 'admin')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            
            $result = BankDetail::where('user_id', $admin_detials->user_id)->where('user_type', 'admin')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = BankDetail::where('user_id', $admin_detials->user_id)->where('user_type', 'admin')
                ->count();
                
            if(!$totalFiltered){
                $result =  BankDetail::where('user_id', $admin_detials->user_id)->where('user_type', 'admin')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
                
                $totalFiltered = BankDetail::where('user_id', $admin_detials->user_id)->where('user_type', 'admin')
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->count();
            }
        }
        
        $data = array();
        $j=1;
        foreach ($result as $item) {
            
            if($item->status == '1'){
                $status = 'Active';
            }else{
                $status = 'Inactive';
            }
            
            if(has_permission(session()->get('user_type'), 'edit_banks')){
                $edit = '<a href="' . route('edit.bank', $item->id) . '" class="mr-2 btn btn-primary text-white edit editBtn" rel=' . $item->id . ' ><i class="fa fa-edit faPostion"></i></a>';
            }else{
                $edit = '';
            }
            
            if(has_permission(session()->get('user_type'), 'delete_banks')){
                $delete = '<a href="" class="mr-2 btn btn-danger text-white delete deleteBtn" rel=' . $item->id . ' ><i class="fa fa-trash faPostion"></i></a>';
            }else{
                $delete = '';
            }
            
            $admin_detials = Admin::where('user_id', $item->user_id)->first();
            
            if (!empty($admin_detials)) {
                $userName = '<a href="' . route('admins.edit', $item->user_id) . '"><span class="badge bg-primary text-white">' . $admin_detials->first_name.' '.$admin_detials->last_name . '</span></a>';
            }

            $action = $edit.$delete;
            
            $data[] = array(
                $j++,
                $userName,
                $item->bank_name,
                $item->account_number,
                $item->account_holder,
                $item->swift_code,
                $status,
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
    
    function create()
    {
        $admin_detials = Admin::where('user_name',session()->get('user_name'))->first();
        
        return view('banks.create', ['admin_detials' => $admin_detials]);
    }
    
    function store(Request $request)
    {
        $admin_detials = Admin::where('user_name',session()->get('user_name'))->first();
        
        $bank = BankDetail::create([
            'user_id' => $admin_detials->user_id,
            'user_type' => 'admin',
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_holder' => $request->account_holder,
            'swift_code' => $request->swift_code,
            'status' => $request->status,
        ]);

        return redirect('banks')->with(['bank_message' => 'Bank Created Successfully']);
    }
    
    function edit($id)
    {
        $bank = BankDetail::find($id);
        $admin_detials = Admin::where('user_id', $bank->user_id)->first();

        return view('banks.edit', ['bank' => $bank, 'admin_detials' => $admin_detials]);
    }
    
    function update(Request $request, $id)
    {
        $bank = BankDetail::where('id', $id)->update([
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_holder' => $request->account_holder,
            'swift_code' => $request->swift_code,
            'status' => $request->status,
        ]);

        return redirect('banks')->with(['bank_message' => 'Bank Updated Successfully']);
    }
    
    function delete($id)
    {
        $bank = BankDetail::find($id);
        $bank->delete();

        return redirect('banks')->with(['bank_message' => 'Bank Deleted Successfully']);
    }
}
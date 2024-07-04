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
use Illuminate\Support\Carbon;
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
use App\Models\Role;
use App\Models\Permission;
use App\Models\PermissionRole;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Card;
use App\Models\Users;
use App\Models\Country;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::get();
        return view('countries.index', ['data' => $countries]);
    }
    
    public function store(Request $request)
    {
        $rules = [
            'short_name'=>  'required',
            'name'=>  'required',
            'iso3'=>  'required',
            'number_code'=>  'required',
            'phone_code'=>  'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        $countries                = new Country();
        $countries->short_name    = $request->short_name;
        $countries->name          = $request->name;
        $countries->iso3          = $request->iso3;
        $countries->number_code   = $request->number_code;
        $countries->phone_code    = $request->phone_code;
        $countries->currency_code = $request->currency_code;
        $countries->status        = $request->status;
        $countries->save();
        
        return redirect('countries')->with(['country_message' => 'Country Added Successfully']);
    }
    
    function list(Request $request)
    {
        $totalData =  Country::count();
        $rows = Country::orderBy('name', 'ASC')->get();

        $result = $rows;

        $columns = array(
            0 => 'id',
            1 => 'short_name',
            2 => 'name',
            3 => 'iso3',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalFiltered = $totalData;
        if (empty($request->input('search.value'))) {
            $result = Country::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  Country::where(function ($query) use ($search) {
                $query->where('short_name', 'LIKE', "%{$search}%")
                    ->orWhere('name', 'LIKE', "%{$search}%")
                    ->orWhere('iso3', 'LIKE', "%{$search}%")
                    ->orWhere('number_code', 'LIKE', "%{$search}%")
                    ->orWhere('phone_code', 'LIKE', "%{$search}%")
                    ->orWhere('currency_code', 'LIKE', "%{$search}%")
                    ->orWhere('status', 'LIKE', "%{$search}%");
            })
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();
                
            $totalFiltered = Country::where(function ($query) use ($search) {
                $query->where('short_name', 'LIKE', "%{$search}%")
                    ->orWhere('name', 'LIKE', "%{$search}%")
                    ->orWhere('iso3', 'LIKE', "%{$search}%")
                    ->orWhere('number_code', 'LIKE', "%{$search}%")
                    ->orWhere('phone_code', 'LIKE', "%{$search}%")
                    ->orWhere('currency_code', 'LIKE', "%{$search}%")
                    ->orWhere('status', 'LIKE', "%{$search}%");
            })
            ->count();
        }
        $data = array();

        foreach ($result as $k=>$item) 
        {
            if($item->status == '1'){
                $status = 'Active';
            }else{
                $status = 'Inactive';
            }
            
            if(has_permission(session()->get('user_type'), 'view_country')){
                $edit = '<a data-shortname="' . $item->short_name . '" data-name="' . $item->name . '" data-iso3="' . $item->iso3 . '" data-numbercode="' . $item->number_code . '" data-phonecode="' . $item->phone_code . '" data-currencycode="' . $item->currency_code . '" data-status="' . $item->status . '" href="" class="mr-2 btn btn-primary text-white edit editBtn" rel=' . $item->id . ' ><i class="fa fa-edit faPostion"></i></a>';
            }else{
                $edit = '';
            }
            
            if(has_permission(session()->get('user_type'), 'delete_country')){
                $delete = '<a href="" class="mr-2 btn btn-danger text-white delete deleteBtn" rel=' . $item->id . ' ><i class="fa fa-trash faPostion"></i></a>';
            }else{
                $delete = '';
            }
            
            $action = $edit.$delete;

            $check_role = Role::where('id', $item->user_type)->first();
            
            $data[] = array(
                ++$k,
                $item->name,
                $item->short_name,
                $item->iso3,
                $item->number_code,
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

    public function update(Request $request)
    {
        $rules = [
            'short_name'=>  'required',
            'name'=>  'required',
            'iso3'=>  'required',
            'number_code'=>  'required',
            'phone_code'=>  'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        $id = $request->id;
        
        $countries                = Country::find($id);
        $countries->short_name    = $request->short_name;
        $countries->name          = $request->name;
        $countries->iso3          = $request->iso3;
        $countries->number_code   = $request->number_code;
        $countries->phone_code    = $request->phone_code;
        $countries->currency_code = $request->currency_code;
        $countries->status        = $request->status;
        $countries->save();
        
        return GlobalFunction::sendSimpleResponse(true, 'Country edited successfully!');
    }
    
    public function delete($id)
    {
        Country::where('id', $id)->delete();

        return redirect('countries')->with(['country_message' => 'Country Deleted Successfully']);
    }
}

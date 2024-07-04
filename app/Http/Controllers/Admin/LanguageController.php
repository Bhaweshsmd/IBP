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

class LanguageController extends Controller
{
    public function index()
    {    
        $languages = Language::get();
        $settings = GlobalSettings::first();
        return view('languages.index', [
            'settings' => $settings,'languages'=>$languages
        ]);
    }
    
    function list(Request $request)
    {
       $totalData =  Language::count();
        $rows = Language::orderBy('id', 'DESC')->get();
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
            $result = Language::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  Language::where(function ($query) use ($search) {
                $query->Where('name', 'LIKE', "%{$search}%");
            })->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Language::where(function ($query) use ($search) {
                $query->Where('name', 'LIKE', "%{$search}%");
            })->count();
        }
        $data = array();
        foreach ($result as $k=>$item) {
            
            if(has_permission(session()->get('user_type'), 'view_languages')){
                $edit = '<a data-name="' . $item->name . '" data-short_name="' . $item->short_name . '" data-status="' . $item->status . '" data-maxDiscAmount="' . $item->name . '" data-coupon="' . $item->name . '" data-percentage="' . $item->name . '" href="" class="mr-2 btn btn-primary text-white edit editBtn" rel=' . $item->id . ' ><i class="fa fa-edit"></i></a>';
            }else{
                $edit = '';
            }
            
            if(has_permission(session()->get('user_type'), 'delete_languages')){
                $delete = '<a href="" class="mr-2 btn btn-danger text-white delete deleteBtn" rel=' . $item->id . ' ><i class="fa fa-trash"></i></a>';
            }else{
                $delete = '';
            }
            
            $action = $edit  . $delete;
            
            $imgUrl = "http://placehold.jp/150x150.png";
            if ($item->flag == null) {
                $img = '<img src="http://placehold.jp/150x150.png" width="50" height="50">';
            } else {
                $imgUrl = GlobalFunction::createMediaUrl($item->flag);
                $img = '<img src="' . $imgUrl . '" width="50" height="50">';
            }

            $data[] = array(
                ++$k,
                $item->name,
                $item->short_name,
                $img,
                $item->status,
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
        $rules = [
            'name' => 'required',
            'short_name' => 'required',
            'status' => 'required',
            'flag' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
             return back()->with(['status' => false, 'message' => $msg])->withInput();
        }
        
        $langauge = new Language();
        $langauge->name = $request->name;
        $langauge->short_name = $request->short_name;
        $langauge->status = $request->status;
        $langauge->flag = GlobalFunction::saveFileAndGivePath($request->flag);
        $langauge->save();
        return back()->with(['lang_message' => 'Langauge Added Successfully']);
    }
    
    function update(Request $request)
    {
        $langauge = Language::find($request->id);
        if($request->name){
           $langauge->name = $request->name;
        }
        if($request->short_name){
            $langauge->short_name = $request->short_name;
          }
        if($request->status){
             $langauge->status = $request->status;
        }
        if($request->flag){
          $langauge->flag = GlobalFunction::saveFileAndGivePath($request->flag);
        }
        $langauge->save();

        return GlobalFunction::sendSimpleResponse(true, 'Language edited successfully!');
    }
    
    function delete($id)
    {
        $coupon = Language::find($id);
        $coupon->delete();
        return back()->with(['lang_message' => 'Langauge Deleted Successfully']);
    }
    
    public function contents()
    {    
        $languages = Language::get();
        $settings = GlobalSettings::first();
        return view('languages.content', [
            'settings' => $settings,'languages'=>$languages
        ]);
    }
    
    function contents_list(Request $request)
    {
        $totalData =  LanguageContent::count();
        $rows = LanguageContent::orderBy('id', 'DESC')->get();
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
            $result = LanguageContent::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  LanguageContent::where(function ($query) use ($search) {
                $query->Where('string', 'LIKE', "%{$search}%")
                    ->orWhere('en', 'LIKE', "%{$search}%")
                    ->orWhere('pap', 'LIKE', "%{$search}%")
                    ->orWhere('nl', 'LIKE', "%{$search}%");
            })->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = LanguageContent::where(function ($query) use ($search) {
                $query->Where('string', 'LIKE', "%{$search}%")
                    ->orWhere('en', 'LIKE', "%{$search}%")
                    ->orWhere('pap', 'LIKE', "%{$search}%")
                    ->orWhere('nl', 'LIKE', "%{$search}%");
            })->count();
        }
        $data = array();
        foreach ($result as $k=>$item) {
            
            if(has_permission(session()->get('user_type'), 'view_content')){
                $edit = '<a data-string="' . $item->string . '" data-en="' . $item->en . '" data-status="' . $item->active . '" data-pap="' . $item->pap . '" data-nl="' . $item->nl . '"  href="" class="mr-2 btn btn-primary text-white edit editBtn" rel=' . $item->id . ' ><i class="fa fa-edit"></i></a>';
            }else{
                $edit = '';
            }
            
            if(has_permission(session()->get('user_type'), 'delete_content')){
                $delete = '<a href="" class="mr-2 btn btn-danger text-white delete deleteBtn" rel=' . $item->id . ' ><i class="fa fa-trash"></i></a>';
            }else{
                $delete = '';
            }
            
            $action = $edit  . $delete;
            
             if($item->active){
                  $status="Active";
             }else{
                  $status="Inactive";
             }

            $data[] = array(
                ++$k,
                $item->string,
                $item->en,
                $item->pap,
                $item->nl,
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
    
    function contents_store(Request $request)
    {  
        $rules = [
            'string' => 'required|unique:language_contents',
            'en' => 'required',
            'active'=>'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        $langauge = new LanguageContent();
        $langauge->string = $request->string;
        $langauge->en = $request->en;
        $langauge->pap = $request->pap;
        $langauge->nl = $request->nl;
         $langauge->active = $request->active;
        $langauge->save();
        return GlobalFunction::sendSimpleResponse(true, 'Langauge Content Added Successfully!');
    }
    
    function contents_update(Request $request)
    {
        $rules = [
            'string' => 'required|unique:language_contents,string,'.$request->id,
            'en' => 'required',
            'active'=>'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
           return response()->json(['status' => false, 'message' => $msg]);
        }
        
        $langauge = LanguageContent::find($request->id);
        if($request->string){
           $langauge->string = $request->string;
        }
        if($request->en){
            $langauge->en = $request->en;
          }
        if($request->pap){
             $langauge->pap = $request->pap;
        }
        if($request->nl){
          $langauge->nl = $request->nl;
        }
        if($request->active){
          $langauge->active = $request->active;
        }
        $langauge->save();

        return GlobalFunction::sendSimpleResponse(true, 'Language Content edited successfully!');
    }
    
    function contents_delete($id)
    {
        $coupon = LanguageContent::find($id);
        $coupon->delete();
        return back()->with(['lang_message' => 'Langauge Content Deleted Successfully']);
    }
}
<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Banners;
use App\Models\Bookings;
use App\Models\Constants;
use App\Models\GlobalFunction;
use App\Models\GlobalSettings;
use App\Models\SalonCategories;
use App\Models\Salons;
use App\Models\Services;
use App\Models\UserNotification;
use App\Models\Users;
use App\Models\UserWalletRechargeLogs;
use App\Models\UserWalletStatements;
use App\Models\UserWithdrawRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Twilio\Jwt\ClientToken;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\Services\TwilioService;
use DB;
use App\Jobs\SendEmail;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Jobs\SendNotification;
use App\Models\Fee;
use App\Models\EventType;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use App\Models\EmailTemplate;
use App\Models\SalonBookingSlots;
use App\Models\Admin;
use App\Models\Card;
use App\Models\Device;
use App\Models\Language;
use App\Models\CardsTransaction;

class UserController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService, Auth $auth)
    {
        $this->twilioService = $twilioService;
    }
    
    function index()
    {
        return view('users.index');
    }
    
    function list(Request $request)
    {
        $totalData =  Users::count();
        $rows = Users::orderBy('id', 'DESC')->get();

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
             $search = $request->input('search.value');
            $result = Users::where(function ($query) use ($search) {
                $query->Where('identity', 'LIKE', "%{$search}%")
                    ->orWhere('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhere('profile_id', 'LIKE', "%{$search}%")
                    ->orWhere('phone_number', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('user_type', 'LIKE', "%{$search}%");
            })->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  Users::where(function ($query) use ($search) {
                $query->Where('identity', 'LIKE', "%{$search}%")
                    ->orWhere('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhere('profile_id', 'LIKE', "%{$search}%")
                    ->orWhere('phone_number', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('user_type', 'LIKE', "%{$search}%");
            })->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Users::where(function ($query) use ($search) {
                $query->Where('identity', 'LIKE', "%{$search}%")
                    ->orWhere('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhere('profile_id', 'LIKE', "%{$search}%")
                    ->orWhere('phone_number', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('user_type', 'LIKE', "%{$search}%");
            })->count();
        }
        $data = array();
        $i=1;
        foreach ($result as $item) {

            if ($item->profile_image == null) {
                $image = '<img src="https://placehold.jp/150x150.png" width="50" height="50">';
            } else {
                $imgUrl = GlobalFunction::createMediaUrl($item->profile_image);
                $image = '<img src="' . $imgUrl . '" width="50" height="50">';
            }
            
            $fullname=ucfirst($item->first_name.' '.$item->last_name);
            
            $contact=$item->phone_number.'<br>'.$item->email;
            if($item->user_type == '0'){
                $user_type = "Bonaire Resident";
            }else{
                $user_type = "Non-Resident";
            }

            $bookingCount = Bookings::where('user_id', $item->id)->count();
            
            if(has_permission(session()->get('user_type'), 'view_user')){
                $view = '<a href="' . route('users.profile', $item->id) . '" class="mr-2 btn btn-info text-white viewBtn " data-toggle="tooltip" data-placement="top" title="View" rel=' . $item->id . ' ><i class="fa fa-eye faPostion"></i></a>';
            }else{
                $view = '';
            }
            
            if(has_permission(session()->get('user_type'), 'view_user')){
                $edit = '<a href="' . route('users.edit', $item->id) . '" class="mr-2 btn btn-primary text-white  editBtn" rel=' . $item->id . ' ><i class="fa fa-edit faPostion"></i></a>';
            }else{
                $edit = '';
            }
            
            if(has_permission(session()->get('user_type'), 'delete_user')){
                if ($item->is_block == 0) {
                    $block = '<a href="" class="mr-2 btn btn-danger text-white block blockBtn" rel=' . $item->id . ' ><i class="fa fa-ban faPostion"></i></a>';
                } else {
                    $block = '<a href="" class="mr-2 btn btn-success text-white unblock unblockBtn " rel=' . $item->id . ' ><i class="fa fa-check faPostion"></i></a>';
                }
            }else{
                $block = '';
            }

            $action = $view . $edit . $block;
            
            $profileID= '<a href="' . route('users.profile', $item->id) . '" class=" " rel=' . $item->id . ' >' . $item->profile_id . '</a>';
            $fullname= '<a href="' . route('users.profile', $item->id) . '" class=" " rel=' . $item->id . ' >' . $fullname. '</a>';
            $data[] = array(
                $i++,
                $profileID,
                $image,
                $fullname,
                $item->email,
                $item->formated_number,
                $user_type,
                $item->identity,
                $bookingCount,
                ucfirst($item->added_by??'N/A'),
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
        $countries = DB::table('countries')->get();
        return view('users.create',['countries'=>$countries]);
    }
    
    function store(Request $request)
    {  
        $rules = [
            'first_name'=>  'required',
            'last_name' => 'required',
            'email' => 'required|unique:users',
            'country' => 'required',
            'phone' => 'required|unique:users,phone_number',
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return back()->with(['user_error_message' => $msg]);
        }

        try{    

            $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
            $pass = array();
            $alphaLength = strlen($alphabet) - 1;
            for ($i = 0; $i < 8; $i++) {
                $n = rand(0, $alphaLength);
                $pass[] = $alphabet[$n];
            }
            
            $user_password = implode($pass);
            $formated_number = $request->country.$request->phone;
            
            $admin_detials = Admin::where('user_name',session()->get('user_name'))->first();
            $add_by = $admin_detials->first_name.' '.$admin_detials->last_name??'';
            
            if($request->user_type == '1'){
                $identity = $request->passport_number;
            }else{
                $identity = $request->local_number;
            }
            
            $user = new Users();  
            $user->identity = $identity;
            $user->fullname = GlobalFunction::cleanString($request->first_name);
            $user->first_name = GlobalFunction::cleanString($request->first_name);
            $user->last_name = GlobalFunction::cleanString($request->last_name);
            $user->country_code = $request->country;
            $user->phone_number = $request->phone;
            $user->formated_number = $formated_number;
            $user->email = $request->email;
            $user->device_type = null;
            $user->device_token = null;
            $user->login_type = null;
            $user->user_type = $request->user_type;
            $user->firebase_uid = $createdUser->uid??'';
            $user->password = Hash::make($user_password);
            $user->added_by=$add_by;
            $user->is_verified=0;
            if ($request->has('profile_image')) {
                $user->profile_image = GlobalFunction::saveFileAndGivePath($request->profile_image);
            }
            $user->save();
            
            $user = Users::where('id', $user->id)->withCount('bookings')->first();
            $profile_id = 'IBPC'.$user->id;
            Users::where('id',$user->id)->update(['profile_id'=>$profile_id]);
            
            $user_name = $user->first_name.' '.$user->last_name;
            
            $check_device = Device::where('user_id', $user->id)->first();
            if(!empty($check_device->language)){
                $act_lang = $check_device->language;
            }else{
                $act_lang = '1';
            }
            
            $email_template = EmailTemplate::find(11);
            if($act_lang == '1'){
                $subject = $email_template->email_subjects;
                $content = $email_template->email_content;
            }elseif($act_lang == '2'){
                $subject = $email_template->email_subject_pap;
                $content = $email_template->email_content_pap;
            }elseif($act_lang == '3'){
                $subject = $email_template->email_subject_nl;
                $content = $email_template->email_content_nl;
            }
            
            $content = str_replace(["{user}","{email}","{phone}","{password}"], [$user_name,$user->email,$user->formated_number,$user_password],$content);
            $details=[         
                "subject"=>$subject ,
                "message"=>$content,
                "to"=>$user->email,
            ];
            send_email($details);
        }catch(\Exception $e){
            return $data=['status' => false, 'message' =>$e->getMessage()];
        }
        
        return redirect('users')->with(['user_message' => 'Customer Created Successfully']);
    }
    
    function edit($id)
    {
        $countries = DB::table('countries')->get();
        $user = Users::find($id);
        return view('users.edit',['countries'=>$countries,'user'=>$user]);
    }
    
    function update(Request $request, $id)
    {
        $formated_number = $request->country.$request->phone;
        
        if($request->user_type == '1'){
            $identity = $request->passport_number;
        }else{
            $identity = $request->local_number;
        }
        
        $user = Users::find($id);  
        $user->identity = $identity;
        $user->fullname = GlobalFunction::cleanString($request->first_name);
        $user->first_name = GlobalFunction::cleanString($request->first_name);
        $user->last_name = GlobalFunction::cleanString($request->last_name);
        $user->country_code = $request->country;
        $user->phone_number = $request->phone;
        $user->formated_number = $formated_number;
        $user->email = $request->email;
        $user->device_type = null;
        $user->device_token = null;
        $user->login_type = null;
        $user->user_type = $request->user_type;
        $user->firebase_uid = $createdUser->uid??'';
        if($request->password){
            $user->password = Hash::make($request->password);
        }else{
            $user->password = $user->password;
        }
        if ($request->has('profile_image')) {
            $user->profile_image = GlobalFunction::saveFileAndGivePath($request->profile_image);
        }
        $user->save();
        
        return redirect('users')->with(['user_message' => 'Customer Updated Successfully']);
    }
    
    function block($id)
    {
        $user = Users::find($id);
        $user->is_block = 1;
        $user->save();

        return redirect('users')->with(['user_message' => 'Customer Blocked Successfully']);
    }
    
    function unblock($id)
    {
        $user = Users::find($id);
        $user->is_block = 0;
        $user->save();

        return redirect('users')->with(['user_message' => 'Customer Unblocked Successfully']);
    }
    
    function profile($id)
    {
        $user = Users::find($id);
        $settings = GlobalSettings::first();
        $totalBookings = Bookings::where('user_id', $id)->count();
        
        if($user->user_type==1){
            $services = Services::select(['id','latitude','logintude','salon_id','category_id','rating','thumbnail','title','service_time','service_number','about','rules','qauntity','foreiner_price as price','foreiner_price_per_day as price_per_day','foreiner_discount as discount'])->where('status',1)->get();
        }else{
            $services = Services::select(['id','latitude','logintude','salon_id','category_id','thumbnail','rating','title','service_time','service_number','about','rules','qauntity','price','price_per_day','discount'])->where('status',1)->get(); 
        }
        
        $ibpcards = Card::where('assigned_to', $id)->first();
        
        return view('users.profile', [
            'user' => $user,
            'ibpcards'=>$ibpcards,
            'settings' => $settings,
            'totalBookings' => $totalBookings,
            'services'=>$services,
        ]);
    }
    
    function bookings_list(Request $request)
    {
        $totalData =  Bookings::where('user_id', $request->userId)->count();
        $rows = Bookings::where('user_id', $request->userId)
            ->with(['user', 'salon'])->orderBy('id', 'DESC')->get();
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
            $result = Bookings::where('user_id', $request->userId)
                ->with(['user', 'salon'])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  Bookings::where('user_id', $request->userId)
                ->with(['user', 'salon'])
                ->where(function ($query) use ($search) {
                    $query->Where('booking_id', 'LIKE', "%{$search}%")
                        ->orWhere('payable_amount', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Bookings::where('user_id', $request->userId)
                ->with(['user', 'salon'])
                ->where(function ($query) use ($search) {
                    $query->Where('booking_id', 'LIKE', "%{$search}%")
                        ->orWhere('payable_amount', 'LIKE', "%{$search}%");
                })
                ->count();
        }
        $data = array();
        foreach ($result as $k=>$item) {
            
            if(has_permission(session()->get('user_type'), 'view_bookings')){
                $view = '<a href="' . route('bookings.view', $item->id) . '" class="mr-2 btn btn-primary text-white viewBtn " rel=' . $item->id . ' ><i class="fa fa-eye faPostion"></i></a>';
            }else{
                $view = '';
            }

            $action = $view;

            $salon = '';
            if ($item->salon != null) {
                $salon = '<a href="' . route('platforms.edit', $item->salon->id) . '"><span class="badge bg-primary text-white">' . $item->salon->salon_name . '</span></a>';
            }

            $status = "";
            if ($item->status == Constants::orderPlacedPending) {
                $status = '<span class="badge bg-warning text-white"rel="' . $item->id . '">' . __('Pending') . '</span>';
            } else if ($item->status == Constants::orderAccepted) {
                $status = '<span class="badge bg-primary text-white" rel="' . $item->id . '">' . __('Accepted') . '</span>';
            } else if ($item->status == Constants::orderCompleted) {
                $status = '<span class="badge bg-success text-white"rel="' . $item->id . '">' . __('Completed') . '</span>';
            } else if ($item->status == Constants::orderDeclined) {
                $status = '<span class="badge bg-danger text-white"rel="' . $item->id . '">' . __('Declined') . '</span>';
            } else if ($item->status == Constants::orderCancelled) {
                $status = '<span class="badge bg-danger text-white"rel="' . $item->id . '">' . __('Cancelled') . '</span>';
            }

            $dateTime =  $item->date . '<br>' . GlobalFunction::formateTimeString($item->time);
            $payableAmount = $settings->currency . $item->payable_amount;

            $data[] = array(
                ++$k,
                $item->booking_id,
                $status,
                $dateTime,
                $settings->currency .  number_format($item->service_amount, 2, '.', ','),
                $settings->currency . number_format($item->discount_amount, 2, '.', ','),
                $settings->currency . number_format($item->subtotal, 2, '.', ','),
                $settings->currency . number_format($item->total_tax_amount, 2, '.', ','),
                $payableAmount,
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
    
    function wallet_statements(Request $request)
    {
        $totalData =  UserWalletStatements::where('user_id', $request->userId)->count();
        $rows = UserWalletStatements::where('user_id', $request->userId)->orderBy('id', 'DESC')->get();
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
            $result = UserWalletStatements::where('user_id', $request->userId)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  UserWalletStatements::where('user_id', $request->userId)
                ->where(function ($query) use ($search) {
                    $query->Where('booking_id', 'LIKE', "%{$search}%")
                        ->orWhere('transaction_id', 'LIKE', "%{$search}%")
                        ->orWhere('summary', 'LIKE', "%{$search}%")
                        ->orWhere('created_at', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = UserWalletStatements::where('user_id', $request->userId)
                ->where(function ($query) use ($search) {
                    $query->Where('booking_id', 'LIKE', "%{$search}%")
                        ->orWhere('transaction_id', 'LIKE', "%{$search}%")
                        ->orWhere('summary', 'LIKE', "%{$search}%")
                        ->orWhere('created_at', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%");
                })
                ->count();
        }
        $data = array();
        foreach ($result as $k=>$item) {

            $cr_dr = $item->cr_or_dr;
            $icon = '';
            $textClass = '';
            $crDrBadge = '';

            if ($cr_dr == Constants::credit) {
                $icon =  '<i class="fas fa-plus-circle m-1 ic-credit"></i>';
                $textClass = 'text-credit';
                $crDrBadge = '<span  class="badge bg-success text-white ">' . __("Credit") . '</span>';
            } else {
                $icon =  '<i class="fas fa-minus-circle m-1 ic-debit"></i>';
                $textClass = 'text-debit';
                $crDrBadge = '<span  class="badge bg-danger text-white ">' . __("Debit") . '</span>';
            }
            $transaction = $icon . '<span class=' . $textClass . '>' . $item->transaction_id . '</span>';

            $data[] = array(
                ++$k,
                $transaction,
                $settings->currency . number_format($item->amount, 2, '.', ','),
                $crDrBadge,
                GlobalFunction::formateTimeString($item->created_at),
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
    
    function card_statements(Request $request)
    {
        $totalData =  CardsTransaction::where('user_id', $request->userId)->count();
        $rows = CardsTransaction::where('user_id', $request->userId)->orderBy('id', 'DESC')->get();
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
            $result = CardsTransaction::where('user_id', $request->userId)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  CardsTransaction::where('user_id', $request->userId)
                ->where(function ($query) use ($search) {
                    $query->Where('booking_id', 'LIKE', "%{$search}%")
                        ->orWhere('transaction_id', 'LIKE', "%{$search}%")
                        ->orWhere('summary', 'LIKE', "%{$search}%")
                        ->orWhere('created_at', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = CardsTransaction::where('user_id', $request->userId)
                ->where(function ($query) use ($search) {
                    $query->Where('booking_id', 'LIKE', "%{$search}%")
                        ->orWhere('transaction_id', 'LIKE', "%{$search}%")
                        ->orWhere('summary', 'LIKE', "%{$search}%")
                        ->orWhere('created_at', 'LIKE', "%{$search}%")
                        ->orWhere('amount', 'LIKE', "%{$search}%");
                })
                ->count();
        }
        $data = array();
        foreach ($result as $k=>$item) {

            $cr_dr = $item->type;
            $icon = '';
            $textClass = '';
            $crDrBadge = '';

            if ($cr_dr == "Topup") {
                $icon =  '<i class="fas fa-plus-circle m-1 ic-credit"></i>';
                $textClass = 'text-credit';
                $crDrBadge = '<span  class="badge bg-success text-white ">' . __("Card Topup") . '</span>';
            } else {
                $icon =  '<i class="fas fa-minus-circle m-1 ic-debit"></i>';
                $textClass = 'text-debit';
                $crDrBadge = '<span  class="badge bg-danger text-white ">' . __("Spent on Booking") . '</span>';
            }
            $transaction = $icon . '<span class=' . $textClass . '>' . $item->transaction_id . '</span>';
            
            $check_card = Card::where('id', $item->card_id)->first();

            $data[] = array(
                ++$k,
                $transaction,
                !empty($check_card->card_number) ? chunk_split($check_card->card_number, 4, ' ') : '-',
                $settings->currency . number_format($item->amount, 2, '.', ','),
                $crDrBadge,
                GlobalFunction::formateTimeString($item->created_at),
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

    function recharge_logs(Request $request)
    {
        $userId = $request->userId;
        $totalData =  UserWalletRechargeLogs::where('user_id', $userId)->count();
        $rows = UserWalletRechargeLogs::where('user_id', $userId)->orderBy('id', 'DESC')->get();
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
            $result = UserWalletRechargeLogs::where('user_id', $userId)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  UserWalletRechargeLogs::where('user_id', $userId)
                ->where(function ($query) use ($search) {
                    $query->Where('amount', 'LIKE', "%{$search}%")
                        ->orWhere('transaction_summary', 'LIKE', "%{$search}%")
                        ->orWhere('transaction_id', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = UserWalletRechargeLogs::where('user_id', $userId)
                ->where(function ($query) use ($search) {
                    $query->Where('amount', 'LIKE', "%{$search}%")
                        ->orWhere('transaction_summary', 'LIKE', "%{$search}%")
                        ->orWhere('transaction_id', 'LIKE', "%{$search}%");
                })
                ->count();
        }
        $data = array();
        foreach ($result as $k=>$item) {

            $gateway = GlobalFunction::detectPaymentGateway($item->gateway);
            
            if($gateway=="Stripe"){
                $gateway="Debit/Credit Card";
            }

            $data[] = array(
                ++$k,
                $settings->currency . $item->amount,
                $gateway,
                $item->transaction_id,
                GlobalFunction::formateTimeString($item->created_at),
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
}

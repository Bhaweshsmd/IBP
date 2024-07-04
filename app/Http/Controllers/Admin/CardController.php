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
use App\Models\Fee;
use App\Models\NotificationTemplate;
use App\Models\SupportTicket;
use App\Models\Role;
use App\Models\Permission;
use App\Models\PermissionRole;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Card;
use Milon\Barcode\DNS1D;
use Illuminate\Support\Str;
use App\Models\CardTopup;
use Carbon\Carbon;
use App\Models\CardsTransaction;
use App\Models\CardFee;
use App\Models\RevenueSetting;
use App\Models\PlatformData;
use App\Models\Users;
use App\Jobs\SendEmail;
use App\Models\EmailTemplate;
use App\Models\AdminEmailTemplate;
use App\Jobs\SendNotification;
use App\Models\CardmembershipFee;
use App\Models\CardassignFee;
use App\Models\MaintenanceFee;
use App\Jobs\SendAdminNotification;
use App\Models\AdminNotificationTemplate;
use App\Models\Device;

class CardController extends Controller
{
    public function index()
    {
        $cards = Card::get();
        $assigncards = Card::whereNull('assigned_to')->get();
        $users = User::where('is_block', '!=', '1')->whereNull('card_id')->orderBy('first_name', 'asc')->get();
        $languages = Language::get();
        $settings = GlobalSettings::first();
        
        if (isset($_GET['btn']))
        {
            if (empty($_GET['from']))
            {
                $from = null;
                $to   = null;
                
                $assign_card_earning = Card::whereNotNull('assigned_to')->sum('assign_fee');
                $topup_card_earning = CardTopup::sum('amount');
            }
            else
            {
                $from = Carbon::parse($_GET['from'])->format('Y-m-d');
                $to   = Carbon::parse($_GET['to'])->format('Y-m-d');
                
                $assign_card_earning = Card::whereNotNull('assigned_to')->whereDate('assigned_on', '>=', $from)->whereDate('assigned_on', '<=', $to)->sum('assign_fee');
                $topup_card_earning = CardTopup::whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)->sum('amount');
            }
        }
        else
        {
            $from = null;
            $to   = null;
            
            $assign_card_earning = Card::whereNotNull('assigned_to')->sum('assign_fee');
            $topup_card_earning = CardTopup::sum('amount');
        }
        
        return view('cards.index', ['data' => $cards, 'assigncards' => $assigncards, 'users' => $users, 'languages' => $languages, 'assign_card_earning' => $assign_card_earning, 'topup_card_earning' => $topup_card_earning, 'settings' => $settings, 'from' => $from, 'to' => $to]);
    }
    
    public function create()
    {
        return view('admins.create', ['roles' => $roles]);
    }
    
    public function store(Request $request)
    {
        $number_cards = $request->number_cards;
        $language = $request->language;
        
        $admin = DB::table('admin_user')->where('user_name', session()->get('user_name'))->first();

        for($i = 0; $i < $number_cards; $i++) {
            $card_number = rand(100000000000,999999999999);
            
            $checkcard = Card::where('card_number', $card_number)->first();
            if(empty($checkcard)){
                $card               = new Card();
                $card->card_number  = $card_number;
                $card->generated_by = $admin->user_id;
                $card->language     = $language;
                $card->save();
            }
        }
    
        return back()->with(['card_message' => 'Cards Generated Successfully']);
    }
    
    function list(Request $request)
    {
        $totalData =  Card::count();
        $rows = Card::orderBy('id', 'DESC')->get();

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
            $result = Card::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  Card::where(function ($query) use ($search) {
                    $query->Where('card_number', 'LIKE', "%{$search}%")
                        ->orWhere('status', 'LIKE', "%{$search}%")
                        ->orWhere('issue_status', 'LIKE', "%{$search}%")
                        ->orWhere('language', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Card::where(function ($query) use ($search) {
                    $query->Where('card_number', 'LIKE', "%{$search}%")
                        ->orWhere('status', 'LIKE', "%{$search}%")
                        ->orWhere('issue_status', 'LIKE', "%{$search}%")
                        ->orWhere('language', 'LIKE', "%{$search}%");
                })
                ->count();
           
             if(!$totalFiltered){
                    
                   $users_ids= Users::where('profile_id','LIKE', "%{$search}%")
                    ->orWhere('first_name', 'LIKE', "%{$search}%")
                   ->orWhere('last_name', 'LIKE', "%{$search}%")
                   ->orWhere('phone_number', 'LIKE', "%{$search}%")
                   ->orWhere('email', 'LIKE', "%{$search}%")
                    ->pluck('id');
                  
                 $result =  Card::whereIn('assigned_to',$users_ids)
                ->orWhere('card_number', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Card::whereIn('assigned_to',$users_ids)
                ->orWhere('card_number', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->count();
            }        
                  
        }
        $data = array();

        $settings = GlobalSettings::first();
        
        foreach ($result as $k=>$item) 
        {
            $user = DB::table('users')->where('id', $item->assigned_to)->first();
            $cashier = DB::table('admin_user')->where('user_id', $item->assigned_by)->first();
            $language = DB::table('languages')->where('id', $item->language)->first();
            
            $imgUrl = asset('public/cards/englishfront.PNG');
            $image = '<img src="' . $imgUrl . '" style="width: 50px; height: auto;">';
            
            if($item->status == '1'){
                $status = 'Active';
            }elseif($item->status == '2'){
                $status = 'Inactive';
            }else{
                $status = 'Blocked';
            }
            
            if(!empty($item->assigned_to)){
                if(!empty($user)){
                    $username = $user->first_name.' '.$user->last_name;
                    $contact = $user->formated_number.'<br>'.$user->email;
                }else{
                    $username = 'N/A';
                    $contact = 'N/A';
                }
            }else{
                $username = 'N/A';
                $contact = 'N/A';
            }
            
            if(!empty($item->assigned_by)){
                if(!empty($cashier)){
                    $cashierrname = $cashier->first_name.' '.$cashier->last_name;
                }else{
                    $cashierrname = 'N/A';
                }
            }else{
                $cashierrname = 'N/A';
            }
            
            if(has_permission(session()->get('user_type'), 'view_cards')){
                $view = '<a href="' . route('cards.edit', $item->id) . '" class="mr-2 btn btn-info text-white viewBtn" rel=' . $item->id . ' ><i class="fa fa-eye"></i></a>';
            }else{
                $view = '';
            }
            
            $action = $view;
            
            $data[] = array(
                ++$k,
                $image,
                chunk_split($item->card_number, 4, ' '),
                $status,
                $settings->currency . number_format($item->balance, 2, '.', ','),
                $username,
                $contact,
                $cashierrname,
                !empty($item->assigned_on) ? Carbon::parse($item->assigned_on)->format('d-M-Y') : 'N/A',
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
    
    public function edit($id)
    {
        $settings = GlobalSettings::first();
        
        $card = Card::find($id);

        return view('cards.edit', [
            'card' => $card
        ]);
    }
    
    public function status_update(Request $request, $id)
    {
        $card         = Card::find($id);
        $card->status = $request->status;
        $card->save();
        
        Session::flash('message', 'Card Status Updated successfully.');
        return back();
    }
    
    public function card_topups()
    {
        $topups = CardTopup::get();
        $users = User::get();
        $cards = Card::whereNotNull('assigned_to')->get();
        return view('cards.topups', ['data' => $topups, 'users' => $users, 'cards' => $cards]);
    }
    
    function card_topups_list(Request $request)
    {
        $totalData =  CardTopup::count();
        $rows = CardTopup::orderBy('id', 'DESC')->get();

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
            $result = CardTopup::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  CardTopup::where(function ($query) use ($search) {
                    $query->Where('payment_type', 'LIKE', "%{$search}%")
                        ->orWhere('status', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = CardTopup::where(function ($query) use ($search) {
                    $query->Where('payment_type', 'LIKE', "%{$search}%")
                        ->orWhere('status', 'LIKE', "%{$search}%");
                })
                ->count();
                
                   if(!$totalFiltered){
                    
                   $users_ids= Users::where('profile_id','LIKE', "%{$search}%")
                    ->orWhere('first_name', 'LIKE', "%{$search}%")
                   ->orWhere('last_name', 'LIKE', "%{$search}%")
                   ->orWhere('phone_number', 'LIKE', "%{$search}%")
                   ->orWhere('email', 'LIKE', "%{$search}%")
                    ->pluck('id');
                  
                 $result =  CardTopup::whereIn('user_id',$users_ids)
                // ->orWhere('card_number', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = CardTopup::whereIn('user_id',$users_ids)
                // ->orWhere('card_number', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->count();
            } 
            
                     if(!$totalFiltered){
                    
                   $users_ids= Card::where('card_number','LIKE', "%{$search}%")
                 
                    ->pluck('assigned_to');
                  
                 $result =  CardTopup::whereIn('user_id',$users_ids)
                // ->orWhere('card_number', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = CardTopup::whereIn('user_id',$users_ids)
                // ->orWhere('card_number', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->count();
            }
            
            
        }
        $data = array();

        $settings = GlobalSettings::first();
        
        foreach ($result as $k=>$item) 
        {
            $user = User::where('id', $item->user_id)->first();
            $card = Card::where('id', $item->card_id)->first();
            $cashier = DB::table('admin_user')->where('user_id', $item->topup_by)->first();
            
            if($item->status == '1'){
                $status = 'Success';
            }else{
                $status = 'Failed';
            }
            
            if(!empty($item->user_id)){
                if(!empty($user)){
                    $username = '<a href="' . route('users.profile', $item->user_id) . '"><span class="">' . $user->first_name.' '.$user->last_name . '</span></a>'; 
                }else{
                    $username = 'N/A';
                }
            }else{
                $username = 'N/A';
            }
            
            if(!empty($item->topup_by)){
                if(!empty($cashier)){
                    $cashierrname = '<a href="' . route('admins.edit', $item->topup_by) . '"><span class="">' . $cashier->first_name.' '.$cashier->last_name . '</span></a>';
                }else{
                    $cashierrname = 'N/A';
                }
            }else{
                $cashierrname = 'N/A';
            }
            
            if(has_permission(session()->get('user_type'), 'view_card_topup')){
                $view = '<a href="' . route('cards.topup.edit', $item->id) . '" class="mr-2 btn btn-info text-white viewBtn " rel=' . $item->id . ' ><i class="fa fa-eye"></i></a>';
                $print = '<a href="' . route('topup.invoice', $item->id) . '" class="mr-2 btn btn-success text-white printBtn" target="_blank" rel=' . $item->id . ' ><i class="fa fa-print"></i></a>';
            }else{
                $view = '';
                $print = '';
            }
            
            $action = $view.$print;
            
            $data[] = array(
                ++$k,
                $item->order_id??'N/A',
                chunk_split($card->card_number, 4, ' '),
                $username,
                $settings->currency.number_format($item->amount, 2, '.', ','),
                $cashierrname,
                !empty($item->created_at) ? Carbon::parse($item->created_at)->format('d-M-Y') : 'N/A',
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
    
    public function card_topup_store(Request $request)
    {
        $card_id = $request->card_id;
        $amount = $request->amount;
        
        $card = Card::where('id', $card_id)->first();
        $admin = Admin::where('user_name', session()->get('user_name'))->first();
        
        $CardsTransaction = new CardsTransaction();
        $CardsTransaction->user_id=$card->assigned_to;
        $CardsTransaction->card_id=$card->id;
        $CardsTransaction->transaction_id = GlobalFunction::generateTransactionId();
        $CardsTransaction->type= 'Topup';
        $CardsTransaction->amount =$amount;
        $CardsTransaction->save();
        
        $rs = CardTopup::create([
            'user_id' => $card->assigned_to,
            'card_id' => $card_id,
            'topup_by' => $admin->user_id,
            'amount' => $amount,
            'payment_type' => 'Cash',
            'order_id' => $CardsTransaction->transaction_id
        ]);
        
        $topup = Card::where('id', $card_id)->update(['balance' => $card->balance + $amount]);
        
        $earning  = 0;
        $settings = GlobalSettings::first();
        $commissionAmount = ($settings->comission / 100) * $earning;
        
        // Revenue divide
        $revenue_per= RevenueSetting::latest()->first();
        $ibp_revenue = ($revenue_per->ibp_revenue/100)*$earning;
        $account_maintenance = ($revenue_per->account_maintenance/100)*$earning;
        $technical_support= ($revenue_per->technical_support/100)*$earning;

        // Adding Earning Logs of Platform
        $platformEarningHistory = new PlatformEarningHistory();
        $platformEarningHistory->type = "card_topup";
        $platformEarningHistory->earning_number = GlobalFunction::generatePlatformEarningHistoryNumber();
        $platformEarningHistory->amount = $commissionAmount;
        $platformEarningHistory->ibp_revenue = $ibp_revenue;
        $platformEarningHistory->account_maintenance = $account_maintenance;
        $platformEarningHistory->technical_support = $technical_support;
        $platformEarningHistory->commission_percentage = $settings->comission;
        $platformEarningHistory->booking_id = $CardsTransaction->id;
        $platformEarningHistory->salon_id = 1;
        $platformEarningHistory->save();
        
        // Increasing total platform earning data
        $platformData = PlatformData::first();
        $platformData->lifetime_earnings = $platformData->lifetime_earnings + $commissionAmount;
        $platformData->save();
        
        // User Mail
        $user = Users::where('id',$card->assigned_to)->first();
        $user_name = $user->first_name.' '.$user->last_name;
        
        $check_device = Device::where('user_id', $user->id)->first();
        if(!empty($check_device->language)){
            $act_lang = $check_device->language;
        }else{
            $act_lang = '1';
        }
        
        $email_template = EmailTemplate::find(13);
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
        
        $content = str_replace(["{user}","{cardnumber}","{email}","{phone}","{date}","{amount}"], [$user_name, chunk_split($card->card_number, 4, ' '),$user->email,$user->formated_number,$card->assigned_on, $settings->currency.number_format($amount, 2, '.', ',')],$content);
        $details=[         
            "subject" => $subject ,
            "message" => $content,
            "to" => $user->email,
        ];
        send_email($details);
        
        $superAdmin = Admin::where('user_type',1)->first();
        $admin_name = $admin->first_name.' '.$admin->last_name;
        $bookingsuccesemail = AdminEmailTemplate::find(13);
        $subject = $bookingsuccesemail->email_subjects;
        $content = $bookingsuccesemail->email_content; 
        $content = str_replace(["{user}","{cardnumber}","{email}","{phone}","{date}","{amount}","{admin}"], [$user_name, chunk_split($card->card_number, 4, ' '),$user->email,$user->formated_number,$card->assigned_on, $settings->currency.number_format($amount, 2, '.', ','), $admin_name],$content);
        $details=[         
            "subject" => $subject ,
            "message" => $content,
            "to" => $superAdmin->email,
        ];
        send_email($details);
        
        $type = "card_topup";
        
        $notification_template = NotificationTemplate::find(10);
        if($act_lang == '1'){
            $title = $notification_template->notification_subjects;
            $message = strip_tags($notification_template->notification_content);
        }elseif($act_lang == '2'){
            $title = $notification_template->notification_subject_pap;
            $message = strip_tags($notification_template->notification_content_pap);
        }elseif($act_lang == '3'){
            $title = $notification_template->notification_subject_nl;
            $message = strip_tags($notification_template->notification_content_nl);
        }
        
        $message = str_replace(["{amount}", "{card_number}"],[$settings->currency.number_format($amount, 2, '.', ','), chunk_split($card->card_number, 4, ' ')],$message);
        
        $item = new UserNotification();
        $item->user_id = $user->id;
        $item->title = $title;
        $item->description = $message;
        $item->notification_type = $type;
        $item->temp_id = '10';
        $item->amount = $settings->currency.number_format($amount, 2, '.', ',');
        $item->card_number = chunk_split($card->card_number, 4, ' ');
        $item->save();
        
        GlobalFunction::sendPushToUser($title, $message, $user);

        $adminNotification = AdminNotificationTemplate::find(13);
        $title = $adminNotification->notification_subjects;
        $title = str_replace(["{card_number}"],[$user_name],$title);
        $message = strip_tags($adminNotification->notification_content);
        $message = str_replace(["{amount}","{card_number}","{admin}"],[$settings->currency.number_format($amount, 2, '.', ','),chunk_split($card->card_number, 4, ' '),$admin_name],$message);
        dispatch(new SendAdminNotification($title,$message,$type,$user->id));

        return back()->with(['card_topup' => 'Card Topup Done Successfully', 'card_id' => $rs->id]);
    }
    
    public function card_topup_edit($id)
    {
        $card = CardTopup::where('id', $id)->first();
        
        return view('cards.viewtopup', ['card' => $card]);
    }
    
    public function topup_invoice($id)
    {
        $topup = CardTopup::where('id', $id)->first();
        $user = User::where('id', $topup->user_id)->first();
        $card = Card::where('id', $topup->card_id)->first();
        $settings = GlobalSettings::first();
        
        return view('invoice.topup', ['topup' => $topup, 'user' => $user, 'card' => $card, 'settings' => $settings]);
    }
    
    public function card_assign()
    {
        $cards = Card::whereNull('assigned_to')->get();
        $users = User::where('is_block', '!=', '1')->whereNull('card_id')->orderBy('first_name', 'asc')->get();
        $languages = Language::get();
        return view('cards.assign', ['cards' => $cards, 'users' => $users, 'languages' => $languages]);
    }
    
    function assign_card_list(Request $request)
    {
        $totalData =  Card::whereNotNull('assigned_to')->count();
        $rows = Card::whereNotNull('assigned_to')->orderBy('assigned_on', 'DESC')->get();

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
            $result = Card::whereNotNull('assigned_to')->orderBy('assigned_on', 'DESC')->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  Card::whereNotNull('assigned_to')->where(function ($query) use ($search) {
                    $query->Where('card_number', 'LIKE', "%{$search}%")
                        ->orWhere('status', 'LIKE', "%{$search}%")
                        ->orWhere('issue_status', 'LIKE', "%{$search}%")
                        ->orWhere('language', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Card::whereNotNull('assigned_to')->where(function ($query) use ($search) {
                    $query->Where('card_number', 'LIKE', "%{$search}%")
                        ->orWhere('status', 'LIKE', "%{$search}%")
                        ->orWhere('issue_status', 'LIKE', "%{$search}%")
                        ->orWhere('language', 'LIKE', "%{$search}%");
                })
                ->count();
                
                  if(!$totalFiltered){
                    
                   $users_ids= Users::where('profile_id','LIKE', "%{$search}%")
                    ->orWhere('first_name', 'LIKE', "%{$search}%")
                   ->orWhere('last_name', 'LIKE', "%{$search}%")
                   ->orWhere('phone_number', 'LIKE', "%{$search}%")
                   ->orWhere('email', 'LIKE', "%{$search}%")
                    ->pluck('id');
                  
                 $result =  Card::whereNotNull('assigned_to')->whereIn('assigned_to',$users_ids)
                ->orWhere('card_number', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Card::whereNotNull('assigned_to')->whereIn('assigned_to',$users_ids)
                ->orWhere('card_number', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->count();
            } 
                
                
        }
        $data = array();

        $settings = GlobalSettings::first();
        
        foreach ($result as $k=>$item) 
        {
            $user = DB::table('users')->where('id', $item->assigned_to)->first();
            $cashier = DB::table('admin_user')->where('user_id', $item->assigned_by)->first();
            $language = DB::table('languages')->where('id', $item->language)->first();
            
            $imgUrl = asset('public/cards/englishfront.PNG');
            $image = '<img src="' . $imgUrl . '" style="width: 50px; height: auto;">';
            
            if($item->status == '1'){
                $status = 'Active';
            }elseif($item->status == '2'){
                $status = 'Inactive';
            }else{
                $status = 'Blocked';
            }
            
            if(!empty($item->assigned_to)){
                if(!empty($user)){
                    $username  = '<a href="' . route('users.profile', $item->assigned_to) . '"><span class="">' . $user->first_name.' '.$user->last_name . '</span></a>'; 
                    $contact = $user->formated_number.'<br>'.$user->email;
                }else{
                    $username = 'N/A';
                    $contact = 'N/A';
                }
            }else{
                $username = 'N/A';
                $contact = 'N/A';
            }
            
            if(!empty($item->assigned_by)){
                if(!empty($cashier)){
                    $cashierrname = '<a href="' . route('admins.edit', $item->assigned_by) . '"><span class="">' . $cashier->first_name.' '.$cashier->last_name. '</span></a>';  ;
                }else{
                    $cashierrname = 'N/A';
                }
            }else{
                $cashierrname = 'N/A';
            }
            
            if(has_permission(session()->get('user_type'), 'view_assign_card')){
                $view = '<a href="' . route('cards.edit', $item->id) . '" class="mr-2 btn btn-info text-white viewBtn " rel=' . $item->id . ' ><i class="fa fa-eye"></i></a>';
                $print = '<a href="' . route('assign.invoice', $item->id) . '" class="mr-2 btn btn-success text-white printBtn" target="_blank" rel=' . $item->id . ' ><i class="fa fa-print"></i></a>';
            }else{
                $view = '';
                $print = '';
            }
            
            $action = $view.$print;
            
            $data[] = array(
                ++$k,
                $image,
                chunk_split($item->card_number, 4, ' '),
                $settings->currency . number_format($item->assign_fee, 2, '.', ','),
                $status,
                $settings->currency . number_format($item->balance, 2, '.', ','),
                $username,
                $contact,
                $cashierrname,
                !empty($item->assigned_on) ? Carbon::parse($item->assigned_on)->format('d-M-Y') : 'N/A',
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
    
    public function assign_card_store(Request $request)
    {
        $user_id = $request->user_id;
        $card_id = $request->card_id;
        
        $admin = DB::table('admin_user')->where('user_name', session()->get('user_name'))->first();
        
        $cardfee = CardFee::where('status', '1')->orderBy('id', 'desc')->first();
        $membershipfee = MaintenanceFee::where('status', '1')->orderBy('id', 'desc')->first();
        
        $card = Card::where('id', $card_id)->update([
            'assigned_to' => $user_id,
            'assigned_by' => $admin->user_id,
            'assigned_on' => Carbon::now()->format('Y-m-d'),
            'issue_status'=> '1',
            'status'      => '1',
            'assign_fee'  => $cardfee->fee,
        ]);
        
        $earning  =$cardfee->fee;
        $settings = GlobalSettings::first();
        $commissionAmount = ($settings->comission / 100) * $earning;
        
        // Revenue divide
        $revenue_per= RevenueSetting::latest()->first();
        $ibp_revenue = ($revenue_per->ibp_revenue/100)*$earning;
        $account_maintenance = ($revenue_per->account_maintenance/100)*$earning;
        $technical_support= ($revenue_per->technical_support/100)*$earning;

        // Adding Earning Logs of Platform
        $platformEarningHistory = new PlatformEarningHistory();
        $platformEarningHistory->type = "card_assign";
        $platformEarningHistory->earning_number = GlobalFunction::generatePlatformEarningHistoryNumber();
        $platformEarningHistory->amount = $commissionAmount;
        $platformEarningHistory->ibp_revenue = $ibp_revenue;
        $platformEarningHistory->account_maintenance = $account_maintenance;
        $platformEarningHistory->technical_support = $technical_support;
        $platformEarningHistory->commission_percentage = $settings->comission;
        $platformEarningHistory->booking_id = $card_id;
        $platformEarningHistory->salon_id = 1;
        $platformEarningHistory->save();
        
        // Increasing total platform earning data
        $platformData = PlatformData::first();
        $platformData->lifetime_earnings = $platformData->lifetime_earnings + $commissionAmount;
        $platformData->save();
        
        User::where('id', $user_id)->update([
            'card_id' => $card_id
        ]);
        
        $card = Card::where('id', $card_id)->first();
        
        $rs = CardassignFee::create([
            'user_id' => $user_id,
            'card_id' => $card_id,
            'fee' => $cardfee->fee,
            'status' => '1',
        ]);
        
        $rs = CardmembershipFee::create([
            'user_id' => $user_id,
            'card_id' => $card_id,
            'fee' => $membershipfee->fee,
            'status' => '1',
        ]);
        
        // User Mail
        $user = Users::where('id', $user_id)->first();
        $user_name = $user->first_name.' '.$user->last_name;
        
        $check_device = Device::where('user_id', $user->id)->first();
        if(!empty($check_device->language)){
            $act_lang = $check_device->language;
        }else{
            $act_lang = '1';
        }
        
        $email_template = EmailTemplate::find(12);
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
        
        $content = str_replace(["{user}","{cardnumber}","{email}","{phone}","{date}"], [$user_name, chunk_split($card->card_number, 4, ' '), $user->email,$user->formated_number,$card->assigned_on],$content);
        $details=[         
            "subject" => $subject ,
            "message" => $content,
            "to" => $user->email,
        ];
        send_email($details);
        
        $superAdmin = Admin::where('user_type',1)->first();
        $admin_name = $admin->first_name.' '.$admin->last_name;
        $bookingsuccesemail = AdminEmailTemplate::find(12);
        $subject = $bookingsuccesemail->email_subjects;
        $content = $bookingsuccesemail->email_content; 
        $content = str_replace(["{user}","{cardnumber}","{email}","{phone}","{date}","{admin}"], [$user_name,chunk_split($card->card_number, 4, ' '),$user->email,$user->formated_number,$card->assigned_on, $admin_name],$content);
        $details=[         
            "subject" => $subject ,
            "message" => $content,
            "to" => $superAdmin->email,
        ];
        send_email($details);
        
        $type = "card_assigned";
        
        $notification_template = NotificationTemplate::find(9);
        if($act_lang == '1'){
            $title = $notification_template->notification_subjects;
            $message = strip_tags($notification_template->notification_content);
        }elseif($act_lang == '2'){
            $title = $notification_template->notification_subject_pap;
            $message = strip_tags($notification_template->notification_content_pap);
        }elseif($act_lang == '3'){
            $title = $notification_template->notification_subject_nl;
            $message = strip_tags($notification_template->notification_content_nl);
        }
        
        $message = str_replace(["{card_number}"],[chunk_split($card->card_number, 4, ' ')],$message);
        
        $item = new UserNotification();
        $item->user_id = $user->id;
        $item->title = $title;
        $item->description = $message;
        $item->notification_type = $type;
        $item->temp_id = '9';
        $item->card_number = chunk_split($card->card_number, 4, ' ');
        $item->save();
        
        GlobalFunction::sendPushToUser($title, $message, $user);

        $adminNotification = AdminNotificationTemplate::find(6);
        $title = $adminNotification->notification_subjects;
        $title = str_replace(["{user}"],[$user_name],$title);
        $message = strip_tags($adminNotification->notification_content);
        $message = str_replace(["{user}","{card_number}"],[$user_name,chunk_split($card->card_number, 4, ' ')],$message);
        dispatch(new SendAdminNotification($title,$message,$type,$user->id));
        
        return back()->with(['card_assign' => 'Card Assigned Successfully', 'card_id' => $card_id]);
    }
    
    public function assign_invoice($id)
    {
        $card = Card::where('id', $id)->first();
        $user = User::where('id', $card->assigned_to)->first();
        $settings = GlobalSettings::first();
        
        return view('invoice.assign', ['user' => $user, 'card' => $card, 'settings' => $settings]);
    }
    
    public function card_unassign($id)
    {
        $check_card = Card::where('id', $id)->first();
        
        $user = User::where('id', $check_card->assigned_to)->update([
            'card_id' => null
        ]);
        
        $card = Card::where('id', $id)->update([
            'assigned_to' => null,
            'assigned_by' => null,
            'assigned_on' => null,
            'issue_status'=> 2,
            'status'      => 2
        ]);
        
        return back()->with(['card_message' => 'Card Unassigned Successfully']);
    }
    
    public function cards_loyality_point(Request $request)
    {
        $setting = GlobalSettings::where('id', '1')->update(['loyality_amount' => $request->amount,'loyality_points'=>$request->loyality_points]);
        
        return back()->with(['settings_message' => 'Loyality Points Updated Successfully']);
    }
    
    public function cards_details(Request $request)
    {
        $card_len = strlen($request->card_id);
        if($card_len != '12'){
            return response()->json([
                'message'=> 'Invalid Card Number',
            ]);
        }
        
        $card = Card::where('card_number', $request->card_id)->first();
        if(empty($card)){
            return response()->json([
                'status' => false,
                'message'=> 'Invalid Card Number',
            ]);
        }
        
        if(empty($card->assigned_to)){
            return response()->json([
                'status' => false,
                'message'=> 'Card Not Assigned',
            ]);
        }
        
        if(session()->get('user_type') == '20'){
            $redirect_url = route('admin.service.booking', $card->assigned_to);
        }else{
            $redirect_url = route('users.profile', $card->assigned_to);
        }
        
        return response()->json([
            'status' => true,
            'message'=> 'Card details fetched successfully.',
            'redirect_url' => $redirect_url,
        ]);
    }
    
    public function export_cards(Request $request)
    {
        $cards = Card::where('created_at', '>=', $request->from_date)->where('created_at', '<', $request->to_date)->get();
        $languages = Language::get();
        return view('cards.export', ['cards' => $cards, 'languages' => $languages]);
    }
    
    public function transactions(Request $request)
    {
        $transactions = CardsTransaction::get();
        $languages = Language::get();
        return view('cards.transactions', ['cards' => $transactions, 'languages' => $languages]);
    }
    
    function transactions_list(Request $request)
    {
        $totalData =  CardsTransaction::count();
        $rows = CardsTransaction::orderBy('id', 'DESC')->get();

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
            $result = CardsTransaction::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  CardsTransaction::where(function ($query) use ($search) {
                    $query->Where('transaction_id', 'LIKE', "%{$search}%")
                        ->orWhere('booking_id', 'LIKE', "%{$search}%")
                        ->orWhere('status', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = CardsTransaction::where(function ($query) use ($search) {
                    $query->Where('transaction_id', 'LIKE', "%{$search}%")
                        ->orWhere('booking_id', 'LIKE', "%{$search}%")
                        ->orWhere('status', 'LIKE', "%{$search}%");
                })
                ->count();
            
                   if(!$totalFiltered){
                    
                   $users_ids= Users::where('profile_id','LIKE', "%{$search}%")
                    ->orWhere('first_name', 'LIKE', "%{$search}%")
                   ->orWhere('last_name', 'LIKE', "%{$search}%")
                   ->orWhere('phone_number', 'LIKE', "%{$search}%")
                   ->orWhere('email', 'LIKE', "%{$search}%")
                    ->pluck('id');
                  
                 $result =  CardsTransaction::whereIn('user_id',$users_ids)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = CardTopup::whereIn('user_id',$users_ids)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->count();
            } 
            
            if(!$totalFiltered){
                    
                $users_ids= Card::where('card_number','LIKE', "%{$search}%")->pluck('assigned_to');
                  
                $result =  CardsTransaction::whereIn('user_id',$users_ids)
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
                $totalFiltered = CardTopup::whereIn('user_id',$users_ids)
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->count();
            }      
        }
        $data = array();

        $settings = GlobalSettings::first();
        
        foreach ($result as $k=>$item) 
        {
            $user = DB::table('users')->where('id', $item->user_id)->first();
            $cardnumber = Card::where('id', $item->card_id)->first();
            
            if($item->status == '1'){
                $status = 'Success';
            }else{
                $status = 'Failed';
            }
            
            if(!empty($item->user_id)){
                if(!empty($user)){
                    $username = '<a href="' . route('users.profile', $user->id) . '" class="text-info" rel=' . $item->id . ' >' .$user->first_name.' '.$user->last_name. '</a>';
                    $contact = $user->formated_number.'<br>'.$user->email;
                }else{
                    $username = 'N/A';
                    $contact = 'N/A';
                }
            }else{
                $username = 'N/A';
                $contact = 'N/A';
            }
            
            $card_number = '<a href="' . route('cards.edit', $item->card_id) . '" class="text-info" rel=' . $item->id . ' >' . chunk_split($cardnumber->card_number, 4, ' ') . '</a>';
            
            if($item->type=='Topup'){
                $type="Card Topup";
            }else{
                $type="Spent on Booking";  
            }
            
            $data[] = array(
                ++$k,
                $username,
                $card_number,
                $item->transaction_id,
                $item->booking_id??'N/A',
                $settings->currency . number_format($item->amount, 2, '.', ','),
                $type,
                $status,
                !empty($item->created_at) ? Carbon::parse($item->created_at)->format('d-M-Y') : 'N/A',
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
    
    public function transaction(Request $request, $id)
    {
        $transactions = CardsTransaction::where('card_id', $id)->get();
        $card = Card::where('id', $id)->first();
        $user = User::where('id', $card->assigned_to)->first();
        $languages = Language::get();
        return view('cards.transaction', ['cards' => $transactions, 'languages' => $languages, 'card' => $card, 'user' => $user]);
    }
    
    function transaction_list(Request $request, $id)
    {
        $totalData =  CardsTransaction::where('card_id', $id)->count();
        $rows = CardsTransaction::where('card_id', $id)->orderBy('id', 'DESC')->get();

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
            $result = CardsTransaction::where('card_id', $id)->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  CardsTransaction::where('card_id', $id)->where(function ($query) use ($search) {
                    $query->Where('transaction_id', 'LIKE', "%{$search}%")
                        ->orWhere('booking_id', 'LIKE', "%{$search}%")
                        ->orWhere('status', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = CardsTransaction::where('card_id', $id)->where(function ($query) use ($search) {
                    $query->Where('transaction_id', 'LIKE', "%{$search}%")
                        ->orWhere('booking_id', 'LIKE', "%{$search}%")
                        ->orWhere('status', 'LIKE', "%{$search}%");
                })
                ->count();
        }
        $data = array();

        $settings = GlobalSettings::first();
        
        foreach ($result as $k=>$item) 
        {
            $user = DB::table('users')->where('id', $item->user_id)->first();
            $cardnumber = Card::where('id', $item->card_id)->first();
            
            if($item->status == '1'){
                $status = 'Success';
            }else{
                $status = 'Failed';
            }
            
            if(!empty($item->user_id)){
                if(!empty($user)){
                    $username = '<a href="' . route('users.profile', $user->id) . '" class="text-info" rel=' . $item->id . ' >' .$user->first_name.' '.$user->last_name. '</a>';
                    $contact = $user->formated_number.'<br>'.$user->email;
                }else{
                    $username = 'N/A';
                    $contact = 'N/A';
                }
            }else{
                $username = 'N/A';
                $contact = 'N/A';
            }
            
            $card_number = '<a href="' . route('cards.edit', $item->card_id) . '" class="text-info" rel=' . $item->id . ' >' . chunk_split($cardnumber->card_number, 4, ' ') . '</a>';
            
            $data[] = array(
                ++$k,
                $username,
                $card_number,
                $item->transaction_id,
                !empty($item->booking_id) ? $item->booking_id : 'N/A',
                $settings->currency . number_format($item->amount, 2, '.', ','),
                $item->type,
                $status,
                !empty($item->created_at) ? Carbon::parse($item->created_at)->format('d-M-Y') : 'N/A',
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
    
    public function memberships(Request $request)
    {
        $memberships = CardmembershipFee::get();
        return view('cards.membership', ['memberships' => $memberships]);
    }
    
    function memberships_list(Request $request)
    {
        $totalData =  CardmembershipFee::count();
        $rows = CardmembershipFee::orderBy('id', 'DESC')->get();

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
            $result = CardmembershipFee::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  CardmembershipFee::where(function ($query) use ($search) {
                    $query->Where('fee', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = CardmembershipFee::where(function ($query) use ($search) {
                    $query->Where('fee', 'LIKE', "%{$search}%");
                })
                ->count();
        }
        $data = array();

        $settings = GlobalSettings::first();
        
        foreach ($result as $k=>$item) 
        {
            $user = DB::table('users')->where('id', $item->user_id)->first();
            $cardnumber = Card::where('id', $item->card_id)->first();
            
            if(!empty($item->user_id)){
                if(!empty($user)){
                    $username = '<a href="' . route('users.profile', $user->id) . '" class="text-info" rel=' . $item->id . ' >' .$user->first_name.' '.$user->last_name. '</a>';
                }else{
                    $username = 'N/A';
                }
            }else{
                $username = 'N/A';
            }
            
            $card_number = '<a href="' . route('cards.edit', $item->card_id) . '" class="text-info" rel=' . $item->id . ' >' . chunk_split($cardnumber->card_number, 4, ' ') . '</a>';
            
            $data[] = array(
                ++$k,
                $username,
                $card_number,
                $settings->currency . number_format($item->fee, 2, '.', ','),
                !empty($item->created_at) ? Carbon::parse($item->created_at)->format('d-M-Y') : 'N/A',
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
    
    public function scan_card()
    {
        return view('cards.scan');
    }
}

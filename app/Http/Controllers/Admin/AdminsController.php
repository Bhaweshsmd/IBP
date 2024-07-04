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
use DB;
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
use App\Models\CardTopup;

class AdminsController extends Controller
{
    public function index()
    {
        $admins = Admin::get();
        return view('admins.index', ['data' => $admins]);
    }
    
    public function create()
    {
        $roles = Role::where('id', '!=', '1')->get();
        return view('admins.create', ['roles' => $roles]);
    }
    
    public function store(Request $request)
    {
        $admin                = new Admin();
        $admin->first_name    = $request->first_name;
        $admin->last_name     = $request->last_name;
        $admin->user_name     = $request->user_name;
        $admin->email         = $request->email;
        $admin->phone         = $request->phone;
        $admin->user_password = Hash::make($request->user_password);
        $admin->user_type     = $request->user_type;
        $admin->status        = $request->status;
        
        if ($request->has('picture')) {
            $admin->picture = GlobalFunction::saveFileAndGivePath($request->picture);
        }
        
        $admin->save();
        
        return redirect('admins')->with(['admin_message' => 'Admin Created Successfully']);
    }
    
    function list(Request $request)
    {
        $totalData =  Admin::count();
        $rows = Admin::orderBy('user_id', 'ASC')->get();

        $result = $rows;

        $columns = array(
            0 => 'user_id',
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
            $result = Admin::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  Admin::where(function ($query) use ($search) {
                    $query->where('user_name', 'LIKE', "%{$search}%")
                        ->orWhere('user_type', 'LIKE', "%{$search}%")
                        ->orWhere('first_name', 'LIKE', "%{$search}%")
                        ->orWhere('last_name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%")
                        ->orWhere('phone', 'LIKE', "%{$search}%")
                        ->orWhere('status', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Admin::where(function ($query) use ($search) {
                    $query->where('user_name', 'LIKE', "%{$search}%")
                        ->orWhere('user_type', 'LIKE', "%{$search}%")
                        ->orWhere('first_name', 'LIKE', "%{$search}%")
                        ->orWhere('last_name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%")
                        ->orWhere('phone', 'LIKE', "%{$search}%")
                        ->orWhere('status', 'LIKE', "%{$search}%");
                })
                ->count();
        }
        $data = array();

        $settings = GlobalSettings::first();
        
        foreach ($result as $k=>$item) 
        {
            if ($item->picture == null) {
                $image = '<img src="https://placehold.jp/150x150.png" width="50" height="50">';
            } else {
                $imgUrl = GlobalFunction::createMediaUrl($item->picture);
                $image = '<img src="' . $imgUrl . '" width="50" height="50">';
            }
            
            if($item->status == '1'){
                $status = 'Active';
            }else{
                $status = 'Inactive';
            }
            
            if(has_permission(session()->get('user_type'), 'view_admin')){
                $view = '<a href="' . route('admins.edit', $item->user_id) . '" class="mr-2 btn btn-info text-white viewBtn" rel=' . $item->user_id . ' ><i class="fa fa-eye faPostion"></i></a>';
            }else{
                $view = '';
            }
            
            if(has_permission(session()->get('user_type'), 'delete_admin')){
                $delete = '<a href="" class="mr-2 btn btn-danger text-white delete deleteBtn" rel=' . $item->user_id . ' ><i class="fa fa-trash faPostion"></i></a>';
            }else{
                $delete = '';
            }
            
            if($item->user_type == '1'){
                $action = $view;
            }else{
                $action = $view.$delete;
            }
            
            $check_role = Role::where('id', $item->user_type)->first();
            
            $name='<a href="' . route('admins.edit', $item->user_id) . '" class="mr-2  text-black " rel=' . $item->user_id . ' >' .$item->first_name.' '.$item->last_name. '</a>';
            $user_name='<a href="' . route('admins.edit', $item->user_id) . '" class="mr-2  text-black " rel=' . $item->user_id . ' >' .$item->user_name. '</a>';

            $data[] = array(
                ++$k,
                $image,
                $name,
                $user_name,
                $item->email,
                $status,
                $check_role->name,
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
        
        $admin = Admin::find($id);
        $roles = Role::where('id', '!=', '1')->get();

        return view('admins.edit', [
            'admin' => $admin,
            'roles' => $roles
        ]);
    }
    
    public function update(Request $request, $id)
    {
        $admin                = Admin::find($id);
        $admin->first_name    = $request->first_name;
        $admin->last_name     = $request->last_name;
        $admin->user_name     = $request->user_name;
        $admin->email         = $request->email;
        $admin->phone         = $request->phone;
        if($request->user_password){
            $admin->user_password = Hash::make($request->user_password);
        }else{
            $admin->user_password = $admin->user_password;
        }
        $admin->user_type     = $request->user_type;
        $admin->status        = $request->status;
        if ($request->has('picture')) {
            $admin->picture = GlobalFunction::saveFileAndGivePath($request->picture);
        }
        $admin->save();
        
        return redirect('admins')->with(['admin_message' => 'Admin Updated Successfully']);
    }
    
    public function delete($id)
    {
        Admin::where('user_id', $id)->delete();

        return redirect('admins')->with(['admin_message' => 'Admin Deleted Successfully']);
    }
    
    public function profile()
    {
        $admin = DB::table('admin_user')->where('user_name', session()->get('user_name'))->first();
        $role = Role::where('id', $admin->user_type)->first();
        return view('admins.profile', ['admin' => $admin, 'role' => $role]);
    }
    
    public function profile_update(Request $request, $id)
    {
        $admin                = Admin::find($id);
        $admin->first_name    = $request->first_name;
        $admin->last_name     = $request->last_name;
        $admin->user_name     = $request->user_name;
        $admin->email         = $request->email;
        $admin->phone         = $request->phone;
        $admin->status        = $request->status;
        if ($request->has('picture')) {
            $admin->picture = GlobalFunction::saveFileAndGivePath($request->picture);
        }
        $admin->save();
        
        Session::flash('message', 'Profile Updated successfully.');
        return back();
    }
    
    function password_update(Request $request)
    {
        $admin = Admin::where('user_name', session()->get('user_name'))->first();
        if (Hash::check($request->old_password, $admin->user_password)) { 
            
            $rs = Admin::where('user_name', session()->get('user_name'))->update(['user_password' => Hash::make($request->new_password)]);

            return response()->json(['status' => true, 'message' => 'Password changed successfully']);
        } else {
            return response()->json(['status' => false, 'message' => 'Incorrect Old password !']);
        }
    }
    
    function dashboard()
    {
        $settings = GlobalSettings::first();
        
        $cards = Card::whereNull('assigned_to')->get();
        $assigncards = Card::whereNotNull('assigned_to')->get();
        $users = Users::where('is_block', '!=', '1')->whereNull('card_id')->orderBy('first_name', 'asc')->get();

        $salonRequests = Salons::where('status', Constants::statusSalonPending)->count();
        $activeSalons = Salons::where('status', Constants::statusSalonActive)->count();
        $bannedSalons = Salons::where('status', Constants::statusSalonBanned)->count();
        $singUpOnlySalons = Salons::where('status', Constants::statusSalonSignUpOnly)->count();

        $todayTotalBookings = Bookings::whereDate('created_at', Carbon::now())->count();
        $todayTotalPendingBookings = Bookings::whereDate('created_at', Carbon::today())->where('status', Constants::orderPlacedPending)->count();
        $todayTotalAcceptedBookings = Bookings::whereDate('created_at', Carbon::today())->where('status', Constants::orderAccepted)->count();
        $todayTotalCompletedBookings = Bookings::whereDate('created_at', Carbon::today())->where('status', Constants::orderCompleted)->count();
        $todayTotalCancelledBookings = Bookings::whereDate('created_at', Carbon::today())->where('status', Constants::orderCancelled)->count();
        $todayTotalDeclinedBookings = Bookings::whereDate('created_at', Carbon::today())->where('status', Constants::orderDeclined)->count();

        $last7date = Carbon::now()->subDays(7);
        $last7daysTotalBookings = Bookings::where('created_at', '>=', $last7date)->count();
        $last7daysTotalPendingBookings = Bookings::where('created_at', '>=', $last7date)->where('status', Constants::orderPlacedPending)->count();
        $last7daysTotalAcceptedBookings = Bookings::where('created_at', '>=', $last7date)->where('status', Constants::orderAccepted)->count();
        $last7daysTotalCompletedBookings = Bookings::where('created_at', '>=', $last7date)->where('status', Constants::orderCompleted)->count();
        $last7daysTotalCancelledBookings = Bookings::where('created_at', '>=', $last7date)->where('status', Constants::orderCancelled)->count();
        $last7daysTotalDeclinedBookings = Bookings::where('created_at', '>=', $last7date)->where('status', Constants::orderDeclined)->count();

        $last30date = Carbon::now()->subDays(30);
        $last30daysTotalBookings = Bookings::where('created_at', '>=', $last30date)->count();
        $last30daysTotalPendingBookings = Bookings::where('created_at', '>=', $last30date)->where('status', Constants::orderPlacedPending)->count();
        $last30daysTotalAcceptedBookings = Bookings::where('created_at', '>=', $last30date)->where('status', Constants::orderAccepted)->count();
        $last30daysTotalCompletedBookings = Bookings::where('created_at', '>=', $last30date)->where('status', Constants::orderCompleted)->count();
        $last30daysTotalCancelledBookings = Bookings::where('created_at', '>=', $last30date)->where('status', Constants::orderCancelled)->count();
        $last30daysTotalDeclinedBookings = Bookings::where('created_at', '>=', $last30date)->where('status', Constants::orderDeclined)->count();

        $last90date = Carbon::now()->subDays(90);
        $last90daysTotalBookings = Bookings::where('created_at', '>=', $last90date)->count();
        $last90daysTotalPendingBookings = Bookings::where('created_at', '>=', $last90date)->where('status', Constants::orderPlacedPending)->count();
        $last90daysTotalAcceptedBookings = Bookings::where('created_at', '>=', $last90date)->where('status', Constants::orderAccepted)->count();
        $last90daysTotalCompletedBookings = Bookings::where('created_at', '>=', $last90date)->where('status', Constants::orderCompleted)->count();
        $last90daysTotalCancelledBookings = Bookings::where('created_at', '>=', $last90date)->where('status', Constants::orderCancelled)->count();
        $last90daysTotalDeclinedBookings = Bookings::where('created_at', '>=', $last90date)->where('status', Constants::orderDeclined)->count();

        $last180date = Carbon::now()->subDays(180);
        $last180daysTotalBookings = Bookings::where('created_at', '>=', $last180date)->count();
        $last180daysTotalPendingBookings = Bookings::where('created_at', '>=', $last180date)->where('status', Constants::orderPlacedPending)->count();
        $last180daysTotalAcceptedBookings = Bookings::where('created_at', '>=', $last180date)->where('status', Constants::orderAccepted)->count();
        $last180daysTotalCompletedBookings = Bookings::where('created_at', '>=', $last180date)->where('status', Constants::orderCompleted)->count();
        $last180daysTotalCancelledBookings = Bookings::where('created_at', '>=', $last180date)->where('status', Constants::orderCancelled)->count();
        $last180daysTotalDeclinedBookings = Bookings::where('created_at', '>=', $last180date)->where('status', Constants::orderDeclined)->count();

        $allTimeTotalBookings = Bookings::count();
        $allTimeTotalPendingBookings = Bookings::where('status', Constants::orderPlacedPending)->count();
        $allTimeTotalAcceptedBookings = Bookings::where('status', Constants::orderAccepted)->count();
        $allTimeTotalCompletedBookings = Bookings::where('status', Constants::orderCompleted)->count();
        $allTimeTotalDeclinedBookings = Bookings::where('status', Constants::orderDeclined)->count();
        $allTimeTotalCancelledBookings = Bookings::where('status', Constants::orderCancelled)->count();

        $todayEarnings = PlatformEarningHistory::whereDate('created_at', Carbon::now())->sum('amount');
        $last7DaysEarnings = PlatformEarningHistory::where('created_at', '>=', $last7date)->sum('amount');
        $last30DaysEarnings = PlatformEarningHistory::where('created_at', '>=', $last30date)->sum('amount');
        $last90DaysEarnings = PlatformEarningHistory::where('created_at', '>=', $last90date)->sum('amount');
        $last180DaysEarnings = PlatformEarningHistory::where('created_at', '>=', $last180date)->sum('amount');
        $allTimeDaysEarnings = PlatformEarningHistory::sum('amount');

        $pendingSalonPayouts = SalonPayoutHistory::where('status', 0)->sum('amount');
        $completedSalonPayouts = SalonPayoutHistory::where('status', 1)->sum('amount');
        $pendingUserPayouts = UserWithdrawRequest::where('status', 0)->sum('amount');
        $completedUserPayouts = UserWithdrawRequest::where('status', 1)->sum('amount');
        $rejectedUserPayouts = UserWithdrawRequest::where('status', 2)->sum('amount');
        
        $todayRecharges = UserWalletRechargeLogs::whereDate('created_at', Carbon::now())->sum('amount');
        $last7DaysRecharges = UserWalletRechargeLogs::where('created_at', '>=', $last7date)->sum('amount');
        $last30DaysRecharges = UserWalletRechargeLogs::where('created_at', '>=', $last30date)->sum('amount');
        $last90DaysRecharges = UserWalletRechargeLogs::where('created_at', '>=', $last90date)->sum('amount');
        $last180DaysRecharges = UserWalletRechargeLogs::where('created_at', '>=', $last180date)->sum('amount');
        $allTimeRecharges = UserWalletRechargeLogs::sum('amount');
        
        $todayTopups = CardTopup::whereDate('created_at', Carbon::now())->sum('amount');
        $last7DaysTopups = CardTopup::where('created_at', '>=', $last7date)->sum('amount');
        $last30DaysTopups = CardTopup::where('created_at', '>=', $last30date)->sum('amount');
        $last90DaysTopups = CardTopup::where('created_at', '>=', $last90date)->sum('amount');
        $last180DaysTopups = CardTopup::where('created_at', '>=', $last180date)->sum('amount');
        $allTimeTopups = CardTopup::sum('amount');

        return view('dashboard.index', [
            'settings' => $settings,
            'cards' => $cards,
            'assigncards' => $assigncards,
            'users' => $users,

            'todayRecharges' => GlobalFunction::roundNumber($todayRecharges),
            'last7DaysRecharges' => GlobalFunction::roundNumber($last7DaysRecharges),
            'last30DaysRecharges' => GlobalFunction::roundNumber($last30DaysRecharges),
            'last90DaysRecharges' => GlobalFunction::roundNumber($last90DaysRecharges),
            'last180DaysRecharges' => GlobalFunction::roundNumber($last180DaysRecharges),
            'allTimeRecharges' => GlobalFunction::roundNumber($allTimeRecharges),
            
            'todayTopups' => GlobalFunction::roundNumber($todayTopups),
            'last7DaysTopups' => GlobalFunction::roundNumber($last7DaysTopups),
            'last30DaysTopups' => GlobalFunction::roundNumber($last30DaysTopups),
            'last90DaysTopups' => GlobalFunction::roundNumber($last90DaysTopups),
            'last180DaysTopups' => GlobalFunction::roundNumber($last180DaysTopups),
            'allTimeTopups' => GlobalFunction::roundNumber($allTimeTopups),

            'pendingSalonPayouts' => GlobalFunction::roundNumber($pendingSalonPayouts),
            'completedSalonPayouts' => GlobalFunction::roundNumber($completedSalonPayouts),
            'pendingUserPayouts' => GlobalFunction::roundNumber($pendingUserPayouts),
            'completedUserPayouts'=>GlobalFunction::roundNumber($completedUserPayouts),
            'rejectedUserPayouts'=>GlobalFunction::roundNumber($rejectedUserPayouts),

            'todayEarnings' => GlobalFunction::roundNumber($todayEarnings),
            'last7DaysEarnings' => GlobalFunction::roundNumber($last7DaysEarnings),
            'last30DaysEarnings' => GlobalFunction::roundNumber($last30DaysEarnings),
            'last90DaysEarnings' => GlobalFunction::roundNumber($last90DaysEarnings),
            'last180DaysEarnings' => GlobalFunction::roundNumber($last180DaysEarnings),
            'allTimeDaysEarnings' => GlobalFunction::roundNumber($allTimeDaysEarnings),

            'salonRequests' => $salonRequests,
            'activeSalons' => $activeSalons,
            'bannedSalons' => $bannedSalons,
            'singUpOnlySalons' => $singUpOnlySalons,

            'todayTotalBookings' => $todayTotalBookings,
            'todayTotalPendingBookings' => $todayTotalPendingBookings,
            'todayTotalAcceptedBookings' => $todayTotalAcceptedBookings,
            'todayTotalCompletedBookings' => $todayTotalCompletedBookings,
            'todayTotalCancelledBookings' => $todayTotalCancelledBookings,
            'todayTotalDeclinedBookings' => $todayTotalDeclinedBookings,

            'last7daysTotalBookings' => $last7daysTotalBookings,
            'last7daysTotalPendingBookings' => $last7daysTotalPendingBookings,
            'last7daysTotalAcceptedBookings' => $last7daysTotalAcceptedBookings,
            'last7daysTotalCompletedBookings' => $last7daysTotalCompletedBookings,
            'last7daysTotalCancelledBookings' => $last7daysTotalCancelledBookings,
            'last7daysTotalDeclinedBookings' => $last7daysTotalDeclinedBookings,

            'last30daysTotalBookings' => $last30daysTotalBookings,
            'last30daysTotalPendingBookings' => $last30daysTotalPendingBookings,
            'last30daysTotalAcceptedBookings' => $last30daysTotalAcceptedBookings,
            'last30daysTotalCompletedBookings' => $last30daysTotalCompletedBookings,
            'last30daysTotalCancelledBookings' => $last30daysTotalCancelledBookings,
            'last30daysTotalDeclinedBookings' => $last30daysTotalDeclinedBookings,

            'last90daysTotalBookings' => $last90daysTotalBookings,
            'last90daysTotalPendingBookings' => $last90daysTotalPendingBookings,
            'last90daysTotalAcceptedBookings' => $last90daysTotalAcceptedBookings,
            'last90daysTotalCompletedBookings' => $last90daysTotalCompletedBookings,
            'last90daysTotalCancelledBookings' => $last90daysTotalCancelledBookings,
            'last90daysTotalDeclinedBookings' => $last90daysTotalDeclinedBookings,

            'last180daysTotalBookings' => $last180daysTotalBookings,
            'last180daysTotalPendingBookings' => $last180daysTotalPendingBookings,
            'last180daysTotalAcceptedBookings' => $last180daysTotalAcceptedBookings,
            'last180daysTotalCompletedBookings' => $last180daysTotalCompletedBookings,
            'last180daysTotalCancelledBookings' => $last180daysTotalCancelledBookings,
            'last180daysTotalDeclinedBookings' => $last180daysTotalDeclinedBookings,

            'allTimeTotalBookings' => $allTimeTotalBookings,
            'allTimeTotalPendingBookings' => $allTimeTotalPendingBookings,
            'allTimeTotalAcceptedBookings' => $allTimeTotalAcceptedBookings,
            'allTimeTotalCompletedBookings' => $allTimeTotalCompletedBookings,
            'allTimeTotalCancelledBookings' => $allTimeTotalCancelledBookings,
            'allTimeTotalDeclinedBookings' => $allTimeTotalDeclinedBookings,
        ]);
    }
}

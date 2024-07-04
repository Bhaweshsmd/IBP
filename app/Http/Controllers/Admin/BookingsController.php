<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Bookings;
use App\Models\Coupons;
use App\Models\GlobalFunction;
use App\Models\Salons;
use App\Models\Users;
use App\Models\UserWalletStatements;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Constants;
use App\Models\GlobalSettings;
use App\Models\PlatformData;
use App\Models\PlatformEarningHistory;
use App\Models\SalonEarningHistory;
use App\Models\SalonPayoutHistory;
use App\Models\SalonReviews;
use App\Models\SalonWalletStatements;
use Carbon\Carbon;
use Mockery\Generator\StringManipulation\Pass\ConstantsPass;
use PHPUnit\TextUI\XmlConfiguration\Constant;
use Symfony\Component\VarDumper\Caster\ConstStub;
use App\Models\Services;
use App\Jobs\SendNotification;
use App\Models\SalonBookingSlots;
use App\Models\EmailTemplate;
use App\Models\Taxes;
use App\Models\Fee;
use App\Jobs\SendEmail;
use App\Models\RevenueSetting;
use App\Models\Card;
use App\Models\LoyalityPoints;
use App\Models\UsedCoupon;
use App\Models\NotificationTemplate;
use App\Models\AdminNotificationTemplate;
use App\Jobs\SendAdminNotification;
use App\Models\Admin;
use App\Models\AdminEmailTemplate;
use Session;
use App\Models\Device;
use App\Models\UserNotification;
use App\Models\SalonCategories;
use App\Models\CardsTransaction;

class BookingsController extends Controller
{
    function index()
    {
        return view('bookings.index');
    }
    
    function list(Request $request)
    {
        $totalData =  Bookings::count();
        $rows = Bookings::with(['user', 'salon','service'])->orderBy('id', 'DESC')->get();
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
            $result = Bookings::with(['user', 'salon'])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  Bookings::with(['user', 'salon'])
                ->where(function ($query) use ($search) {
                    $query->Where('booking_id', 'LIKE', "%{$search}%")
                        ->orWhere('payable_amount', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Bookings::with(['user', 'salon'])
                ->where(function ($query) use ($search) {
                    $query->Where('booking_id', 'LIKE', "%{$search}%")
                        ->orWhere('payable_amount', 'LIKE', "%{$search}%");
                })
                ->count();
                
            if(!$totalFiltered){
                 $users_id= Users::where('profile_id','LIKE', "%{$search}%")
                          ->orWhere('first_name','LIKE', "%{$search}%")
                          ->orWhere('last_name','LIKE', "%{$search}%")
                          ->orWhere('phone_number','LIKE', "%{$search}%")
                          ->orWhere('email','LIKE', "%{$search}%")
                           ->orWhere('formated_number','LIKE', "%{$search}%")
                          ->pluck('id');
                $result =  Bookings::with(['user', 'salon'])->whereIn('user_id',$users_id)->get();
                $totalFiltered =  Bookings::with(['user', 'salon'])->whereIn('user_id',$users_id)->count();
            }        
                
        }
        
        $data = array();
        $i=1;
        foreach ($result as $item) {

            if(has_permission(session()->get('user_type'), 'view_bookings')){
                $view = '<a href="' . route('bookings.view', $item->id) . '" class="mr-2 btn btn-primary viewBtn text-white" rel=' . $item->id . ' ><i class="fa fa-eye faPostion"></i></a>';
                $print = '<a href="' . route('booking.invoice', $item->id) . '" class="mr-2 btn btn-success printBtn text-white" target="_blank" rel=' . $item->id . ' ><i class="fa fa-print faPostion"></i></a>';
            }else{
                $view = '';
                $print = '';
            }

            $action = $view.$print;

            $user = "";
            if ($item->user != null) {
                $user = '<a href="' . route('users.profile', $item->user->id) . '"><span class="">' . $item->user->profile_id . '</span></a>';
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
            
            $service = "";
            if ($item->service != null) {
                $service = '<a href="' . route('viewService', $item->service->id) . '"><span class="">' . $item->service->title . '</span></a>';
            }

            $dateTime =  date('d M Y',strtotime($item->date)) . '<br>' . date('h:i A',strtotime($item->time));
            $payableAmount = $settings->currency . $item->payable_amount;
            $full_name= '<a href="' . route('users.profile', $item->user->id) . '"><span class="">' . $item->user->first_name.' '.$item->user->last_name . '</span></a>';
            
            $contact=$item->user->formated_number.'<br>'.$item->user->email;
            
            $data[] = array(
                $i++,
                '<a href="' . route('bookings.view', $item->id) . '">'.$item->booking_id.'</a>',
                $service,
                $user,
                $full_name,
                $contact,
                $status,
                $dateTime,
                $settings->currency . number_format($item->service_amount, 2, '.', ','),
                $settings->currency . number_format($item->discount_amount, 2, '.', ','),
                $settings->currency . number_format($item->subtotal, 2, '.', ','),
                $payableAmount,
                GlobalFunction::formateTimeString($item->created_at),
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
    
    function pending(Request $request)
    {
        $totalData =  Bookings::where('status', Constants::orderPlacedPending)->count();
        $rows = Bookings::where('status', Constants::orderPlacedPending)
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
            $result = Bookings::where('status', Constants::orderPlacedPending)
                ->with(['user', 'salon'])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  Bookings::where('status', Constants::orderPlacedPending)
                ->with(['user', 'salon'])
                ->where(function ($query) use ($search) {
                    $query->Where('booking_id', 'LIKE', "%{$search}%")
                        ->orWhere('payable_amount', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Bookings::where(
                'status',
                Constants::orderPlacedPending
            )->with(['user', 'salon'])
                ->where(function ($query) use ($search) {
                    $query->Where('booking_id', 'LIKE', "%{$search}%")
                        ->orWhere('payable_amount', 'LIKE', "%{$search}%");
                })
                ->count();
                if(!$totalFiltered){
                 $users_id= Users::where('profile_id','LIKE', "%{$search}%")
                          ->orWhere('first_name','LIKE', "%{$search}%")
                          ->orWhere('last_name','LIKE', "%{$search}%")
                          ->orWhere('phone_number','LIKE', "%{$search}%")
                          ->orWhere('email','LIKE', "%{$search}%")
                           ->orWhere('formated_number','LIKE', "%{$search}%")
                          ->pluck('id');
                $result =  Bookings::with(['user', 'salon'])->whereIn('user_id',$users_id)->get();
                $totalFiltered =  Bookings::with(['user', 'salon'])->whereIn('user_id',$users_id)->count();
            }    
                
        }
        
        $data = array();
        $i=1;
        foreach ($result as $item) {

            if(has_permission(session()->get('user_type'), 'view_bookings')){
                $view = '<a href="' . route('bookings.view', $item->id) . '" class="mr-2 btn btn-primary viewBtn text-white" rel=' . $item->id . ' ><i class="fa fa-eye faPostion"></i></a>';
                $print = '<a href="' . route('booking.invoice', $item->id) . '" class="mr-2 btn btn-success printBtn text-white" target="_blank" rel=' . $item->id . ' ><i class="fa fa-print faPostion"></i></a>';
            }else{
                $view = '';
                $print = '';
            }

            $action = $view.$print;

            $user = "";
            if ($item->user != null) {
                $user = '<a href="' . route('users.profile', $item->user->id) . '"><span class="">' . $item->user->profile_id . '</span></a>';
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

            $dateTime =  date('d M Y',strtotime($item->date)) . '<br>' . date('h:i A',strtotime($item->time));

            $payableAmount = $settings->currency . $item->payable_amount;
            $full_name= '<a href="' . route('users.profile', $item->user->id) . '"><span class="">' . $item->user->first_name.' '.$item->user->last_name . '</span></a>';
            $contact=$item->user->formated_number.'<br>'.$item->user->email;
            $data[] = array(
                $i++,
                '<a href="' . route('bookings.view', $item->id) . '">'.$item->booking_id.'</a>',
                $user,
                $full_name,
                $contact,
                $status,
                $dateTime,
                $settings->currency . number_format($item->service_amount, 2, '.', ','),
                $settings->currency . number_format($item->discount_amount, 2, '.', ','),
                $settings->currency . number_format($item->subtotal, 2, '.', ','),
                $payableAmount,
                GlobalFunction::formateTimeString($item->created_at),
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
    
    function accepted(Request $request)
    {
        $totalData =  Bookings::where('status', Constants::orderAccepted)->count();
        $rows = Bookings::where('status', Constants::orderAccepted)
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
            $result = Bookings::where('status', Constants::orderAccepted)
                ->with(['user', 'salon'])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  Bookings::where('status', Constants::orderAccepted)
                ->with(['user', 'salon'])
                ->where(function ($query) use ($search) {
                    $query->Where('booking_id', 'LIKE', "%{$search}%")
                        ->orWhere('payable_amount', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Bookings::where(
                'status',
                Constants::orderAccepted
            )->with(['user', 'salon'])
                ->where(function ($query) use ($search) {
                    $query->Where('booking_id', 'LIKE', "%{$search}%")
                        ->orWhere('payable_amount', 'LIKE', "%{$search}%");
                })
                ->count();
               if(!$totalFiltered){
                 $users_id= Users::where('profile_id','LIKE', "%{$search}%")
                          ->orWhere('first_name','LIKE', "%{$search}%")
                          ->orWhere('last_name','LIKE', "%{$search}%")
                          ->orWhere('phone_number','LIKE', "%{$search}%")
                          ->orWhere('email','LIKE', "%{$search}%")
                           ->orWhere('formated_number','LIKE', "%{$search}%")
                          ->pluck('id');
                $result =  Bookings::with(['user', 'salon'])->whereIn('user_id',$users_id)->get();
                $totalFiltered =  Bookings::with(['user', 'salon'])->whereIn('user_id',$users_id)->count();
            }     
        }
        
        $data = array();
        $i=1;
        foreach ($result as $item) {
            
            if(has_permission(session()->get('user_type'), 'view_bookings')){
                $view = '<a href="' . route('bookings.view', $item->id) . '" class="mr-2 btn btn-primary viewBtn text-white" rel=' . $item->id . ' ><i class="fa fa-eye faPostion"></i></a>';
                $print = '<a href="' . route('booking.invoice', $item->id) . '" class="mr-2 btn btn-success printBtn text-white" target="_blank" rel=' . $item->id . ' ><i class="fa fa-print faPostion"></i></a>';
            }else{
                $view = '';
                $print = '';
            }

            $action = $view.$print;

            $user = "";
            if ($item->user != null) {
                $user = '<a href="' . route('users.profile', $item->user->id) . '"><span class="">' . $item->user->profile_id . '</span></a>';
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

            $dateTime =  date('d M Y',strtotime($item->date)) . '<br>' . date('h:i A',strtotime($item->time));

            $payableAmount = $settings->currency . $item->payable_amount;
            $full_name= '<a href="' . route('users.profile', $item->user->id) . '"><span class="">' . $item->user->first_name.' '.$item->user->last_name . '</span></a>';
            $contact=$item->user->formated_number.'<br>'.$item->user->email;
            $data[] = array(
                $i++,
                '<a href="' . route('bookings.view', $item->id) . '">'.$item->booking_id.'</a>',
                $user,
                $full_name,
                $contact,
                $status,
                $dateTime,
                $settings->currency . number_format($item->service_amount, 2, '.', ','),
                $settings->currency . number_format($item->discount_amount, 2, '.', ','),
                $settings->currency . number_format($item->subtotal, 2, '.', ','),
                $payableAmount,
                GlobalFunction::formateTimeString($item->created_at),
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
    
    function completed(Request $request)
    {
        $totalData =  Bookings::where('status', Constants::orderCompleted)->count();
        $rows = Bookings::where('status', Constants::orderCompleted)
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
            $result = Bookings::where('status', Constants::orderCompleted)
                ->with(['user', 'salon'])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  Bookings::where('status', Constants::orderCompleted)
                ->with(['user', 'salon'])
                ->where(function ($query) use ($search) {
                    $query->Where('booking_id', 'LIKE', "%{$search}%")
                        ->orWhere('payable_amount', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Bookings::where(
                'status',
                Constants::orderCompleted
            )->with(['user', 'salon'])
                ->where(function ($query) use ($search) {
                    $query->Where('booking_id', 'LIKE', "%{$search}%")
                        ->orWhere('payable_amount', 'LIKE', "%{$search}%");
                })
                ->count();
          
             if(!$totalFiltered){
                 $users_id= Users::where('profile_id','LIKE', "%{$search}%")
                          ->orWhere('first_name','LIKE', "%{$search}%")
                          ->orWhere('last_name','LIKE', "%{$search}%")
                          ->orWhere('phone_number','LIKE', "%{$search}%")
                          ->orWhere('email','LIKE', "%{$search}%")
                           ->orWhere('formated_number','LIKE', "%{$search}%")
                          ->pluck('id');
                $result =  Bookings::with(['user', 'salon'])->whereIn('user_id',$users_id)->get();
                $totalFiltered =  Bookings::with(['user', 'salon'])->whereIn('user_id',$users_id)->count();
            }         
        }
        $data = array();
        $i=1;
        foreach ($result as $item) {

            if(has_permission(session()->get('user_type'), 'view_bookings')){
                $view = '<a href="' . route('bookings.view', $item->id) . '" class="mr-2 btn btn-primary viewBtn text-white" rel=' . $item->id . ' ><i class="fa fa-eye faPostion"></i></a>';
                $print = '<a href="' . route('booking.invoice', $item->id) . '" class="mr-2 btn btn-success printBtn text-white" target="_blank" rel=' . $item->id . ' ><i class="fa fa-print faPostion"></i></a>';
            }else{
                $view = '';
                $print = '';
            }

            $action = $view.$print;

            $user = "";
            if ($item->user != null) {
                $user = '<a href="' . route('users.profile', $item->user->id) . '"><span class="">' . $item->user->profile_id . '</span></a>';
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

            $dateTime =  date('d M Y',strtotime($item->date)) . '<br>' . date('h:i A',strtotime($item->time));

            $payableAmount = $settings->currency . $item->payable_amount;
            $full_name= '<a href="' . route('users.profile', $item->user->id) . '"><span class="">' . $item->user->first_name.' '.$item->user->last_name . '</span></a>';
            $contact=$item->user->formated_number.'<br>'.$item->user->email;
            $data[] = array(
                $i++,
                '<a href="' . route('bookings.view', $item->id) . '">'.$item->booking_id.'</a>',
                $user,
                $full_name,
                $contact,
                $status,
                $dateTime,
                $settings->currency . number_format($item->service_amount, 2, '.', ','),
                $settings->currency . number_format($item->discount_amount, 2, '.', ','),
                $settings->currency . number_format($item->subtotal, 2, '.', ','),
                $payableAmount,
                GlobalFunction::formateTimeString($item->created_at),
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
    
    function cancelled(Request $request)
    {
        $totalData =  Bookings::where('status', Constants::orderCancelled)->count();
        $rows = Bookings::where('status', Constants::orderCancelled)
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
            $result = Bookings::where('status', Constants::orderCancelled)
                ->with(['user', 'salon'])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  Bookings::where('status', Constants::orderCancelled)
                ->with(['user', 'salon'])
                ->where(function ($query) use ($search) {
                    $query->Where('booking_id', 'LIKE', "%{$search}%")
                        ->orWhere('payable_amount', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Bookings::where(
                'status',
                Constants::orderCancelled
            )->with(['user', 'salon'])
                ->where(function ($query) use ($search) {
                    $query->Where('booking_id', 'LIKE', "%{$search}%")
                        ->orWhere('payable_amount', 'LIKE', "%{$search}%");
                })
                ->count();
               if(!$totalFiltered){
                 $users_id= Users::where('profile_id','LIKE', "%{$search}%")
                          ->orWhere('first_name','LIKE', "%{$search}%")
                          ->orWhere('last_name','LIKE', "%{$search}%")
                          ->orWhere('phone_number','LIKE', "%{$search}%")
                          ->orWhere('email','LIKE', "%{$search}%")
                           ->orWhere('formated_number','LIKE', "%{$search}%")
                          ->pluck('id');
                $result =  Bookings::with(['user', 'salon'])->whereIn('user_id',$users_id)->get();
                $totalFiltered =  Bookings::with(['user', 'salon'])->whereIn('user_id',$users_id)->count();
            } 
                
        }
        $data = array();
        $i=1;
        foreach ($result as $item) {

            if(has_permission(session()->get('user_type'), 'view_bookings')){
                $view = '<a href="' . route('bookings.view', $item->id) . '" class="mr-2 btn btn-primary viewBtn text-white" rel=' . $item->id . ' ><i class="fa fa-eye faPostion"></i></a>';
                $print = '<a href="' . route('booking.invoice', $item->id) . '" class="mr-2 btn btn-success printBtn text-white" target="_blank" rel=' . $item->id . ' ><i class="fa fa-print faPostion"></i></a>';
            }else{
                $view = '';
                $print = '';
            }

            $action = $view.$print;

            $user = "";
            if ($item->user != null) {
                $user = '<a href="' . route('users.profile', $item->user->id) . '"><span class="">' . $item->user->profile_id . '</span></a>';
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

            $dateTime =  date('d M Y',strtotime($item->date)) . '<br>' . date('h:i A',strtotime($item->time));

            $payableAmount = $settings->currency . $item->payable_amount;
            $full_name= '<a href="' . route('users.profile', $item->user->id) . '"><span class="">' . $item->user->first_name.' '.$item->user->last_name . '</span></a>';
            $contact=$item->user->formated_number.'<br>'.$item->user->email;
            $data[] = array(
                $i++,
                '<a href="' . route('bookings.view', $item->id) . '">'.$item->booking_id.'</a>',
                $user,
                $full_name,
                $contact,
                $status,
                $dateTime,
                $settings->currency . number_format($item->service_amount, 2, '.', ','),
                $settings->currency . number_format($item->discount_amount, 2, '.', ','),
                $settings->currency . number_format($item->subtotal, 2, '.', ','),
                $payableAmount,
                GlobalFunction::formateTimeString($item->created_at),
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
    
    function declined(Request $request)
    {
        $totalData =  Bookings::where('status', Constants::orderDeclined)->count();
        $rows = Bookings::where('status', Constants::orderDeclined)
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
            $result = Bookings::where('status', Constants::orderDeclined)
                ->with(['user', 'salon'])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  Bookings::where('status', Constants::orderDeclined)
                ->with(['user', 'salon'])
                ->where(function ($query) use ($search) {
                    $query->Where('booking_id', 'LIKE', "%{$search}%")
                        ->orWhere('payable_amount', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Bookings::where(
                'status',
                Constants::orderDeclined
            )->with(['user', 'salon'])
                ->where(function ($query) use ($search) {
                    $query->Where('booking_id', 'LIKE', "%{$search}%")
                        ->orWhere('payable_amount', 'LIKE', "%{$search}%");
                })
                ->count();
             if(!$totalFiltered){
                 $users_id= Users::where('profile_id','LIKE', "%{$search}%")
                          ->orWhere('first_name','LIKE', "%{$search}%")
                          ->orWhere('last_name','LIKE', "%{$search}%")
                          ->orWhere('phone_number','LIKE', "%{$search}%")
                          ->orWhere('email','LIKE', "%{$search}%")
                           ->orWhere('formated_number','LIKE', "%{$search}%")
                          ->pluck('id');
                $result =  Bookings::with(['user', 'salon'])->whereIn('user_id',$users_id)->get();
                $totalFiltered =  Bookings::with(['user', 'salon'])->whereIn('user_id',$users_id)->count();
            }         
        }
        $data = array();
        $i=1;
        foreach ($result as $item) {
            
            if(has_permission(session()->get('user_type'), 'view_bookings')){
                $view = '<a href="' . route('bookings.view', $item->id) . '" class="mr-2 btn btn-primary viewBtn text-white" rel=' . $item->id . ' ><i class="fa fa-eye faPostion"></i></a>';
                $print = '<a href="' . route('booking.invoice', $item->id) . '" class="mr-2 btn btn-success printBtn text-white" target="_blank" rel=' . $item->id . ' ><i class="fa fa-print faPostion"></i></a>';
            }else{
                $view = '';
                $print = '';
            }

            $action = $view.$print;

            $user = "";
            if ($item->user != null) {
                $user = '<a href="' . route('users.profile', $item->user->id) . '"><span class="">' . $item->user->profile_id . '</span></a>';
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

            $dateTime =  date('d-m-Y',strtotime($item->date)) . '<br>' . date('h:i A',strtotime($item->time));

            $payableAmount = $settings->currency . $item->payable_amount;
             
             $full_name= '<a href="' . route('users.profile', $item->user->id) . '"><span class="">' . $item->user->first_name.' '.$item->user->last_name . '</span></a>';
            $contact=$item->user->formated_number.'<br>'.$item->user->email;

            $data[] = array(
                $i++,
                '<a href="' . route('bookings.view', $item->id) . '">'.$item->booking_id.'</a>',
                $user,
                $full_name,
                $contact,
                $status,
                $dateTime,
                $settings->currency . number_format($item->service_amount, 2, '.', ','),
                $settings->currency . number_format($item->discount_amount, 2, '.', ','),
                $settings->currency . number_format($item->subtotal, 2, '.', ','),
                $payableAmount,
                GlobalFunction::formateTimeString($item->created_at),
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
    
    function view($id)
    {
        $booking = Bookings::find($id);
        $settings = GlobalSettings::first();

        $starDisabled = '<i class="fas fa-star starDisabled"></i>';
        $starActive = '<i class="fas fa-star starActive"></i>';

        $ratingBar = '';
        if ($booking->rating != null) {
            for ($i = 0; $i < 5; $i++) {
                if ($booking->rating->rating > $i) {
                    $ratingBar = $ratingBar . $starActive;
                } else {
                    $ratingBar = $ratingBar . $starDisabled;
                }
            }
        }

        $bookingSummary = json_decode($booking->services, true);

        return view('bookings.view', [
            'booking' => $booking,
            'ratingBar' => $ratingBar,
            'settings' => $settings,
            'bookingSummary' => $bookingSummary,
        ]);
    }
    
    public function booking_invoice($id)
    {
        $booking = Bookings::where('id', $id)->first();
        $user = Users::where('id', $booking->user_id)->first();
        $service = Services::where('id', $booking->service_id)->first();
        $tax = Taxes::where('id', '1')->first();
        $settings = GlobalSettings::first();
        return view('invoice.booking', [
            'booking' => $booking,
            'user' => $user,
            'service' => $service,
            'tax' => $tax,
            'settings' => $settings,
        ]);
    }
    
    function status($booking_id,$status)
    {  
        $booking = Bookings::where('id', $booking_id)->with(['salon', 'user'])->first();
        if ($booking == null) {
            return back()->with(['booking_cancel' => 'Booking does not exists!']);
        }
        $user = Users::find($booking->user_id);
        if ($user == null) {
            return back()->with(['booking_cancel' => 'User does not exists!']);
        }
        if ($booking->user_id != $user->id) {
            return back()->with(['booking_cancel' => 'This booking does not belong to this user']);
        }
        if ($booking->status == Constants::orderCancelled || $booking->status == Constants::orderDeclined || $booking->status == Constants::orderCompleted) {
            return back()->with(['booking_cancel' => 'This booking is not eligible to be change status!']);
        }
        
        if($booking->payment_method=='wallet'){
            $booking_type="wallet_booking";
        }else{
          $booking_type="card_booking";
        }
        
        if($status==2){
            $salon = Salons::where('id',1)->first();
            
            $booking->status = Constants::orderCompleted;
            $booking->save();

            $earning = $booking->subtotal;
            $settings = GlobalSettings::first();
            $commissionAmount = ($settings->comission / 100) * $earning;
            
            $revenue_per= RevenueSetting::latest()->first();
            $ibp_revenue = ($revenue_per->ibp_revenue/100)*$earning;
            $account_maintenance = ($revenue_per->account_maintenance/100)*$earning;
            $technical_support= ($revenue_per->technical_support/100)*$earning;

            $earningSummary = "Earning from booking: " . $booking->booking_id;
            GlobalFunction::addSalonStatementEntry($salon->id, $booking->booking_id, $earning, Constants::credit, Constants::salonWalletEarning, $earningSummary);

            $commissionSummary = "Commission of booking: " . $booking->booking_id . " : (" . $settings->comission . "%)";
            GlobalFunction::addSalonStatementEntry($salon->id, $booking->booking_id, $commissionAmount, Constants::debit, Constants::salonWalletCommission, $commissionSummary);

            $earningAfterCommission = $earning - $commissionAmount;
            $salon->wallet = $salon->wallet + $earningAfterCommission;
            $salon->total_completed_bookings = $salon->total_completed_bookings + 1;
            $salon->lifetime_earnings = $salon->lifetime_earnings + $earningAfterCommission;
            $salon->save();

            $salonEarningHistory = new SalonEarningHistory();
            $salonEarningHistory->salon_id = $salon->id;
            $salonEarningHistory->booking_id = $booking->id;
            $salonEarningHistory->earning_number = GlobalFunction::generateSalonEarningHistoryNumber();
            $salonEarningHistory->amount = $earningAfterCommission;
            $salonEarningHistory->save();

            $platformEarningHistory = new PlatformEarningHistory();
            $platformEarningHistory->type = $booking_type;
            $platformEarningHistory->earning_number = GlobalFunction::generatePlatformEarningHistoryNumber();
            $platformEarningHistory->amount = $commissionAmount;
            $platformEarningHistory->ibp_revenue = $ibp_revenue;
            $platformEarningHistory->account_maintenance = $account_maintenance;
            $platformEarningHistory->technical_support = $technical_support;
                                
            $platformEarningHistory->commission_percentage = $settings->comission;
            $platformEarningHistory->booking_id = $booking->id;
            $platformEarningHistory->salon_id = $salon->id;
            $platformEarningHistory->save();

            $platformData = PlatformData::first();
            $platformData->lifetime_earnings = $platformData->lifetime_earnings + $commissionAmount;
            $platformData->save();
            
            if($booking->payment_method=='card'){
                $cardDetails = Card::where('assigned_to',$booking->user_id)->where('status',1)->first();
                if($settings->loyality_points && $settings->loyality_amount && !empty($cardDetails) ){
                    $loyality_points= $settings->loyality_points*($earning/$settings->loyality_amount);
                    $total_points_updated= $cardDetails->loyality_points+$loyality_points;  
                    $cardDetails->loyality_points=$total_points_updated;
                    $cardDetails->save();
                    
                    $LoyalityPoints=new LoyalityPoints();
                    $LoyalityPoints->user_id=$booking->user_id;
                    $LoyalityPoints->booking_id=$booking->booking_id;
                    $LoyalityPoints->points=$loyality_points;
                    $LoyalityPoints->save();
                }
            }

            $title = "Booking : " . $booking->booking_id;
            $message = "Booking has been completed successfully!";
            $type="booking";
            dispatch(new SendNotification($title,$message,$type,$booking->user_id));
            GlobalFunction::sendPushToUser($title, $message, $user);

            return response()->json(['status' => true, 'message' => "Booking completed by provider successfully!"]);
        } 
     
        if($status==4){
            $booking->status = Constants::orderCancelled;
            $booking->save();
    
            $user->wallet = $user->wallet + $booking->payable_amount;
            $user->save();
            
            $summary = 'Booking Cancelled By Admin: ' . $booking->booking_id . ' Refund';
            GlobalFunction::addUserStatementEntry($user->id, $booking->booking_id, $booking->payable_amount, Constants::credit, Constants::refund, $summary);
    
            $title = "Booking :" . $booking->booking_id;
            $message = "Booking has been cancelled by provider successfully!";
            $type="booking";
            dispatch(new SendNotification($title,$message,$type,$booking->user_id));
            GlobalFunction::sendPushToUser($title, $message, $user);
    
            return back()->with(['booking_cancel' => 'Booking cancelled successfully']);
        }
    }
    
    public function available(Request $request)
    {
        $rules = [
            'date' => 'required',
            'service_id'=>'required',
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        $salon = Services::where('id',$request->service_id)->first();
        if ($salon == null) {
            return response()->json(['status' => false, 'message' => "Service doesn't exists!"]);
        }
        
        $user = Users::where('id',$request->user_id)->first();
        if ($user == null) {
            return response()->json(['status' => false, 'message' => "User doesn't exists!"]);
        }
        
        $usertype = $user->user_type;
        if($usertype == '0'){
            $booking_price = number_format($salon->price, 2, '.', ',');
            $booking_discount = $salon->discount;
        }else{
            $booking_price = number_format($salon->foreiner_price, 2, '.', ',');
            $booking_discount = $salon->foreiner_discount;
        }
        
        $discounted_price = number_format($booking_price - ($booking_discount*$booking_price)/100, 2, '.', ',');
        $quantity = $request->booking_numbers;
        if(empty($request->booking_hours)){
            $booking_hours = '1';
        }else{
            $booking_hours = $request->booking_hours;
        }
        $total_amount = number_format($booking_price*$booking_hours*$quantity, 2, '.', ',');
        $payable_amount = number_format($discounted_price*$booking_hours*$quantity, 2, '.', ',');
        $item_name = $salon->title;
        $taxes=Taxes::first();
        $booking_tax = $taxes->value;
            
        $bookings = Bookings::select(['id','booking_hours','service_id','date','time','quantity','status'])->where('service_id',$request->service_id)
            ->where('date', date('Y-m-d',strtotime($request->date)))
            ->whereIn('status', [Constants::orderPlacedPending, Constants::orderAccepted])
            ->get();
            
        $date = date('l', strtotime($request->date));    
        $days = array(1=>'Monday',2=>'Tuesday',3=>'Wednesday',4=>'Thursday',5=>'Friday',6=>'Saturday',7=>'Sunday');
        $key = array_search($date, $days); 
        
        Session::put('date',$request->date);
        Session::put('booking_hours',$request->booking_hours);
        Session::put('booking_price',$booking_price);
        Session::put('booking_discount',$booking_discount);
        Session::put('payable_amount',$payable_amount);
        Session::put('discounted_price',$discounted_price);
        Session::put('item_name',$item_name);
        Session::put('quantity',$quantity);
        Session::put('booking_tax',$booking_tax);
        Session::put('total_amount',$total_amount);
        Session::put('service_type',$salon->service_type);
        
        $serviceBookingHourSlots= SalonBookingSlots::select(['id','booking_hours','salon_id as service_id','time','booking_limit'])->where('salon_id',$request->service_id)->where('booking_hours',$request->booking_hours)->where('weekday',$key)->orderBy('time','ASC')->get();
        
        if(count($bookings) > 0){
            foreach($serviceBookingHourSlots as $updateslots){
                
                $current_time = date('H:i');
                if(date('H:i',strtotime($updateslots->time)) < $current_time){
                    $updateslots->booking_limit=0 ;
                }
                
                if($salon->service_type){
                    $bookings_slots = Bookings::select(['id','booking_hours','service_id','date','time','quantity','status'])->where('service_id',$request->service_id)
                    ->where('date', date('Y-m-d',strtotime($request->date)))
                    ->where('booking_hours',$request->booking_hours)
                    ->whereIn('status', [Constants::orderPlacedPending, Constants::orderAccepted])
                    ->get();
                    
                    foreach($bookings_slots as $bookings_slot){
                        $next_time = date('H:i',strtotime($bookings_slot->time. '+'.$request->booking_hours.' hours'));
                        $prev_time = date('H:i',strtotime('-'.$request->booking_hours.' hours', strtotime($bookings_slot->time)));
                        
                        if(date('H:i',strtotime($bookings_slot->time)) <= date('H:i',strtotime($updateslots->time)) && date('H:i',strtotime($updateslots->time)) < $next_time){
                            $updateslots->booking_limit = 0;
                        }
                        
                        if(date('H:i',strtotime($bookings_slot->time)) >= date('H:i',strtotime($updateslots->time)) && date('H:i',strtotime($updateslots->time)) > $prev_time){
                            $updateslots->booking_limit = 0;
                        }
                    }
                }else{
                    $bookings_slots = Bookings::select(['id','booking_hours','service_id','date','time','quantity','status'])->where('service_id',$request->service_id)
                    ->where('date', date('Y-m-d',strtotime($request->date)))
                    ->where('booking_hours',$request->booking_hours)
                    ->whereIn('status', [Constants::orderPlacedPending, Constants::orderAccepted])
                    ->get();
                     foreach($bookings_slots as $bookings_slot){
                           if($bookings_slot->booking_hours!=16){
                        $next_time = date('H:i',strtotime($bookings_slot->time. '+'.$request->booking_hours.' hours'));
                        $prev_time = date('H:i',strtotime('-'.$request->booking_hours.' hours', strtotime($bookings_slot->time)));
                        
                         if($bookings_slot->time==$updateslots->time){
                            $updateslots->booking_limit=$updateslots->booking_limit-$bookings_slot->quantity;
                        }
                      
                        if(date('H:i',strtotime($bookings_slot->time)) < date('H:i',strtotime($updateslots->time)) && date('H:i',strtotime($updateslots->time)) < $next_time){
                            // $updateslots->booking_limit = 0;
                            $updateslots->booking_limit=$updateslots->booking_limit-$bookings_slot->quantity;
                            if($updateslots->booking_limit<=0){
                               $updateslots->booking_limit=0 ;
                            }
                        }
                        if(date('H:i',strtotime($bookings_slot->time)) > date('H:i',strtotime($updateslots->time)) && date('H:i',strtotime($updateslots->time)) > $prev_time){

                            $updateslots->booking_limit=$updateslots->booking_limit-$bookings_slot->quantity;
                             if($updateslots->booking_limit<=0){
                               $updateslots->booking_limit=0 ;
                            }
                        }
                           }
                        if($bookings_slot->booking_hours==16){
                            if($updateslots->booking_limit-$bookings_slot->quantity<=0){
                                 $updateslots->booking_limit=0 ;
                            }else{
                            $updateslots->booking_limit=$updateslots->booking_limit-$bookings_slot->quantity;
                            }
                        }
                        
                    }
                }
                $updateslots->time = date('h:i A',strtotime($updateslots->time));
            }
        }else{
            foreach($serviceBookingHourSlots as $updateslots){
                $updateslots->time=date('h:i A',strtotime($updateslots->time)); 
            }
        }
        
        $bookings['slots'] = $serviceBookingHourSlots;
        $serviceBookingHour = SalonBookingSlots::where('salon_id',$request->service_id)->where('weekday',$key)->pluck('booking_hours');
        $booking_hours = $serviceBookingHour->sort()->unique()->toArray();
        
        $New_start_index = 0; 
        $booking_hours = array_combine(range($New_start_index,  
            count($booking_hours) + ($New_start_index-1)), 
            array_values($booking_hours));
        $bookings['booking_hours']=$booking_hours;
        
        $bookings['booking_price'] = $booking_price; 
        $bookings['booking_discount'] = $booking_discount; 
        $bookings['payable_amount'] = $payable_amount; 
        $bookings['discounted_price'] = $discounted_price; 
        $bookings['item_name'] = $item_name;
        $bookings['quantity'] = $quantity; 
        $bookings['booking_tax'] = $booking_tax; 
        $bookings['total_amount'] = $total_amount;
        $bookings['service_type'] = $salon->service_type; 
        $bookings['maximum_quantity'] = $salon->qauntity; 
            
        return GlobalFunction::sendDataResponse(true, 'Bookings fetched successfully', $bookings);
    }
    
    function place_booking(Request $request)
    {  
        $rules = [
            'user_id' => 'required',
            'service_id'=>'required',
            'date' => 'required',
            'booking_hours' => 'required',
            'slot' => 'required',
            'quantity'=>'required',
        ];
      
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return back()->with(['booking_message' => $msg]);
        }

        $settings = GlobalSettings::first();
        $taxes=Taxes::first();
        $service_id=$request->service_id;
        $user_id=$request->user_id;
        $quantity=$request->quantity;
        $booking_hours=$request->booking_hours;
        $settings = GlobalSettings::first();
        $date=$request->date;
        $booking_time=$request->slot;
        
        $service = Services::find($service_id);
          
        if(session()->get('locale')=="pap"){
            $title=  "title_in_papiamentu as title"; 
            $rules=  "rules_in_papiamentu as rules"; 
            $about=  "about_in_papiamentu as about";
        }elseif(session()->get('locale')=="nl"){
            $title=  "title_in_dutch as title";  
            $rules=  "rules_in_dutch as rules"; 
            $about=  "about_in_dutch as about";
        }else{
            $title=  "title as title"; 
            $rules=  "rules as rules"; 
            $about=  "about as about";
        }
          
        if ($service == null) {
            return back()->with(['booking_message' => "Service doesn't exists!"]);
        }
        
        $user = Users::find($user_id);
        if ($user == null) {
            return back()->with(['booking_message' => "User doesn't exists!"]);
        }
        
        if($user->user_type == '1'){
            $service = Services::select(['id','service_type',$title,'thumbnail','category_id','salon_id','slug','service_number','service_time',$about,$rules,'qauntity','foreiner_price as price','foreiner_price_per_day as price_per_day','slug','foreiner_discount as discount'])->where('status',1)->where('id',$service_id)->with(['images','salon','salon.images','salon.gallery'])->first();
        }else{
            $service = Services::select(['id','service_type',$title,'service_number','category_id','salon_id','slug','thumbnail','service_time',$about,$rules,'qauntity','price','price_per_day','discount','slug'])->where('status',1)->where('id',$service_id)->with(['images','salon','salon.images','salon.gallery'])->first(); 
        } 

        $bookingsCount = Bookings::where('user_id', $user_id)
            ->whereIn('status', [Constants::orderPlacedPending, Constants::orderAccepted])
            ->count();
        if ($bookingsCount >= $settings->max_order_at_once) {
            return back()->with(['booking_message' => "Maximum at a time order limit reached!"]);
        }

        $salon = Salons::find($request->salon_id);
        
        $service_amount = number_format($service->price-($service->price*$service->discount)/100,2);
        
        if($service->service_type == '1'){
            $payable_amount = $service_amount*Session::get('booking_hours');
        }else{
            $payable_amount = $service_amount*$quantity*Session::get('booking_hours');
        }
        
        $ibpcards = Card::where('assigned_to',$user_id)->first();
       
        if($request->inlineRadioOptions=='wallet'){
            if ($user->wallet < $payable_amount) {
                return back()->with(['booking_message' => "Insufficient balance in wallet"]);
            }
        }else{
            if($ibpcards){   
                if ($ibpcards->balance < $payable_amount) {
                    return back()->with(['booking_message' => "Insufficient balance in card"]);
                }  
            }else{
                return back()->with(['booking_message' => "Card not assigned to this user"]);
            }
        }
        
        $discount_amount = 0;
        $taxes=Taxes::first();
        $taxes['tax_amount']=(float)$taxes->value;
        $service_details['service_amount']=(float)$service_amount;
        $service_details['discount_amount']=(float)$discount_amount;
        $service_details['subtotal']=(float)$payable_amount;
        
        $service_details['total_tax_amount']=(float)$taxes->value;
        $service_details['payable_amount']=(float)$payable_amount;
        $service_details['coupon_apply']=0;
        $service_details['taxes']=array($taxes);
        $service_details['services']=array($service);        // dd($taxes);

        $booking = new Bookings();
        $booking->booking_id = GlobalFunction::generateBookingId();
        $booking->completion_otp = rand(1000, 9999);
        $booking->user_id = $user_id;
        $booking->salon_id = 1;
        $booking->service_id  = $service_id ;
        $booking->booking_hours = $booking_hours;
        $booking->date = date('Y-m-d',strtotime($date));
        $booking->time = date("Hi",strtotime($booking_time));
        $booking->duration = $service->service_time;
        $booking->services = json_encode($service_details);
        $booking->is_coupon_applied = $request->is_coupon_applied;
        $booking->order_by=session()->get('user_name');
        $booking->quantity = $quantity;
        $booking->service_amount = $service_amount;
        $booking->discount_amount = $discount_amount;
        $booking->subtotal = $payable_amount;
        $booking->total_tax_amount =  $taxes->value; //$request->total_tax_amount;
        $booking->payable_amount = $payable_amount;
        $booking->payment_method = $request->inlineRadioOptions;

        if ($request->is_coupon_applied == 1) {
            $booking->coupon_title = $request->coupon_title;
            $discounts = explode(',', $user->coupons_used);
            array_push($discounts, $request->coupon_id);
            $user->coupons_used = implode(',', $discounts);
        }

        $booking->save();
        
        if($request->inlineRadioOptions=='wallet'){
            $user->wallet = $user->wallet - $payable_amount;
            $user->save();
            
            $booking_type = 'wallet_booking';
         
            GlobalFunction::addUserStatementEntry(
                $user->id,
                $booking->booking_id,
                $payable_amount,
                Constants::debit,
                Constants::purchase,
                null
            );
        }else{
            $ibpcards->balance=$ibpcards->balance-$payable_amount;
            $ibpcards->save();
            
            $CardsTransaction =new CardsTransaction();
            $CardsTransaction->user_id=$user_id;
            $CardsTransaction->card_id=$ibpcards->id;
            $CardsTransaction->transaction_id = GlobalFunction::generateTransactionId();
            $CardsTransaction->booking_id =$booking->booking_id;
            $CardsTransaction->type= 'purchase';
            $CardsTransaction->amount =$payable_amount;
            $CardsTransaction->save();
            
            $booking_type = 'card_booking';
        }
        
        $earning  = $payable_amount;
        $settings = GlobalSettings::first();
        $commissionAmount = ($settings->comission / 100) * $earning;
        
        $revenue_per= RevenueSetting::latest()->first();
        $ibp_revenue = ($revenue_per->ibp_revenue/100)*$earning;
        $account_maintenance = ($revenue_per->account_maintenance/100)*$earning;
        $technical_support= ($revenue_per->technical_support/100)*$earning;

        $platformEarningHistory = new PlatformEarningHistory();
        $platformEarningHistory->type = $booking_type;
        $platformEarningHistory->earning_number = GlobalFunction::generatePlatformEarningHistoryNumber();
        $platformEarningHistory->amount = $commissionAmount;
        $platformEarningHistory->ibp_revenue = $ibp_revenue;
        $platformEarningHistory->account_maintenance = $account_maintenance;
        $platformEarningHistory->technical_support = $technical_support;
        $platformEarningHistory->commission_percentage = $settings->comission;
        $platformEarningHistory->booking_id = $booking->id;
        $platformEarningHistory->salon_id = 1;
        $platformEarningHistory->save();
        
        $platformData = PlatformData::first();
        $platformData->lifetime_earnings = $platformData->lifetime_earnings + $commissionAmount;
        $platformData->save();
        
        $booking = Bookings::where('id', $booking->id)->first();
     
        $title = "Booking :" . $booking->booking_id;
        $message = "Booking has been placed";
        $type="booking";
         
        try{
            $user_name=ucfirst($user->first_name.' '.$user->last_name);
            $booked_on=date('D, d F Y',strtotime($booking->created_at));
            $schedule_at= date('D, d F Y',strtotime($booking->date)).'   '.date('h:i A', strtotime($booking->time)).'-'.date('h:i A',strtotime('+'.$booking->booking_hours.'hour',strtotime($booking->time)));
            $item_price= $settings->currency.number_format($service_amount,2);
            $amount= $settings->currency.number_format($payable_amount,2);
            
            $check_device = Device::where('user_id', $user->id)->first();
            if(!empty($check_device->language)){
                $act_lang = $check_device->language;
            }else{
                $act_lang = '1';
            }
            
            $email_template = EmailTemplate::find(1);
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
            
            $content=str_replace(["{user}","{Booking_id}","{booked_on}","{item_name}","{item_price}","{quantity}","{duration}","{created_at}","{code}","{amount}","{tax}"],
            [$user_name,$booking->booking_id,$booked_on,$service->title,$item_price,$quantity,$booking_hours,$schedule_at,$booking->completion_otp,$amount,$taxes->value],$content);

            $details=[         
                "subject"=>$subject ,
                "message"=>$content,
                "to"=>$user->email,
            ];
            send_email($details);
            
            $superAdmin = Admin::where('user_type',1)->first();
            $adminbookingsuccesemail = AdminEmailTemplate::find(1);
            $admin_subject = $adminbookingsuccesemail->email_subjects;
            $admin_subject = str_replace(['{user}'],[$user_name],$admin_subject);
            $admin_content = $adminbookingsuccesemail->email_content; 
            $admin_content=str_replace(["{user}","{Booking_id}","{booked_on}","{item_name}","{item_price}","{quantity}","{duration}","{created_at}","{code}","{amount}","{tax}"],
            [$user_name,$booking->booking_id,$booked_on,$service->title,$item_price,$quantity,$booking_hours,$schedule_at,$booking->completion_otp,$amount,$taxes->value],$admin_content);
            
            $details=[         
                 "subject"=>$admin_subject ,
                 "message"=>$admin_content,
                 "to"=>$superAdmin->email,
            ];
            send_email($details);
            
            $notification_template = NotificationTemplate::find(2);
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
            
            $title = str_replace(["{booking_id}"],[$booking->booking_id],$title);
            $message = str_replace(["{item_name}","{booking_id}"],[$service->title,$booking->booking_id],$message);
            
            $item = new UserNotification();
            $item->user_id = $booking->user_id;
            $item->title = $title;
            $item->description = $message;
            $item->notification_type = $type;
            $item->temp_id = '2';
            $item->item_name = $service->title;
            $item->booking_id = $booking->booking_id;
            $item->save();
            
            GlobalFunction::sendPushToUser($title, $message, $user);
            
            $date=  Session::put('date','');
            $booking_hours=  Session::get('booking_hours','');
            $booking_time=   Session::get('booking_time','');
            $quantity=  Session::get('quantity','');
            $service_id=  Session::get('service_id','');
            
            if(session()->get('user_type') == '20'){
                return redirect()->route('dashboard')->with(['booking_message' => 'Service Booked Successfully', 'booking_id' => $booking->id]);
            }else{
                return redirect()->route('users.profile', $user->id)->with(['booking_message' => 'Service Booked Successfully', 'booking_id' => $booking->id]);
            }
        }catch(\Exception $e){
            return back()->with(['booking_message' => $e->getMessage()]);
        }
    }
    
    function admin_service_booking($id)
    {
        $user = Users::find($id);
        $settings = GlobalSettings::first();
        $totalBookings = Bookings::where('user_id', $id)->count();
        
        if($user->user_type==1){
            $services = Services::select(['id','latitude','logintude','salon_id','category_id','rating','thumbnail','title','service_time','service_number','about','rules','qauntity','foreiner_price as price','foreiner_price_per_day as price_per_day','foreiner_discount as discount'])->where('status',1)->get();
        }else{
            $services = Services::select(['id','latitude','logintude','salon_id','category_id','thumbnail','rating','title','service_time','service_number','about','rules','qauntity','price','price_per_day','discount'])->where('status',1)->get(); 
        }
        
        $ibpcards=Card::where('assigned_to',$id)->first();
        $categories = SalonCategories::where('is_deleted', '0')->where('status', '1')->get();
        
        return view('bookings.placebooking', [
            'user' => $user,
            'ibpcards'=>$ibpcards,
            'settings' => $settings,
            'totalBookings' => $totalBookings,
            'services'=>$services,
            'categories'=>$categories,
        ]);
    }
}

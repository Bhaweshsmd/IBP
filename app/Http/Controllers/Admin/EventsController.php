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
use App\Models\Taxes;
use App\Models\Card;
use App\Models\CardsTransaction;
use App\Jobs\PlatformEarning;
use App\Models\AdminWithdrawRequest;
use App\Models\PlatformEarningHistory;
use App\Models\RevenueSetting;
use App\Models\PlatformData;
use App\Models\Admin;
use App\Models\AdminEmailTemplate;
use App\Models\CardmembershipFee;
use App\Models\Device;
use App\Models\Language;
use App\Models\NotificationTemplate;
use App\Models\AdminNotificationTemplate;
use App\Models\ChatMessage;
use Pusher\Pusher;

class EventsController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService, Auth $auth)
    {
        $this->twilioService = $twilioService;
    }
    
    public function index(Request $request)
    {
        $enquiry_list= DB::table("event_inquiries")->latest()->get();
        return view('events.index',['enquiry_list'=>$enquiry_list]);
    }
    
    function list(Request $request)
    {
       $totalData =  EventType::count();
        $rows = EventType::orderBy('id', 'DESC')->get();
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
            $result = EventType::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  EventType::where(function ($query) use ($search) {
                $query->Where('title', 'LIKE', "%{$search}%");
            })->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = EventType::where(function ($query) use ($search) {
                $query->Where('title', 'LIKE', "%{$search}%");
            })->count();
        }
        $data = array();
        $i=1;
        foreach ($result as $item) {
            
            if(has_permission(session()->get('user_type'), 'view_enquiries')){
                $edit = '<a data-title="' . $item->title . '" data-shortid="' . $item->short_id . '" data-status="' . $item->status . '" data-maxDiscAmount="' . $item->title . '" data-coupon="' . $item->title . '" data-percentage="' . $item->title . '" href="" class="mr-2 btn btn-primary text-white edit editBtn" rel=' . $item->id . ' ><i class="fa fa-edit"></i></a>';
            }else{
                $edit = '';
            }
            
            if(has_permission(session()->get('user_type'), 'delete_enquiries')){
                $delete = '<a href="" class="mr-2 btn btn-danger text-white delete deleteBtn" rel=' . $item->id . ' ><i class="fa fa-trash"></i></a>';
            }else{
                $delete = '';
            }
            
            $action = $edit . $delete;
            
               $imgUrl = "http://placehold.jp/150x150.png";
            if ($item->flag == null) {
                $img = '<img src="http://placehold.jp/150x150.png" width="50" height="50">';
            } else {
                $imgUrl = GlobalFunction::createMediaUrl($item->flag);
                $img = '<img src="' . $imgUrl . '" width="50" height="50">';
            }

            $data[] = array(
                $i++,
                $item->title,
                $item->short_id,
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
    
    public function store(Request $request)
    {
        $rules = [
           'title'         =>'required',
           'short_id'      =>'required',
           'status'        =>'required',
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()){
            $message = $validator->errors()->all();
            $msg = $message[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        $addEvent = new EventType ();
        $addEvent->title = $request->title;
        $addEvent->short_id = $request->short_id;
        $addEvent->status = $request->status;
        $addEvent->save();

        if ($addEvent) {
            return response()->json(['status' => true, 'message' => "Data saved successfully"]);
        } else {
            return response()->json(['status' => false, 'message' => "Failed to save data"]);
        }

        return response()->json(['status' => true, 'message' =>"Okay this is new user"]);
    }
    
    public function update(Request $request)
    {
        $rules = [
           'title'         =>'required',
           'short_id'      =>'required',
           'status'        =>'required',
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()){
            $message = $validator->errors()->all();
            $msg = $message[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        $addEvent =  EventType::find($request->id);
        
        if ($addEvent == null) {
            return response()->json(['status' => false, 'message' => "Event doesn't exists!"]);
        }
        $addEvent->title = $request->title;
        $addEvent->short_id = $request->short_id;
        $addEvent->status = $request->status;
        $addEvent->save();
        
        
        if ($addEvent) {
            return response()->json(['status' => true, 'message' => "Data Updated successfully"]);
        } else {
            return response()->json(['status' => false, 'message' => "Failed to save data"]);
        }

        return response()->json(['status' => true, 'message' =>"Okay this is new user"]);
    }
    
    public function delete($id)
    {
        try {
            $eventType = EventType::find($id);
    
            if (!$eventType) {
                return response()->json(['status' => false, 'message' => 'EventType not found']);
            }
    
            $eventType->delete();
    
            return back()->with(['event_message' => 'Event Deleted Successfully']);
        } catch (\Exception $e) {
            return back()->with(['event_message' => $e->getMessage()]);
        }
    }
}

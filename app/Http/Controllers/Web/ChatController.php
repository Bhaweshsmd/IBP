<?php

namespace App\Http\Controllers\Web;
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

class ChatController extends Controller
{
    public function user_chat(Request $request)
    {
        $data['my_id'] = $my_id = Session::get('user_id');
        $data['users'] = $users = Admin::where('user_id', '1')->get();
        $data['settings'] = GlobalSettings::first();
        return view('web.chat.index', $data);
    }
    
    public function get_message(Request $request, $userid)
    {
        $data['username'] = $username = Admin::where('user_id', '1')->first();
        $data['my_id'] = $my_id = $username->user_id;

        ChatMessage::where(['sender' => $userid, 'receiver' => $my_id])->update(['read' => 1]);
        ChatMessage::where(['receiver' => $userid, 'sender' => $my_id])->update(['read' => 1]);

        $data['messages'] = ChatMessage::where(function ($query) use ($userid, $my_id) {
            $query->where('sender', $userid)->where('receiver', $my_id);
        })->oRwhere(function ($query) use ($userid, $my_id) {
            $query->where('sender', $my_id)->where('receiver', $userid);
        })->get();
        
        return view('web.chat.message', $data);
    }
    
    public function send_message(Request $request)
    {
        $sender = Session::get('user_id');
        $receiver =  $request->receiver_id;
        $message = $request->message;
        $image = $request->image;
        
        if(!empty($message)){
            $new_message = $message;
        }else{
            $new_message = $image;
        }
        
        $rs = ChatMessage::create([
            'sender' => $sender, 
            'receiver' => $receiver, 
            'message' => $new_message, 
            'read' => '0',
            'sender_type' => 'user'
        ]);
        
        User::where('id', $sender)->update(['last_message_time' => date('Y-m-d H:i:s')]);
        
        $settings = GlobalSettings::first();

        $options = array(
            'cluster' => $settings->pusher_cluster,
            'useTLS' => true
        );

        $pusher = new Pusher(
            $settings->pusher_key,
            $settings->pusher_secret,
            $settings->pusher_id,
            $options
        );

        $data = ['sender' => $sender, 'receiver' => $receiver, 'message' => $new_message, 'read' => 0, 'created_at' => $rs->created_at];
        $pusher->trigger('my-channel', 'my-event', $data);
    }
}
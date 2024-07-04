<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
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
use App\Models\User;
use App\Models\Fee;
use App\Models\EventType;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use App\Models\EmailTemplate;
use App\Models\SalonBookingSlots;
use App\Models\Taxes;
use App\Models\Card;
use App\Models\CardsTransaction;
use App\Models\AdminWithdrawRequest;
use App\Models\PlatformEarningHistory;
use App\Models\RevenueSetting;
use App\Models\PlatformData;
use App\Models\Admin;
use App\Models\AdminEmailTemplate;
use App\Models\CardmembershipFee;
use App\Models\Device;
use App\Models\UsersLoginLocation;
use App\Jobs\SendEmail;
use App\Jobs\SendNotification;
use App\Jobs\PlatformEarning;
use App\Services\TwilioService;
use Twilio\Jwt\ClientToken; 
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use DB;
use Session;
use App\Models\AdminNotificationTemplate;
use App\Models\Language;
use App\Models\Country;
use App\Models\ChatMessage;
use Pusher\Pusher;

class ChatController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService, Auth $auth)
    {
        $this->twilioService = $twilioService;
    }
    
    public function send_message(Request $request)
    {
        $rules = [
            'sender'=>'required',
            'message'=>'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        try{
            $sender = $request->sender;
            $receiver = '1';
            $message = $request->message;
            
            $rs = ChatMessage::create([
                'sender' => $sender, 
                'receiver' => $receiver, 
                'message' => $message, 
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
            
            $data = ['sender' => $sender, 'receiver' => $receiver, 'message' => $message, 'read' => '0', 'created_at' => $rs->created_at];
            $pusher->trigger('my-channel', 'my-event', $data);
            
        }catch(\Exception $e){
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
        
        return GlobalFunction::sendDataResponse(true, 'Message sent successfully', $rs);
    }
    
    public function get_message(Request $request)
    { 
        $rules = [
            'sender'=>'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        
        try
        {
            $sender = $request->sender;
            $receiver = '1';

            $profile = DB::select("select chat_messages.`receiver`, chat_messages.`sender`, chat_messages.`message`, users.first_name, chat_messages.`read`, users.email, chat_messages.`created_at` from chat_messages left join users on chat_messages.`receiver` = users.id where `sender` = $sender and `receiver` = $receiver or `sender` = $receiver and `receiver` = $sender order by created_at asc");
        }catch(\Exception $e){
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
        
        return GlobalFunction::sendDataResponse(true, 'Message fetched successfully', $profile);
    }
}
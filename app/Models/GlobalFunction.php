<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Google\Client;
use Illuminate\Support\Facades\File;
use App\Models\SalonBookingSlots;
use App\Models\GlobalSettings;
use App\Models\Device;
use App\Models\User;

class GlobalFunction extends Model
{
    use HasFactory;

    public static function roundNumber($number)
    {
        return round($number, 2);
    }

    public static function sendPushNotificationToUsers($title, $message)
    {
        $client = new Client();
        $client->setAuthConfig('googleCredentials.json');
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->fetchAccessTokenWithAssertion();
        $accessToken = $client->getAccessToken();
        $accessToken = $accessToken['access_token'];

        $contents = File::get(base_path('googleCredentials.json'));
        $json = json_decode(json: $contents, associative: true);

        $url = 'https://fcm.googleapis.com/v1/projects/'.$json['project_id'].'/messages:send';
        $notificationArray = array('title' => $title, 'body' => $message);

        $fields = array(
            'message'=> [
                'topic'=> 'users',
                'notification' => $notificationArray,
            ]
        );

        $headers = array(
            'Content-Type:application/json',
            'Authorization:Bearer ' . $accessToken
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        Log::debug($result);

        if ($result === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);

        if ($result) {
            return json_encode(['status' => true, 'message' => 'Notification sent successfully']);
        } else {
            return json_encode(['status' => false, 'message ' => 'Not sent!']);
        }
    }
    
    public static function sendPushNotificationToSalons($title, $message)
    {
        $users = User::where('is_block', '0')->get();
        foreach($users as $user){
            if ($user->is_notification == 1) {
                $setting = GlobalSettings::first();
                $userdevices = Device::where('user_id', $user->id)->first();
                if(!empty($userdevices->fcm_token)){
                    $fcmUrl          = 'https://fcm.googleapis.com/fcm/send';
                    $headers         = [
                        'Authorization: key='.$setting->firebase_key,
                        'Content-Type: application/json'
                    ]; 
                    $fcmNotification = [
                        'to'        => $userdevices->fcm_token,
                        'notification'     => [
                            'title' => $title,
                            'body'  => $message,
                            'image' => "{{url('asset/img/favicon.ico')}}",
                            'sound' => TRUE,
                            'priority' => "high",
                        ],
                        'data'             => [
                            "type" => 'push',
                        ]
                    ];
                    
                    $ch              = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $fcmUrl);
                    curl_setopt($ch, CURLOPT_POST, TRUE);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
                    $result = curl_exec($ch);
                    curl_close($ch);
                    return $result;
                }
            }
        }
    }

    public static function detectPaymentGateway($gateway)
    {
        $name = "";
        switch ($gateway) {
            case (Constants::stripe):
                $name = 'Stripe';
                break;
            case (Constants::addedByAdmin):
                $name = 'Added By Admin';
                break;
            case (Constants::flutterWave):
                $name = 'Flutterwave';
                break;
            case (Constants::razorPay):
                $name = 'Razorpay';
                break;
            case (Constants::payStack):
                $name = 'Paystack';
                break;
            case (Constants::payPal):
                $name = 'PayPal';
                break;
        }

        return $name;
    }

    public static function sendSimpleResponse($status, $msg)
    {
        return response()->json(['status' => $status, 'message' => $msg]);
    }
    public static function sendDataResponse($status, $msg, $data)
    {
        return response()->json(['status' => $status, 'message' => $msg, 'data' => $data]);
    }

    public static function addCategoriesToSingleSalon($salon)
    {
        $salonCats = SalonCategories::whereIn('id', explode(',', $salon->salon_categories))->get();
        $salon->categories = $salonCats;
        return $salon;
    }
    
    public static function sendPushToUser($title, $message, $user)
    {
        if ($user->is_notification == 1) {
            $setting = GlobalSettings::first();
            $userdevices = Device::where('user_id', $user->id)->first();
            if(!empty($userdevices->fcm_token)){
                $fcmUrl          = 'https://fcm.googleapis.com/fcm/send';
                $headers         = [
                    'Authorization: key='.$setting->firebase_key,
                    'Content-Type: application/json'
                ]; 
                $fcmNotification = [
                    'to'        => $userdevices->fcm_token,
                    'notification'     => [
                        'title' => $title,
                        'body'  => $message,
                        'image' => "{{url('asset/img/favicon.ico')}}",
                        'sound' => TRUE,
                        'priority' => "high",
                    ],
                    'data'             => [
                        "type" => 'push',
                    ]
                ];
                
                $ch              = curl_init();
                curl_setopt($ch, CURLOPT_URL, $fcmUrl);
                curl_setopt($ch, CURLOPT_POST, TRUE);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
                $result = curl_exec($ch);
                curl_close($ch);
                return $result;
            }
        }
    }

    public static function sendPushToSalon($title, $message, $salon)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $api_key = env('FCM_TOKEN');
        $notificationArray = array('title' => $title, 'body' => $message, 'sound' => 'default', 'badge' => '1');

        if ($salon->is_notification == 1) {
            $client = new Client();
            $client->setAuthConfig('googleCredentials.json');
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
            $client->fetchAccessTokenWithAssertion();
            $accessToken = $client->getAccessToken();
            $accessToken = $accessToken['access_token'];

            $contents = File::get(base_path('googleCredentials.json'));
            $json = json_decode(json: $contents, associative: true);

            $url = 'https://fcm.googleapis.com/v1/projects/'.$json['project_id'].'/messages:send';
            $notificationArray = array('title' => $title, 'body' => $message);

            $fields = array(
                'message'=> [
                    'token'=> $salon->device_token,
                    'notification' => $notificationArray,
                ]
            );

            $headers = array(
                'Content-Type:application/json',
                'Authorization:Bearer ' . $accessToken
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            Log::debug($result);

            if ($result === FALSE) {
                die('FCM Send Error: ' . curl_error($ch));
            }
            curl_close($ch);

            if ($result) {
                return json_encode(['status' => true, 'message' => 'Notification sent successfully']);
            } else {
                return json_encode(['status' => false, 'message ' => 'Not sent!']);
            }
        }
    }

    public static function createMediaUrl($media)
    {
        $url = url('public/storage/'.$media);
        return $url;
    }

    public static function uploadFilToS3($request, $key)
    {
        $s3 = Storage::disk('s3');
        $file = $request->file($key);
        $fileName = time() . $file->getClientOriginalName();
        $fileName = str_replace(" ", "_", $fileName);
        $filePath = 'uploads/' . $fileName;
        $result =  $s3->put($filePath, file_get_contents($file), 'public-read');
        return $filePath;
    }

    public static function point2point_distance($lat1, $lon1, $lat2, $lon2, $unit = 'K', $radius)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return (($miles * 1.609344) <= $radius);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }

    public static function cleanString($string)
    {
        return  str_replace(array('<', '>', '{', '}', '[', ']', '`'), '', $string);
    }

    public static function deleteFile($filename)
    {
        if ($filename != null && file_exists(storage_path('app/public/' . $filename))) {
            unlink(storage_path('app/public/' . $filename));
        }
    }

    public static function saveFileAndGivePath($file)
    {
        if ($file != null) {
           
            $info = getimagesize($file);

            if ($info['mime'] == 'image/jpeg'){
                $image = imagecreatefromjpeg($file);
            }elseif ($info['mime'] == 'image/jpg'){
                $image = imagecreatefromjpeg($file);
            }elseif ($info['mime'] == 'image/gif'){
                $image = imagecreatefromgif($file);
            }elseif ($info['mime'] == 'image/png'){
                $image = imagecreatefrompng($file);
            }elseif ($info['mime'] == 'image/webp'){
                $image = imagecreatefrompng($file);
            }
            
            $path = $file->store('uploads');
            $storage_path = storage_path('app/public/').$path;
            $check = imagejpeg($image, $storage_path, 60);
            return $path;
        } else {
            return null;
        }
    }

    public static function formateTimeString($timeString)
    {
        if ($timeString != null) {
            return substr_replace($timeString, ":", 2, 0);
        }
        return "";
    }

    public static function generatePlatformEarningHistoryNumber()
    {
        $token =  rand(100000, 999999);

        $first = Constants::prefixPlatformEarningHistory;
        $first .= GlobalFunction::generateRandomString(3);
        $first .= $token;
        $first .= GlobalFunction::generateRandomString(3);
        $count = PlatformEarningHistory::where('earning_number', $first)->count();

        while ($count >= 1) {

            $token =  rand(100000, 999999);

            $first = GlobalFunction::generateRandomString(3);
            $first .= $token;
            $first .= GlobalFunction::generateRandomString(3);
            $count = PlatformEarningHistory::where('earning_number', $first)->count();
        }

        return $first;
    }
    public static function generateSalonEarningHistoryNumber()
    {
        $token =  rand(100000, 999999);
        $first = Constants::prefixSalonEarningHistory;
        $first .= GlobalFunction::generateRandomString(3);
        $first .= $token;
        $first .= GlobalFunction::generateRandomString(3);
        $count = SalonEarningHistory::where('earning_number', $first)->count();

        while ($count >= 1) {

            $token =  rand(100000, 999999);

            $first = GlobalFunction::generateRandomString(3);
            $first .= $token;
            $first .= GlobalFunction::generateRandomString(3);
            $count = SalonEarningHistory::where('earning_number', $first)->count();
        }

        return $first;
    }
    
    public static function generateServiceNumber()
    {
        $token =  rand(100000, 999999);
        $first = Constants::prefixServiceNumber;
        $first .= GlobalFunction::generateRandomString(3);
        $first .= $token;
        $first .= GlobalFunction::generateRandomString(3);
        $count = Services::where('service_number', $first)->count();

        while ($count >= 1) {

            $token =  rand(100000, 999999);

            $first = GlobalFunction::generateRandomString(3);
            $first .= $token;
            $first .= GlobalFunction::generateRandomString(3);
            $count = Services::where('service_number', $first)->count();
        }

        return $first;
    }
      
    public static function generateSupportTicketNumber()
    {
        $token =  rand(100000, 999999);
        $first = Constants::prefixSupportTicket;
        $first .= GlobalFunction::generateRandomString(3);
        $first .= $token;
        $first .= GlobalFunction::generateRandomString(3);
        $count = Services::where('service_number', $first)->count();

        while ($count >= 1) {

            $token =  rand(100000, 999999);

            $first = GlobalFunction::generateRandomString(3);
            $first .= $token;
            $first .= GlobalFunction::generateRandomString(3);
            $count = Services::where('service_number', $first)->count();
        }

        return $first;
    }
    
    public static function generateSalonNumber()
    {
        $token =  rand(100000, 999999);

        $first = Constants::prefixSalonNumber;
        $first .= GlobalFunction::generateRandomString(3);
        $first .= $token;
        $first .= GlobalFunction::generateRandomString(3);
        $count = Salons::where('salon_number', $first)->count();

        while ($count >= 1) {

            $token =  rand(100000, 999999);

            $first = GlobalFunction::generateRandomString(3);
            $first .= $token;
            $first .= GlobalFunction::generateRandomString(3);
            $count = Salons::where('salon_number', $first)->count();
        }

        return $first;
    }

    public static function generateSalonWithdrawRequestNumber()
    {
        $token =  rand(100000, 999999);
        $first = Constants::prefixSalonWithDrawRequestNumber;
        $first .= GlobalFunction::generateRandomString(3);
        $first .= $token;
        $first .= GlobalFunction::generateRandomString(3);
        $count = SalonPayoutHistory::where('request_number', $first)->count();

        while ($count >= 1) {

            $token =  rand(100000, 999999);
            $first = GlobalFunction::generateRandomString(3);
            $first .= $token;
            $first .= GlobalFunction::generateRandomString(3);
            $count = SalonPayoutHistory::where('request_number', $first)->count();
        }

        return $first;
    }
    
    public static function generateUserWithdrawRequestNumber()
    {
        $token =  rand(100000, 999999);
        $first = Constants::prefixUserWithDrawRequestNumber;
        $first .= GlobalFunction::generateRandomString(3);
        $first .= $token;
        $first .= GlobalFunction::generateRandomString(3);
        $count = UserWithdrawRequest::where('request_number', $first)->count();

        while ($count >= 1) {

            $token =  rand(100000, 999999);
            $first = GlobalFunction::generateRandomString(3);
            $first .= $token;
            $first .= GlobalFunction::generateRandomString(3);
            $count = UserWithdrawRequest::where('request_number', $first)->count();
        }

        return $first;
    }
    
    public static function generateBookingId()
    {
        $token =  rand(100000, 999999);

        $first = Constants::prefixBookingId;
        $first .= GlobalFunction::generateRandomString(3);
        $first .= $token;
        $first .= GlobalFunction::generateRandomString(3);
        $count = Bookings::where('booking_id', $first)->count();

        while ($count >= 1) {

            $token =  rand(100000, 999999);

            $first = GlobalFunction::generateRandomString(3);
            $first .= $token;
            $first .= GlobalFunction::generateRandomString(3);
            $count = Bookings::where('booking_id', $first)->count();
        }

        return $first;
    }

    public static function addSalonStatementEntry($salonId, $bookingId, $amount, $crOrDr, $type, $summary)
    {
        $stmt = new SalonWalletStatements();
        $stmt->transaction_id = GlobalFunction::generateSalonTransactionId();
        $stmt->salon_id = $salonId;
        $stmt->booking_id = $bookingId;
        $stmt->amount = $amount;
        $stmt->cr_or_dr = $crOrDr;
        $stmt->type = $type;
        $stmt->summary = $summary;
        $stmt->save();
    }
    
    public static function addUserStatementEntry($userId, $bookingId, $amount, $crOrDr, $type, $summary,$charge_percent=null,$charge_amount=null,$total_amount=null)
    {
        $stmt = new UserWalletStatements();
        $stmt->transaction_id = GlobalFunction::generateTransactionId();
        $stmt->user_id = $userId;
        $stmt->booking_id = $bookingId;
        $stmt->amount = $amount;
        $stmt->charge_percent = $charge_percent;
        $stmt->charge_amount = $charge_amount;
        $stmt->total_amount = $total_amount;
        $stmt->cr_or_dr = $crOrDr;
        $stmt->type = $type;
        $stmt->summary = $summary;
        $stmt->save();
    }

    public static function generateSalonTransactionId()
    {
        $token =  rand(100000, 999999);

        $first = Constants::prefixSalonTransactionId;
        $first .= GlobalFunction::generateRandomString(3);
        $first .= $token;
        $first .= GlobalFunction::generateRandomString(3);
        $count = SalonWalletStatements::where('transaction_id', $first)->count();

        while ($count >= 1) {

            $token =  rand(100000, 999999);

            $first = GlobalFunction::generateRandomString(3);
            $first .= $token;
            $first .= GlobalFunction::generateRandomString(3);
            $count = SalonWalletStatements::where('transaction_id', $first)->count();
        }

        return $first;
    }
    
    public static function generateTransactionId()
    {
        $token =  rand(100000, 999999);
        $first = Constants::prefixUserTransactionId;
        $first .= GlobalFunction::generateRandomString(3);
        $first .= $token;
        $first .= GlobalFunction::generateRandomString(3);
        $count = UserWalletStatements::where('transaction_id', $first)->count();

        while ($count >= 1) {

            $token =  rand(100000, 999999);

            $first = GlobalFunction::generateRandomString(3);
            $first .= $token;
            $first .= GlobalFunction::generateRandomString(3);
            $count = UserWalletStatements::where('transaction_id', $first)->count();
        }

        return $first;
    }

    public static function generateRandomString($length)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
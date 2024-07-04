<?php

namespace App\Services;
use Twilio\Rest\Client;
use App\Models\GlobalSettings;

class TwilioService
{
    protected $client;
    public function __construct()
    {
        
    }
    
    public function sendSMS($to, $message)
    {
        $settings = GlobalSettings::first();
        
        $client = new Client($settings->twilio_sid, $settings->twilio_auth_token);
        
        return $client->messages->create($to, [
            'from' => $settings->twilio_phone_number,
            'body' => $message,
        ]);
    }
}
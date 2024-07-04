<?php
// namespace App\Helpers;

use App\Models\Users;
use App\Models\GlobalSettings;
use App\Models\Taxes;
use App\Models\Fee;
use App\Models\UserNotification;
use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\EmailConfig;
use Illuminate\Support\Facades\Config;

function getUserDetails($userId=null){

    $user_details=Users::where('id',$userId)->first();
    return $user_details;

}

function getCompanyDetails($userId=null){

    $user_details=Users::where('id',$userId)->first();
    return $user_details;

}


function fetchGlobalSettings()
    {
        $settings = GlobalSettings::first();
        $taxes = Taxes::where('status', 1)->first();
        $fee=Fee::get();
        $settings->taxes = $taxes;
        $settings->fee=$fee;
         return $settings;
    }
    
function favouriteList($service_id=null){
    
    $user_id=Session::get('user_id');
    if($user_id){
    $userDetails= Users::find($user_id);
    $favourite_service = explode(',',$userDetails->favourite_services);
    if(array_search($service_id,$favourite_service)){
        return true;
        
    }else{
       return false; 
    }
    }else{
        return false;
    }
    
}  

    function fetchNotification()
    {
        $user_id = Session::get('user_id');
        $notifications = UserNotification::where('user_id',$user_id)->orWhere('notification_type','promotional')->limit(5)->orderBy('id', 'DESC')->get();
    
        return $notifications;
    } 

    function has_permission($role_id, $permissions)
    {
        $permissions = explode('|', $permissions);

        $permission_id = [];
        $i             = 0;

        $user_type = 'Admin';

        $userPermissions = Permission::whereIn('name', $permissions)->get(['id']);
        
        foreach ($userPermissions as $value)
        {
            $permission_id[$i++] = $value->id;
        }
        $role = PermissionRole::where(['role_id' => $role_id, 'permission_id' => $permission_id])->first(['role_id']);

        if (isset($role->role_id))
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
    
    function send_email($details)
    {
        $settings = EmailConfig::first();
        
        Config::set([
            'mail.driver'     => $settings->mail_driver,
            'mail.host'       => $settings->mail_host,
            'mail.port'       => $settings->mail_host,
            'mail.from'       => ['address' => $settings->mail_from_address, 'name' => $settings->mail_from_name],
            'mail.encryption' => $settings->mail_encryption,
            'mail.username'   => $settings->mail_username,
            'mail.password'   => $settings->mail_password,
        ]);
        
        $fromInfo = Config::get('mail.from');
        
        $data = array();
        $data['subject'] = $details['subject'];
        $data['msg'] = $details['message'];
        $data['email'] = $details['to'];
        $data['from']     = $fromInfo['address'];
        $data['fromName'] = $fromInfo['name'];
        
        Mail::send('basicmail', $data, function ($message) use ($data) {
            $message->from($data['from'], $data['fromName']);
            $message->to($data['email']);
            $message->subject($data['subject']);
        });
    }
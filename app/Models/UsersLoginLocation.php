<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersLoginLocation extends Model
{
    use HasFactory;
    
    public $table = "users_login_location";
    protected $fillable = [
        'user_id', 'ip_address', 'device_id', 'language', 'device_name', 'device_manufacture', 'device_model', 'os_ver', 'device_os', 'app_ver', 'browser', 'version', 'platform'
    ];
}

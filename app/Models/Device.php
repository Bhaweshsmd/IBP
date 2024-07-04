<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;
    
    public $table = "devices";
    protected $fillable = [
        'user_id', 'device_id', 'fcm_token', 'language', 'device_name', 'device_manufacture', 'device_model', 'os_ver', 'device_os', 'app_ver', 'status'
    ];
}

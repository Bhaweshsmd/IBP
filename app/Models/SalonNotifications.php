<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalonNotifications extends Model
{
    use HasFactory;
    public $table = "salon_notifications";
    
     public function user()
    {
        return $this->hasOne(Users::class, 'id', 'user_id');
    }
}
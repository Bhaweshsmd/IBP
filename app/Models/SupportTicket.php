<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    use HasFactory;
    public $table="support_tickets";
    
    
       public function user()
    {
        return $this->hasOne(Users::class, 'id', 'user_id');
    }
    
}

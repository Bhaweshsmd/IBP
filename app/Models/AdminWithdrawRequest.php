<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminWithdrawRequest extends Model
{
    use HasFactory;
      public $table = "admin_withdraw_request";

    public function user()
    {
        return $this->hasOne(Admin::class, 'user_id', 'user_id');
    }
    
    public function getAmountAttribute($value)
    {
      return  number_format($value,2, '.', '');
    }
}

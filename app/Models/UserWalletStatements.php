<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWalletStatements extends Model
{
    use HasFactory;
    public $table = "user_wallet_statement";
    
    public function getAmountAttribute($value)
    {
     return  number_format($value,2, '.', '');
    }
    
    public function user(){
        
     return  $this->hasMany(Users::class, 'id', 'user_id');
        
    }
    
}

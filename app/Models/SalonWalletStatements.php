<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalonWalletStatements extends Model
{
    use HasFactory;
    public $table = "salon_wallet_statement";
    
     public function getAmountAttribute($value)
    {
      return  number_format($value,2);
    }
}

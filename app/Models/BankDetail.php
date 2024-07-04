<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankDetail extends Model
{
    use HasFactory;
    
    public $table = "bank_details";
    protected $fillable = [
        'user_id', 'user_type', 'bank_name', 'account_number', 'account_holder', 'swift_code', 'status'
    ];
}

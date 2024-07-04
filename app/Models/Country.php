<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    
    public $table = "countries";
    protected $fillable = [
        'short_name', 'name', 'iso3', 'number_code', 'phone_code', 'currency_code', 'flag', 'status', 'automatic_kyc', 'manual_kyc'
    ];
}

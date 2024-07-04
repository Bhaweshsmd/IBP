<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardFee extends Model
{
    use HasFactory;
    
    public $table = "card_fees";
    protected $fillable = [
        'fee', 'updated_by'
    ];
}

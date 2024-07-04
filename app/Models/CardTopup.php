<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardTopup extends Model
{
    use HasFactory;
    public $table = "card_topups";
    protected $fillable = [
        'user_id', 'card_id', 'topup_by', 'amount', 'payment_type', 'order_id'
    ];
}

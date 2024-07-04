<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardassignFee extends Model
{
    use HasFactory;
    
    public $table = "cardassign_fees";
    protected $fillable = [
        'user_id', 'card_id', 'fee', 'status'
    ];
}

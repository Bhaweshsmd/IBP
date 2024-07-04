<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;
    public $table = "chat_messages";
    protected $fillable = [
        'sender', 'receiver', 'message', 'read', 'sender_type'
    ];
}

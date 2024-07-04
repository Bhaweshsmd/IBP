<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceSetting extends Model
{
    use HasFactory;
    
    public $table = "maintenance_settings";
    protected $fillable = [
        'subject_en', 'subject_pap', 'subject_nl', 'date', 'from_time', 'to_time', 'message_en', 'message_pap', 'message_nl', 'type', 'status'
    ];
}

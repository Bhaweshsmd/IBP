<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceFee extends Model
{
    use HasFactory;
    
    public $table = "maintenance_fees";
    protected $fillable = [
        'fee', 'updated_by'
    ];
}

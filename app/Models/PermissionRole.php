<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use DB;

class PermissionRole extends Model
{
    use HasFactory;
    public $table = "permission_role";
    protected $fillable = ['permission_id', 'role_id'];
}

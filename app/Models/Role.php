<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use DB;

class Role extends Model
{
    use HasFactory;
    public $table = "roles";
    
    public static function permission_role($id)
    {
        return DB::table('permission_role')->where('role_id', $id)->pluck('permission_id');
    }
}

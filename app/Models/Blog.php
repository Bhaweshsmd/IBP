<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;
    
    public $table = "blogs";
    protected $fillable = [
        'en_title', 'en_description','pap_title', 'pap_description','nl_title', 'nl_description', 'added_by', 'slug', 'status', 'image'
    ];
}

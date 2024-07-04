<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;
    public $table = "pages";
    protected $fillable = [
        'en_title', 'pap_title', 'nl_title', 'en_description', 'pap_description', 'nl_description', 'slug', 'status', 'type'
    ];
}

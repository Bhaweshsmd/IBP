<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LanguageContent extends Model
{
    use HasFactory;
     public $table = "language_contents";
     
     
    //  public function setStringAttribute($title){
         
    //     $this->attributes['string'] = Str::slug($title,'_');
    // }
     
     
}

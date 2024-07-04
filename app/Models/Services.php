<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
class Services extends Model
{
     use HasFactory, SoftDeletes;
  
    protected $dates = ['deleted_at']; 
    
    
    public $table = "salon_services";
    
    
     
    
     public function avgRating()
    {
        return $this->reviews->avg('rating');
    }
    public function images()
    {
        return $this->hasMany(ServiceImages::class, 'service_id', 'id');
    }
    
     public function map_images()
    {
        return $this->hasMany(ServiceMapImage::class, 'service_id', 'id');
    }
    
    public function salon()
    {
        return $this->hasOne(Salons::class, 'id', 'salon_id');
    }
    public function category()
    {
        return $this->hasOne(SalonCategories::class, 'id', 'category_id');
    }
    
    public function slots()
    {
        return $this->hasMany(SalonBookingSlots::class, 'salon_id', 'id')->orderBy('time','ASC');
    }
    
    public function reviews()
    {
        return $this->hasMany(SalonReviews::class, 'salon_id', 'id')->where('status', '1');
    }
    
    public function setSlugAttribute($title)
    {
        $this->attributes['slug'] = Str::slug($title,'-');
    }
    
}

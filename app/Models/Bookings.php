<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookings extends Model
{
    use HasFactory;
    public $table = "bookings";

    public function salon()
    {
        return $this->hasOne(Salons::class, 'id', 'salon_id');
    }
    public function user()
    {
        return $this->hasOne(Users::class, 'id', 'user_id');
    }
    public function review()
    {
        return $this->hasOne(SalonReviews::class, 'booking_id', 'id');
    }
    
      public function service()
    {
        return $this->hasOne(Services::class, 'id', 'service_id');
    }
    
     public function getPayableAmountAttribute($value)
    {
      return  number_format($value,2);
    }
    
    public function transaction(){
        return $this->hasOne(UserWalletStatements::class, 'booking_id', 'booking_id');
        
    }
    
     public function card_transaction(){
        return $this->hasOne(CardsTransaction::class, 'booking_id', 'booking_id');
        
    }
    
    
}

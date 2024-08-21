<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        "client_id" ,
        "order" ,
        "status" ,
        "deliver_id" ,
        'address_id' ,
        "scheduled_time" ,
        "estimated_time" ,
        "start_deliver_time" ,
        "received_time" ,
        "canceled" ,
        "canceled_note" ,
        "image" ,
        "rate" ,
        "is_voice" ,
        "voice_URL" ,
    ];
    public function deliver () {
        return $this->belongsTo(User::class , "deliver_id");
    }
    public function client () {
        return $this->belongsTo(User::class, "client_id");
    }
    public function address () {
        return $this->belongsTo(Address::class, "address_id");
    }
    
    public function invoices () {
        return $this->hasMany(Invoice::class);
    }
    public function adminNotifications () {
        return $this->hasMany(AdminNotification::class);
    }

}

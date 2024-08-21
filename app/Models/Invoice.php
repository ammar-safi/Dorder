<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = [
        "order_id",
        "content",
        "quantity",
        "price",
        "total",
        "client_id",
        "deliver_id",
     
    ];
    public function order () {
        return $this->belongsTo( Order::class , "order_id");
    }
    public function deliver () {
        return $this->belongsTo( User::class , "deliver_id");
    }
    public function client () {
        return $this->belongsTo( User::class , "client_id");
    }


}

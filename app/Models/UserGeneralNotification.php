<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGeneralNotification extends Model
{
    use HasFactory;
    protected $fillable = [
        "client_id",
        "title",
        "description",
        "order_id",
        "read",
    ] ;
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}

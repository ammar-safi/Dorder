<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    use HasFactory;
    protected $fillable = [
        "uuid",
        "title",
        'body',
        "order_id",
        "admin_id",
        "read",

    ];
    protected static function booted()
    {
        static::creating(function ($notification) {
            $notification->uuid = (string) \Str::uuid();  
        });
    }
    
    public function order()
    {
        return $this->belongsTo(Order::class, "order_id");
    }
    public function admin()
    {
        return $this->belongsTo(User::class, "admin_id");
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    use HasFactory;
    protected $fillable = [
        "title",
        "order_id",
        "admin_id",
        "read",

    ];
    public function order()
    {
        return $this->belongsTo(Order::class, "order_id");
    }
    public function admin()
    {
        return $this->belongsTo(User::class, "admin_id");
    }
}

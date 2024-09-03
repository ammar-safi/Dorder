<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        "client_id",
        'uuid',
        "order",
        "status",
        "deliver_id",
        'address_id',

        // اذا كان الطلب مجدول
        "scheduled_time",

        // الزمن المتوقع للتوصيل
        "estimated_time",

        "start_deliver_time",
        "received_time",
        "canceled",
        "canceled_note",
        "image",
        "rate",
    ];


    protected $caste = [
        'uuid' => 'string',
        "order" => "string",
        "status" => "string",
        "scheduled_time" => 'datetime',
        "estimated_time" => 'string',
        "start_deliver_time" => 'string',
        "received_time" => "string",
        "canceled" => 'boolean',
        "canceled_note" => 'string',
        "image" => 'string',
        "rate" => 'double',
    ];

    protected static function booted()
    {
        static::creating(function ($order) {
            $order->uuid = (string) \Str::uuid();
        });
    }

    public function deliver()
    {
        return $this->belongsTo(User::class, "deliver_id");
    }
    public function client()
    {
        return $this->belongsTo(User::class, "client_id");
    }
    public function address()
    {
        return $this->belongsTo(Address::class, "address_id");
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
    public function adminNotifications()
    {
        return $this->hasMany(AdminNotification::class);
    }

    /**
     * Polymorphic-relationships "Morph" 
     */

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, "imageable");
    }
}

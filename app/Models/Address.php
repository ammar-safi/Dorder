<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        "client_id",
        "title",
    
    ];
    protected static function booted()
    {
        static::creating(function ($address) {
            $address->uuid = (string) \Str::uuid();
        });
    }
    public function orders () {
        return $this->hasMany(Order::class);
    }
}

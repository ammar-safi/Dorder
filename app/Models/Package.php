<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;
    protected $fillable = [
        "title",
        "image",
        "count_of_orders",
        "package_price",
        "order_price",
    ];

}

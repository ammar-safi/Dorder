<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RateService extends Model
{
    use HasFactory;
    protected $fillable = [
        "client_id",
        "rate",
        "review",
        "services_id",
    
    ];
}

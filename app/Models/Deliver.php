<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deliver extends Model
{
    use HasFactory;
    protected $fillable = [
        'deliver_id',
        'area_id',
        

    ];

    public function User()
    {
        return $this->belongsTo(User::class, "deliver_id");
    }
    public function Area()
    {
        return $this->belongsTo(Area::class, "area_id");
    }
    
}

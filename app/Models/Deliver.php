<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deliver extends Model
{
    use HasFactory;
    use SoftDeletes;
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

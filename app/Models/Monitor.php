<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Monitor extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        "monitor_id",
        "area_id",
    ];
    public function area()
    {
        return $this->belongsTo(Area::class);
    }
    public function monitor()
    {
        return $this->belongsTo(User::class , "monitor_id");
    }
}

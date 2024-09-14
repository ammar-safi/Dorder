<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $append = [
        "count_of_monitors",
        "count_of_delivers",
        "count_of_clients",
    ];

    protected $fillable = [
        'uuid',
        "title",
        "city_id",
    ];

    protected $casts = [
        'uuid' => 'string',
        "title" => 'string',
        "city_id" => 'string',
    ];
    
    protected static function booted()
    {
        static::creating(function ($area) {
            $area->uuid = (string) \Str::uuid();
        });
    }

    /**
     * The relations whit other tables
     * 
     * Whit Areas 
     */
    public function city()
    {
        return $this->belongsTo(City::class, "city_id");
    }
    public function Monitors()
    {
        return $this->hasMany(Monitor::class);
    }
    public function Delivers()
    {
        return $this->hasMany(Deliver::class);
    }
    public function Users()
    {
        return $this->hasMany(User::class);
    }
    public function AreaMonitors()
    {
        return $this->belongsToMany(User::class, "monitors", 'area_id', "monitor_id");
    }
    public function AreaDelivers()
    {
        return $this->belongsToMany(User::class, "delivers", "area_id", "deliver_id");
    }

    // public function TrashedMonitors() {
    //     return Monitor::onlyTrashed()->where("area_id" , $this->id)->get();
    // }


    /**
     * Append 
     */

     public function getCountOfMonitorsAttribute()
     {
         return $this->deleted_at
             ? $this->Monitors()->onlyTrashed()->count()
             : $this->Monitors()->count();
     }
     public function getCountOfDeliversAttribute()
     {
         return $this->deleted_at
             ? $this->Delivers()->onlyTrashed()->count()
             : $this->Delivers()->count();
     }
    public function getCountOfClientsAttribute()
    {
        return $this->deleted_at
        ?$this->Users()->onlyTrashed()->where("type", '=', 'client')->count()
        :$this->Users()->where("type", '=', 'client')->count();
        
    }
}

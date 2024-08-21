<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    use HasFactory;
    use SoftDeletes ;

    protected $append = [
        "count_of_monitors",
        "count_of_delivers",
        "count_of_clients",
    ];

    protected $fillable = [
        "title",
        "city_id",
    ];

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

    /**
     * Append 
     */
   
    public function getCountOfMonitorsAttribute()
    {
        return $this->Monitors->count();
    }
    public function getCountOfDeliversAttribute()
    {
        return $this->Delivers->count();
    }
    public function getCountOfClientsAttribute()
    {
        return $this->Users()->where("type", '=', 'client')->count();
    }


    // public function softDelete() {
    //     if ($this) {
    //             $this->Monitors->delete();
    //         $this->Delivers->delete();
    //         $this->Users->delete();
    //     }
    // }
}

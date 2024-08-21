<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $table = "cities";

    protected $append = [
        "count_of_areas",
        "count_of_monitors",
        "count_of_delivers",
        "count_of_clients",
    ];

    protected $fillable = [
        "title",
        "image",
    ];

    /**
     * The relations whit other tables
     * 
     * Whit Areas 
     */
    public function Areas()
    {
        return $this->hasMany(Area::class, "city_id");
    }

    /**
     * This table has many through relations
     */
    public function Monitors()
    {
        return $this->hasManyThrough(Monitor::class, Area::class, 'city_id',  'area_id');
    }
    public function Delivers()
    {
        return $this->hasManyThrough(Deliver::class, Area::class, 'city_id',  'area_id');
    }
    public function Clients()
    {
        return $this->hasManyThrough(User::class, Area::class, 'city_id',  'area_id');
    }

    /**
     * Append 
     */
    public function getCountOfAreasAttribute()
    {
        return $this->areas->count();
    }
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
        return $this->Clients()->where("type" , '=' , 'client')->count();
    }
}

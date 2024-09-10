<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
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
        'uuid',
        "title",
        "image",
    ];

    protected $casts = [
        'uuid' => 'string',
        "title" => 'string',
        "image" => 'string',
    ];

    protected static function booted()
    {
        static::creating(function ($city) {
            $city->uuid = (string) \Str::uuid();
        });
    }


    /**
     * The relations whit other tables
     * 
     * Whit Areas 
     */
    public function Areas()
    {
        return $this->hasMany(Area::class, "city_id");
    }
    public function TrashedAreas()
    {
        return Area::onlyTrashed()->where("city_id", $this->id)->get();
    }

    /**
     * This Model has many through relations
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

    public function TrashedMonitors()
    {
        return Monitor::onlyTrashed()
            ->whereIn('area_id', Area::onlyTrashed()
                ->where("city_id", $this->id)
                ->pluck('id')
                ->toArray());
    }

    public function TrashedDelivers()
    {
        return Deliver::onlyTrashed()
            ->whereIn('area_id', Area::onlyTrashed()
                ->where("city_id", $this->id)
                ->pluck('id')
                ->toArray());
    }

    public function TrashedClients()
    {
        return User::onlyTrashed()->where('type', 'client')
            ->whereIn('area_id', Area::onlyTrashed()
                ->where("city_id", $this->id)
                ->pluck('id')
                ->toArray());
    }


    /**
     * Append 
     */
    public function getCountOfAreasAttribute()
    {
        return $this->deleted_at
            ? $this->areas()->onlyTrashed()->count()
            : $this->areas()->count();
    }
    public function getCountOfMonitorsAttribute()
    {
        return $this->deleted_at
            ? $this->TrashedMonitors()->count()
            : $this->Monitors()->count();
    }
    public function getCountOfDeliversAttribute()
    {
        return $this->deleted_at
            ? $this->TrashedDelivers()->count()
            : $this->Delivers()->count();
    }
    public function getCountOfClientsAttribute()
    {
        // dd($this->deleted_at?"d":$this->Clients()->onlyTrashed()->where("type", '=', 'client')->count());
        return $this->deleted_at
            ? $this->TrashedClients()->count()
            : $this->Clients()->where("type", '=', 'client')->count();
    }

    /**
     * Polymorphic-relationships "Morph" 
     */

    public function image(): MorphMany
    {
        return $this->morphMany(Image::class, "imageable");
    }
}

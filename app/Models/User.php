<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile',
        'uuid',
        'package_id',
        'profile_image',
        'subscription_fees',
        'type',
        'active',
        'expire',
        'area_id',
    ];

    protected static function booted()
    {
        static::creating(function ($user) {
            $user->uuid = (string) \Str::uuid();
        });
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function setAttributePassword($password)
    {
        return $this->attributes['password'] = Hash::make($password);
    }


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'name' => 'string',
        'email' => 'string',
        'mobile' => 'string',
        'uuid' => 'string',
        'package_id' => 'integer',
        'profile_image' => 'string',
        'subscription_fees' => 'double',
        'type' => 'string',
        'active' => 'boolean',
        // 'expire' => 'date:',
        'area_id' => 'integer',
    ];



    public function package()
    {
        return $this->belongsTo(Package::class, "package_id")->withTrashed();
    }
    public function area()
    {
        return $this->belongsTo(Area::class, "area_id");
    }
    public function clientOrders()
    {
        return $this->hasMany(Order::class, 'client_id');
    }
    public function deliverOrders()
    {
        return $this->hasMany(Order::class, 'deliver_id');
    }
    public function adminNotifications()
    {
        return $this->hasMany(AdminNotification::class, 'admin_id');
    }
    public function userGeneralNotifications()
    {
        return $this->hasMany(UserGeneralNotification::class, 'client_id');
    }
    public function addresses()
    {
        return $this->hasMany(Address::class, 'client_id');
    }
    public function customerToken()
    {
        return $this->hasOne(CustomerToken::class);
    }
    public function clientInvoices()
    {
        return $this->hasMany(Invoice::class, 'client_id');
    }
    public function deliverInvoices()
    {
        return $this->hasMany(Invoice::class, 'deliver_id');
    }
    public function rateServices()
    {
        return $this->hasMany(RateService::class, 'client_id');
    }
    public function monitors()
    {
        return $this->hasMany(Monitor::class, 'monitor_id');
    }
    public function ContactUs()
    {
        return $this->hasMany(ContactUs::class, 'client_id');
    }
    public function Deliver()
    {
        return $this->hasOne(Deliver::class, 'deliver_id');
    }
    public function areas()
    {
        return $this->belongsToMany(Area::class, 'area_user', 'user_id', 'area_id');
    }


    // public function MonitorArea () {
    //     return $this->hasManyThrough(Area::class , Monitor::class , "city_");
    // }



    /**
     * Polymorphic-relationships "Morph" 
     */

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, "imageable");
    }
}

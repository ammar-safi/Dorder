<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasFactory,SoftDeletes;

    // عدد المشتركين
    protected $append = [
        "count_of_clients",
        
    ];

    protected $fillable = [
        "title",
        "image",
        "count_of_orders",
        "package_price",
        "order_price",
    ];


    /**
     * Relation with Users (Client)
     */
    public function Clients () {
       return $this->hasMany(User::class);
    }


    /**
     * This method for $append->count_of_clients
     */
    public function getCountOfClientsAttribute() {
        return User::where("type" , "client")->where('package_id' , $this->id)->count() ; 
    }

    
}

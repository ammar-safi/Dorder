<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    use HasFactory;
    protected $fillable = [
        "client_id",
        "feedback",
         
    ];
    public function client () {
        return $this->belongsTo(User::class , 'client_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerToken extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'mobile',
        'token',
        'uuid',
    ];
    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class CustomerOtp extends Model
{
    use HasFactory,HasRoles,Notifiable;

    protected $fillable = [
        'phone',
        'otp',
        
    ];
}

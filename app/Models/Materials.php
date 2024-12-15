<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Materials extends Model
{
    use HasFactory,HasRoles,Notifiable;

    protected $fillable = [
        'image',
        'name',
        'qty',
        'description',
        'status'
    ];

    public function rentItemDetails()
    {
        return $this->hasMany(RentItemDetails::class, 'item');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Customers extends Model
{
    use HasFactory,HasRoles,Notifiable;


    protected $fillable = [
        'name',
        'phone_no',
        'alt_mobile',
        'id_type',
        'id_no',
        'customer_photo',
        'isfav',
        'status',
        'remarks',
        'photo',
        'created_at',
        'updated_at',
    ];

    public function rentDetails()
{
    return $this->hasMany(RentDetails::class, 'customer', 'id');
}


    public function returnItems()
    {
        return $this->hasMany(ReturnItems::class, 'customer');
    }

    public function rentItemDetails()
    {
        return $this->hasMany(RentItemDetails::class, 'customer','id');
    
}

}
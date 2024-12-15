<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class RentDetails extends Model
{
    use HasFactory,HasRoles,Notifiable;
protected $fillable = [
'customer',
'order_id',
'contact',
'items',
'qtys',
'rent_date',
'end_date',
'driver_image',
'driver_contact',
'totalCost',
'paid_amt',
'payment_status',
'status',
'approved_by',
];
public function customer()
{
    return $this->belongsTo(Customers::class, 'customer', 'id');
}


    public function rentItemDetails()
    {
        return $this->hasMany(RentItemDetails::class, 'order_id', 'order_id');
    }
    public function material()
    {
        return $this->belongsTo(Materials::class, 'item', 'id'); 
    }
}
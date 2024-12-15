<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class RentItemDetails extends Model
{
    use HasFactory,HasRoles,Notifiable;

    protected $fillable = [
'customer',
'order_id',
'item',
'qty',
'cost',
'status',
'for',
'duration',
'end_date',
'advance',
'balance',
'price',
'balance_amt',
'image',
'receive_image',
'user',
    ];


    public function material()
    {
        return $this->belongsTo(Materials::class, 'item', 'id');
    }
    
    public function customers()
    {
        return $this->belongsTo(Customers::class, 'customer', 'id');
    }
    
    public function rentDetail()
    {
        return $this->belongsTo(RentDetails::class, 'order_id', 'order_id');
    }
    public function returnitems()
    {
        return $this->hasMany(ReturnItems::class , 'order_id' , 'order_id' );
    }
    
}
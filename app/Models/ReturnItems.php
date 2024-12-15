<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class ReturnItems extends Model
{
    use HasFactory,HasRoles,Notifiable;

    protected $fillable = [
'customer',
'item_id',
'order_id',
'item',
'qty',
'return_image',
'paid_amt',
'return_date',
'contact',
'balance_amt',
'user',
    ];

    public function customers()
    {
        return $this->belongsTo(Customers::class, 'customer' , 'id');
    }

    // ReturnItem belongs to RentItemDetail
    public function rentItemDetail()
    {
        return $this->belongsTo(RentItemDetails::class, 'order_id' , 'order_id');
    }
}
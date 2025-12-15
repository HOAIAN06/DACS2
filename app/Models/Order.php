<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id','order_number','status','payment_status','payment_method',
        'subtotal','discount','shipping_fee','total',
        'customer_name','customer_phone','customer_email','shipping_address','note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}

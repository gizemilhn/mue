<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'address_id',
        'total_price',
        'shipping_price',
        'status',
        'payment_id'
    ];

    public function products()
    {
        return $this->hasMany(OrderProducts::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function shipping()
    {
        return $this->hasOne(Shipping::class);
    }
    public function returnRequest() {
        return $this->hasOne(ReturnRequest::class);
    }
}

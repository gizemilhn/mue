<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    protected $fillable = [
        'order_id',
        'address_id',
        'status',
        'tracking_number',
        'shipped_at',
        'delivered_at',

    ];
    protected $casts = [
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}

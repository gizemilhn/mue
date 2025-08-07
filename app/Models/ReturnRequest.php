<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnRequest extends Model
{
    protected $fillable = [
        'order_id', 'user_id', 'reason', 'photos', 'status',
        'return_code', 'cargo_company', 'rejection_reason'
    ];
    protected $casts = [
        'photos' => 'array'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function order() {
        return $this->belongsTo(Order::class);
    }
}

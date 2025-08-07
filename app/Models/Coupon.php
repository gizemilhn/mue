<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'usage_limit',
        'user_id',
        'expires_at',
        'is_active',
    ];
    protected $casts = [
        'expires_at' => 'datetime',
    ];
    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

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
        'min_amount',
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

    public function markAsUsed($userId, $orderId, $discountAmount)
    {
        $usage = $this->usages()->create([
            'user_id' => $userId,
            'order_id' => $orderId,
            'discount_amount' => $discountAmount,
            'used_at' => now()
        ]);
        if ($this->usage_limit && $this->usages()->count() >= $this->usage_limit) {
            $this->update(['is_active' => false]);
        }

        return $usage;
    }

    public function getRemainingUsesAttribute()
    {
        if (!$this->usage_limit) return null;
        return max(0, $this->usage_limit - $this->usages()->count());
    }

    public function canBeUsedBy(User $user, $cartTotal = null)
    {
        if ($this->user_id && $this->user_id != $user->id) {
            return false;
        }
        if (!$this->is_active) {
            return false;
        }
        if ($this->expires_at && now()->gt($this->expires_at)) {
            return false;
        }

        if ($this->usage_limit && $this->remaining_uses <= 0) {
            return false;
        }

        if ($cartTotal && $this->min_amount && $cartTotal < $this->min_amount) {
            return false;
        }
        return true;
    }
}

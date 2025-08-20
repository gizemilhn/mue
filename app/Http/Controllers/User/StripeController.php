<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripeController extends Controller
{
    public function checkout(Request $request)
    {
        $request->validate(['address_id' => 'required|exists:addresses,id']);

        $user = auth()->user();
        $carts = Cart::with('product')->where('user_id', $user->id)->get();

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Sepetiniz boş.');
        }

        // Kupon ve toplam hesaplamaları
        $coupon = session('applied_coupon');
        $discount = $coupon['discount'] ?? 0;
        $subtotal = $carts->sum(fn($c) => $c->product->price * $c->quantity);


        $freeShippingThreshold = 750;
        $shippingPrice = ($subtotal < $freeShippingThreshold) ? 49.90 : 0;

        // Stripe için toplam tutar (kuruş cinsinden)
        $totalAmount = max(50, round(($subtotal - $discount + $shippingPrice) * 100));

        // Tek bir line item olarak gönderiyoruz
        $lineItems = [[
            'price_data' => [
                'currency' => 'try',
                'product_data' => [
                    'name' => 'Sipariş Toplamı',
                ],
                'unit_amount' => $totalAmount,
            ],
            'quantity' => 1,
        ]];

        // Ödeme oturumu oluşturma
        Stripe::setApiKey(config('services.stripe.secret'));

        $checkout = Session::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}&address_id=' . $request->address_id,
            'cancel_url' => route('checkout.cancel'),
            'customer_email' => $user->email,
            'metadata' => [
                'user_id' => $user->id,
                'address_id' => $request->address_id,
                'coupon_code' => $coupon['code'] ?? null,
                'discount_amount' => $discount * 100,
                'cart_items' => $carts->count()
            ]
        ]);

        return redirect($checkout->url);
    }
}

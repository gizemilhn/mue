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
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
        ]);

        $user = auth()->user();
        $carts = Cart::with('product')->where('user_id', $user->id)->get();

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Sepetiniz boş.');
        }

        $lineItems = $carts->map(function ($cart) {
            return [
                'price_data' => [
                    'currency' => 'try',
                    'product_data' => ['name' => $cart->product->name],
                    'unit_amount' => $cart->product->price * 100,
                ],
                'quantity' => $cart->quantity,
            ];
        })->toArray();

        $freeShippingThreshold = 750;
        $totalCartPrice = $carts->sum(fn($c) => $c->product->price * $c->quantity);
        $shippingPrice = ($totalCartPrice < $freeShippingThreshold) ? 4990 : 0;
        if ($shippingPrice > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'try',
                    'product_data' => ['name' => 'Kargo Ücreti'],
                    'unit_amount' => $shippingPrice,
                ],
                'quantity' => 1,
            ];
        }
        Stripe::setApiKey(config('services.stripe.secret'));

        $checkout = Session::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}&address_id=' . $request->address_id,
            'cancel_url' => route('checkout.cancel'),
        ]);

        return redirect($checkout->url);
    }
}

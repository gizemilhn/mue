<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\OrderProducts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Session;
use Stripe\Stripe;


class CheckoutController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $addresses = $user->addresses;

        $carts = Cart::with('product.featuredImage', 'size')
            ->where('user_id', $user->id)
            ->get();

        $totalPrice = $carts->sum(function($cart) {
            return $cart->product->price * $cart->quantity;
        });
        foreach ($carts as $cart) {
            if ($cart->size_id) {
                $stock = DB::table('product_size')
                    ->where('product_id', $cart->product_id)
                    ->where('size_id', $cart->size_id)
                    ->value('stock');

                if ($stock < $cart->quantity) {
                    return redirect()->route('cart.index')->withErrors([
                        'stock' => "{$cart->product->name} ürününden {$cart->size->name} bedeninde yeterli stok yok."
                    ]);
                }
            } else {
                if ($cart->product->stock < $cart->quantity) {
                    return redirect()->route('cart.index')->withErrors([
                        'stock' => "{$cart->product->name} ürününden yeterli stok yok."
                    ]);
                }
            }
        }

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Sepetiniz boş.');
        }

        return view('checkout/index', compact('addresses', 'carts', 'totalPrice'));
    }
    public function storeAddress(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'address_line' => 'required|string',
            'city' => 'required|string',
            'district' => 'required|string',
            'phone' => 'required|string',
        ]);

        $address = auth()->user()->addresses()->create($request->all());

        return redirect()->route('checkout.index')->with('selected_address', $address->id);
    }

    public function success(Request $request)
    {
        $session_id = $request->get('session_id');
        $address_id = $request->get('address_id');

        if (!$session_id || !$address_id) {
            return redirect('/')->with('error', 'Geçersiz işlem.');
        }

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        $session = \Stripe\Checkout\Session::retrieve($session_id);

        if ($session->payment_status !== 'paid') {
            return redirect('/')->with('error', 'Ödeme alınamadı.');
        }

        $user = auth()->user();
        $carts = Cart::with(['product', 'size'])->where('user_id', $user->id)->get();

        if ($carts->isEmpty()) {
            return redirect('/')->with('error', 'Sepetiniz boş.');
        }

        $paymentIntentId = $session->payment_intent;
        $totalFromStripe = $session->amount_total / 100;

        // YENİ EK: Kupon indirimini ve kargo ücretini doğru hesapla
        $subtotal = $carts->sum(fn($cart) => $cart->product->discountedPrice * $cart->quantity);
        $couponDiscount = session('applied_coupon.discount') ?? 0;
        $freeShippingThreshold = 750;
        $shippingFee = ($subtotal - $couponDiscount < $freeShippingThreshold) ? 49.90 : 0;

        $order = null;

        DB::transaction(function () use ($user, $carts, $totalFromStripe, $shippingFee, $address_id, &$order, $paymentIntentId, $couponDiscount) {
            // Sipariş oluşturma
            $order = $user->orders()->create([
                'total_price' => $totalFromStripe,
                'shipping_price' => $shippingFee, // Buraya doğru kargo ücretini gönder
                'status' => 'pending',
                'address_id' => $address_id,
                'payment_id' => $paymentIntentId,
                'coupon_id' => session('applied_coupon.id') ?? null,
                'discount_amount' => $couponDiscount
            ]);

            // ... (Geri kalan kodlar aynı)
            // Kupon işlemleri
            if (session('applied_coupon')) {
                $coupon = Coupon::find(session('applied_coupon.id'));

                // Kupon kullanımını kaydet
                $coupon->markAsUsed(
                    $user->id,
                    $order->id,
                    session('applied_coupon.discount')
                );

                // Session'dan temizle
                session()->forget('applied_coupon');
            }

            // Sipariş ürünlerini ekleme
            foreach ($carts as $cart) {
                OrderProducts::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'size_id' => $cart->size_id,
                    'quantity' => $cart->quantity,
                    'price' => $cart->product->price,
                ]);

                // Stok güncelleme
                if ($cart->size_id) {
                    DB::table('product_size')
                        ->where('product_id', $cart->product_id)
                        ->where('size_id', $cart->size_id)
                        ->decrement('stock', $cart->quantity);
                }

                $cart->product->decrement('stock', $cart->quantity);
                $cart->delete();
            }
        });

        $user->notify(new \App\Notifications\OrderPlaced($order));
        return redirect()->route('user.order.details', $order->id);
    }

    public function cancel()
    {
        return redirect()->route('checkout.index')->with('error', 'Ödeme iptal edildi.');
    }

}

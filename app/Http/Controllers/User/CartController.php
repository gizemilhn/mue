<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function addToCart(Request $request, $productId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Lütfen giriş yapın.');
        }

        $product = Product::findOrFail($productId);
        $sizeId = $request->input('size_id');
        $quantity = $request->input('quantity', 1);

        if (!$sizeId) {
            return back()->with('error', 'Lütfen beden seçin.');
        }

        $size = $product->sizes()->where('sizes.id', $sizeId)->first();

        if (!$size || $size->pivot->stock < $quantity) {
            return back()->with('error', 'Seçilen bedende yeterli stok yok.');
        }

        Cart::create([
            'user_id' => Auth::id(),
            'product_id' => $productId,
            'size_id' => $sizeId,
            'quantity' => $quantity,
        ]);

        return redirect()->route('cart.index')->with('success', 'Ürün sepete eklendi.');
    }

    public function index()
    {
        $carts = Cart::with(['product.featuredImage', 'size'])->where('user_id', Auth::id())->get();

        $totalPrice = $carts->sum(function ($cart) {
            return $cart->product->price * $cart->quantity;
        });

        $totalQuantity = $carts->sum('quantity');

        $shippingCost = 0;

        $cartStocks = [];
        foreach ($carts as $cart) {
            $stock = $cart->size_id
                ? $cart->product->sizes()->where('sizes.id', $cart->size_id)->first()?->pivot->stock ?? 0
                : $cart->product->stock;

            $cartStocks[$cart->id] = $stock;
        }

        return view('home.cart', compact('carts', 'totalPrice', 'totalQuantity', 'shippingCost', 'cartStocks'));
    }

    public function update(Request $request)
    {
        try {
            $carts = $request->input('cart', []);

            foreach ($carts as $cartId => $cartData) {
                if (!isset($cartData['quantity'])) continue;

                $cartItem = Cart::find($cartId);
                if ($cartItem) {
                    $cartItem->quantity = (int) $cartData['quantity'];
                    $cartItem->save();
                }
            }

            $userCarts = Cart::with('product')->where('user_id', Auth::id())->get();

            $totalQuantity = $userCarts->sum('quantity');

            $totalPrice = $userCarts->sum(function ($cart) {
                return $cart->product->price * $cart->quantity;
            });

            return response()->json([
                'success' => true,
                'message' => 'Ürün miktarı güncellendi.',
                'totalPrice' => number_format($totalPrice, 2),
                'totalQuantity' => $totalQuantity,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sunucu hatası: ' . $e->getMessage()
            ], 500);
        }
    }

    public function remove(Request $request)
    {
        $cartItem = Cart::find($request->cart_id);
        if ($cartItem) {
            $cartItem->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }
}

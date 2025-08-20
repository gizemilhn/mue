<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    public function userOrders()
    {
        $user = Auth::user();
        $orders = $user->orders;
        $orders = Order::where('user_id', Auth::id())->latest()->get();
        return view('home.orders', compact('orders'));
    }
    public function orderDetails(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Bu siparişi görüntüleme yetkiniz yok.');
        }

        $order->load(['products.product',
            'products.product.featuredImage',
            'shipping',
            'products.product.sizes' => function($query) use ($order) {
        $query->whereIn('sizes.id',
            $order->products->pluck('size_id')->filter()->unique());
        },
        'coupon',
        'couponUsage']);

        return view('home.order-details', compact('order'));
    }

    public function cancel(Order $order)
    {
        // Siparişin iptal edilebilir olduğunu kontrol et
        if ($order->status !== 'pending') {
            return redirect()->route('user.order.details', $order->id)
                ->with('error', 'Sadece bekleyen siparişler iptal edilebilir.');
        }

        try {
            DB::transaction(function () use ($order) {
                // Sipariş durumunu güncelle
                $order->update(['status' => 'cancelled']);

                // Ürün ve stok bilgilerini yükle
                $order->load('products.product');

                // Stokları geri ekle
                $order->products->each(function ($item) {
                    if ($item->product) {
                        $item->product->increment('stock', $item->quantity);
                    }

                    if ($item->size_id) {
                        DB::table('product_size')
                            ->where('product_id', $item->product_id)
                            ->where('size_id', $item->size_id)
                            ->increment('stock', $item->quantity);
                    }
                });

                // Kargo bilgisini sil (varsa)
                if ($order->shipping) {
                    $order->shipping()->delete();
                }
            });

            return redirect()->route('user.order.details', $order->id)
                ->with('success', 'Sipariş başarıyla iptal edildi.');

        } catch (\Exception $e) {
            \Log::error('Sipariş iptal hatası: '.$e->getMessage());
            return redirect()->route('user.order.details', $order->id)
                ->with('error', 'Sipariş iptal edilirken bir hata oluştu: '.$e->getMessage());
        }
    }
}

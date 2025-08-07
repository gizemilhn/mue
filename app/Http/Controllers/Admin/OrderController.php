<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user', 'products.product')->get();
        return view('admin.orders.index', compact('orders'));
    }
    public function approve(Order $order)
    {
        $trackingNumber = 'TRK' . Str::upper(Str::random(8));

        DB::transaction(function () use ($order, $trackingNumber) {
            $order->update(['status' => 'approved']);

            Shipping::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'address_id' => $order->address_id,
                    'tracking_number' => $trackingNumber,
                    'status' => 'preparing',
                    'shipped_at' => null,
                    'delivered_at' => null
                ]
            );
        });
        toastr()->closeButton()->success('Sipariş onaylandı. Takip No: ' . $trackingNumber);
        return back();
    }

    public function cancel(Order $order)
    {
        DB::transaction(function () use ($order) {
            $order->update(['status' => 'cancelled']);

            $order->load('products.product');

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

            $order->shipping()->delete();
        });
        toastr()->closeButton()->success('Sipariş iptal edildi');
        return back();
    }
}

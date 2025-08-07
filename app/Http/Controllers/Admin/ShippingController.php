<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shipping;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function index(Request $request)
    {
        $query = Shipping::with(['order.user', 'address']);

        if ($request->filled('order_id')) {
            $query->where('order_id', $request->order_id);
        }

        if ($request->filled('customer_name')) {
            $query->whereHas('order.user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->customer_name . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $shippings = $query->latest()->get();

        return view('admin.shippings.index', compact('shippings'));
    }
    public function updateShippingStatus(Request $request, Shipping $shipping)
    {
        $validated = $request->validate([
            'status' => 'required|in:preparing,shipped,delivered,cancelled',
        ]);

        $shipping->status = $validated['status'];

        if ($validated['status'] == 'shipped') {
            $shipping->shipped_at = now();
            $order = $shipping->order; // shipping tablosunun order_id FK'si varsa
            $user = $order->user;
            $user->notify(new \App\Notifications\OrderShipped($order));
        }

        if ($validated['status'] == 'delivered') {
            $shipping->delivered_at = now();
            $order = $shipping->order;
            $user = $order->user;
            $user->notify(new \App\Notifications\OrderDelivered($order));
        }

        $shipping->save();

        toastr()->closeButton()->success('Kargo durumu güncellendi.');
        return back();
    }
    public function updateShippingTracking(Request $request, Shipping $shipping)
    {
        $request->validate([
            'tracking_number' => 'nullable|string|max:255',
        ]);

        $shipping->tracking_number = $request->tracking_number;
        $shipping->save();

        toastr()->closeButton()->success('Takip numarası güncellendi.');
        return back();
    }
}

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
        $order->load(['products.product', 'shipping']);

        return view('home.order-details', compact('order'));
    }

    public function return(Order $order)
    {

    }
}

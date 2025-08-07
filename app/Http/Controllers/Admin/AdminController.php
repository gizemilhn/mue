<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;



class AdminController extends Controller
{

    public function dashboard()
    {

        $newClientsCount = User::where('created_at', '>', now()->subMonth())->count();

        $totalClientsCount= User::where ('role','user')->count();

        $newOrdersCount = Order::where('status', 'pending')->count();

        $totalOrdersCount = Order::count();

        return view('admin.dashboard', compact(
            'newClientsCount','totalClientsCount', 'newOrdersCount', 'totalOrdersCount',
        ));
    }
    public function admin_home(){
        $newClientsCount = User::where('created_at', '>', now()->subMonth())->count();

        $totalClientsCount= User::where ('role','user')->count();

        $newOrdersCount = Order::where('status', 'pending')->count();

        $totalOrdersCount = Order::count();

        return view('admin.index', compact(
            'newClientsCount','totalClientsCount', 'newOrdersCount', 'totalOrdersCount',
        ));
    }




}

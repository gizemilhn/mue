<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ReturnRequest;
use App\Models\User;



class AdminController extends Controller
{

    public function dashboard()
    {

        $pendingOrdersCount=Order::where('status', 'pending')->count();
        $pendingReturnsCount=ReturnRequest::where('status', 'pending')->count();

        
        $newClientsCount = User::where('created_at', '>', now()->subMonth())->count();
        $totalClientsCount= User::where ('role','user')->count();


        return view('admin.dashboard', compact(
            'newClientsCount','totalClientsCount','pendingOrdersCount','pendingReturnsCount',
        ));
    }

}

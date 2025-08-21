<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\ReturnRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
{

    public function dashboard()
    {
        $totalCustomers=User::where('role','user')
            ->count();

        $pendingOrders=Order::where('status', 'pending')
            ->with('user')
            ->latest()
            ->take(5)
            ->get();
        $pendingReturns=ReturnRequest::where('status', 'pending')
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        $lowStockProducts = DB::table('product_size')
            ->join('products', 'product_size.product_id', '=', 'products.id')
            ->join('sizes', 'product_size.size_id', '=', 'sizes.id')
            ->select('products.name as product_name', 'sizes.name as size_name', 'product_size.stock')
            ->where('product_size.stock', '<=', 5)
            ->get();
        $topCategory = Category::select('categories.category_name', DB::raw('COUNT(orders.id) as sales_count'))
            ->join('products', 'products.category', '=', 'categories.category_name')
            ->join('order_products', 'order_products.product_id', '=', 'products.id')
            ->join('orders', 'order_products.order_id', '=', 'orders.id')
            ->groupBy('categories.category_name')
            ->orderByDesc('sales_count')
            ->first();

        $usersByDay = User::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $userDates = $usersByDay->pluck('date')->map(fn($date) => Carbon::parse($date)->format('d M'));
        $userCounts = $usersByDay->pluck('count');

        $salesByDay = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_price) as total_sales'))
            ->where('status', 'approved')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $salesDates = $salesByDay->pluck('date')->map(fn($date) => Carbon::parse($date)->format('d M'));
        $salesTotals = $salesByDay->pluck('total_sales');

        return view('admin.dashboard', compact(
            'totalCustomers','userCounts','userDates','usersByDay','salesTotals','salesDates','pendingOrders','pendingReturns','lowStockProducts','topCategory'
        ));
    }

}

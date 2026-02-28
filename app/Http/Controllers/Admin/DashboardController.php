<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders   = Order::count();
        $totalRevenue  = Order::where('payment_status', 'paid')->sum('total_amount');
        $recentOrders  = Order::latest()->take(10)->get();
        $lowStock      = Product::where('stock_quantity', '<=', 5)->where('stock_quantity', '>', 0)->get();
        $outOfStock    = Product::where('stock_quantity', 0)->count();
        $totalProducts = Product::count();
        $newOrders     = Order::where('order_status', 'new')->count();

        return view('admin.dashboard', compact(
            'totalOrders', 'totalRevenue', 'recentOrders',
            'lowStock', 'outOfStock', 'totalProducts', 'newOrders'
        ));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard with sales reports and inventory indicators.
     */
    public function dashboard()
    {
        // 1. Sales Report Metrics
        $totalRevenue = Order::sum('total_price');
        $ordersCount = Order::count();
        $productsCount = Product::count();

        // 2. Best-selling products (grouped by product_id, sum of quantity purchased)
        $bestSellers = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->with('product.category')
            ->take(5)
            ->get();

        // 3. Recent orders list
        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        // 4. Low stock/out of stock warning list
        $alertProducts = Product::with('category')
            ->where('stock', '<=', 5)
            ->orderBy('stock', 'asc')
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'ordersCount',
            'productsCount',
            'bestSellers',
            'recentOrders',
            'alertProducts'
        ));
    }
}

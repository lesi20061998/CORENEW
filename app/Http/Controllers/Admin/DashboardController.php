<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\OrderItem;
use App\Services\ProductService;
use App\Services\AttributeService;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct(
        protected ProductService $productService,
        protected AttributeService $attributeService
    ) {
    }

    public function index()
    {
        $stats = [
            // High Level Metrics
            'total_revenue' => Order::where('status', '!=', 'cancelled')->sum('total'),
            'revenue_3m' => Order::where('status', '!=', 'cancelled')->where('created_at', '>=', now()->subMonths(3))->sum('total'),
            'revenue_6m' => Order::where('status', '!=', 'cancelled')->where('created_at', '>=', now()->subMonths(6))->sum('total'),
            'monthly_revenue' => Order::where('status', '!=', 'cancelled')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total'),

            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_customers' => User::where('role', '!=', 'admin')->count(),
            'total_products' => $this->productService->getAllProducts()->count(),
            'active_products' => $this->productService->getActiveProducts()->count(),

            // Product Specific Performance
            'top_selling' => OrderItem::select('order_items.product_id', 'order_items.product_name', 'order_items.image', DB::raw('SUM(order_items.quantity) as total_qty'), DB::raw('SUM(order_items.total) as revenue'))
                ->join('orders', 'orders.id', '=', 'order_items.order_id')
                ->where('orders.status', '!=', 'cancelled')
                ->groupBy('order_items.product_id', 'order_items.product_name', 'order_items.image')
                ->orderBy('total_qty', 'desc')
                ->take(5)
                ->get()
                ->map(function ($item) {
                    $img = $item->image;
                    $item->image_url = $img
                        ? (str_starts_with($img, 'http') ? $img : (str_starts_with($img, 'media/') ? asset('storage/' . $img) : asset($img)))
                        : asset('theme/images/no-image.png');
                    return $item;
                }),

            'low_selling' => Product::whereDoesntHave('orderItems')
                ->take(5)
                ->get(),

            // Recent Data
            'recent_orders' => Order::latest()->take(5)->get(),

            // Monthly chart data (last 6 months)
            'revenue_chart' => Order::select(
                DB::raw('SUM(total) as revenue'),
                DB::raw("DATE_FORMAT(created_at, '%m/%Y') as month"),
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month_key")
            )
                ->where('status', '!=', 'cancelled')
                ->groupBy('month', 'month_key')
                ->orderBy('month_key', 'desc')
                ->take(6)
                ->get()
                ->reverse()
                ->values(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
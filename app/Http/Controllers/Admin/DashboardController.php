<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Order;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Thống kê cơ bản
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalUsers = User::where('is_admin', false)->count();
        $totalOrders = Order::count();

        // Doanh thu
        $totalRevenue = Order::where('status', '!=', 'canceled')->sum('total');
        $monthlyRevenue = Order::where('status', '!=', 'canceled')
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('total');

        // Hiệu suất đơn hàng
        $todayOrders = Order::whereDate('created_at', Carbon::today())->count();
        $pendingToday = Order::whereDate('created_at', Carbon::today())
            ->where('status', 'pending')
            ->count();

        // AOV (giá trị đơn trung bình, loại trừ đơn hủy)
        $totalCompletedOrders = Order::where('status', '!=', 'canceled')->count();
        $averageOrderValue = $totalCompletedOrders > 0 ? $totalRevenue / $totalCompletedOrders : 0;

        // Cảnh báo tồn kho thấp
        $lowStockThreshold = 5;
        $lowStockCount = Product::where('stock', '<=', $lowStockThreshold)->count();

        // Dữ liệu biểu đồ doanh thu 12 tháng gần nhất
        $revenueData = [];
        $labels = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $labels[] = $month->format('m/Y');
            $revenue = Order::where('status', '!=', 'canceled')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('total');
            $revenueData[] = $revenue;
        }

        // Đơn hàng mới
        $newOrders = Order::where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        // Sản phẩm bán chạy
        $topProducts = Product::withCount('variants')
            ->orderBy('variants_count', 'desc')
            ->take(5)
            ->get();

        // Khách mới
        $newUsers = User::where('is_admin', false)
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalUsers',
            'totalOrders',
            'totalRevenue',
            'monthlyRevenue',
            'todayOrders',
            'pendingToday',
            'averageOrderValue',
            'lowStockCount',
            'newOrders',
            'topProducts',
            'newUsers',
            'revenueData',
            'labels'
        ));
    }
}

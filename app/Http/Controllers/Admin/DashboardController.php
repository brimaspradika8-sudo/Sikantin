<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalSellers = User::where('role', 'seller')->count();
        $pendingSellers = User::where('role', 'seller')->where('status', 'pending')->count();
        $totalProducts = Product::count();
        $totalTransactions = Order::count();
        $totalRevenue = Order::whereIn('status', ['paid', 'processing', 'ready', 'completed'])->sum('total_amount');

        $recentOrders = Order::with(['user', 'seller'])->latest()->take(6)->get();

        $dailyRevenue = Order::whereIn('status', ['paid', 'processing', 'ready', 'completed'])
            ->where('created_at', '>=', Carbon::now()->subDays(6))
            ->selectRaw('DATE(created_at) as day, SUM(total_amount) as revenue')
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->pluck('revenue', 'day')
            ->toArray();

        $dailyLabels = [];
        $dailyData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dailyLabels[] = $date->format('d M');
            $dailyData[] = $dailyRevenue[$date->format('Y-m-d')] ?? 0;
        }

        $recentAudit = AuditLog::with('actor')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalSellers',
            'pendingSellers',
            'totalProducts',
            'totalTransactions',
            'totalRevenue',
            'recentOrders',
            'dailyLabels',
            'dailyData',
            'recentAudit'
        ));
    }
}

<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $sellerId = $request->user()->id;

        $totalProducts = Product::where('user_id', $sellerId)->count();
        $totalOrders = Order::where('seller_id', $sellerId)->count();
        $totalRevenue = Order::where('seller_id', $sellerId)
            ->whereIn('status', ['paid', 'processing', 'ready', 'completed'])
            ->sum('total_amount');

        $bestSelling = OrderItem::with('product')
            ->whereHas('order', function ($query) use ($sellerId) {
                $query->where('seller_id', $sellerId)
                    ->whereIn('status', ['paid', 'processing', 'ready', 'completed']);
            })
            ->select('product_id')
            ->selectRaw('SUM(quantity) as quantity_sold')
            ->groupBy('product_id')
            ->orderByDesc('quantity_sold')
            ->limit(5)
            ->get();

        $salesByDay = Order::where('seller_id', $sellerId)
            ->whereIn('status', ['paid', 'processing', 'ready', 'completed'])
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as amount')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $dailyLabels = [];
        $dailyData = [];
        for ($days = 6; $days >= 0; $days--) {
            $date = now()->subDays($days);
            $key = $date->format('Y-m-d');
            $dailyLabels[] = $date->format('d M');
            $dailyData[] = $salesByDay[$key]->amount ?? 0;
        }

        $dateFormat = DB::getDriverName() === 'sqlite'
            ? "strftime('%Y-%m', created_at)"
            : "DATE_FORMAT(created_at, '%Y-%m')";

        $salesByMonth = Order::where('seller_id', $sellerId)
            ->whereIn('status', ['paid', 'processing', 'ready', 'completed'])
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->selectRaw("{$dateFormat} as month, SUM(total_amount) as amount")
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $monthlyLabels = [];
        $monthlyData = [];
        for ($months = 5; $months >= 0; $months--) {
            $date = now()->subMonths($months);
            $key = $date->format('Y-m');
            $monthlyLabels[] = $date->format('M');
            $monthlyData[] = $salesByMonth[$key]->amount ?? 0;
        }

        return view('seller.dashboard', compact(
            'totalProducts',
            'totalOrders',
            'totalRevenue',
            'bestSelling',
            'dailyLabels',
            'dailyData',
            'monthlyLabels',
            'monthlyData'
        ));
    }
}

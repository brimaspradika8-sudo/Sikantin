<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class RevenueController extends Controller
{
    public function index(Request $request)
    {
        $sellerId = $request->user()->id;

        $orders = Order::with(['user', 'payment', 'qrcode'])
            ->where('seller_id', $sellerId)
            ->latest()
            ->get();

        $totalRevenue = $orders->whereIn('status', ['paid', 'processing', 'ready', 'completed'])
            ->sum('total_amount');

        $todayRevenue = $orders->where('created_at', '>=', now()->startOfDay())
            ->whereIn('status', ['paid', 'processing', 'ready', 'completed'])
            ->sum('total_amount');

        $monthlyRevenue = $orders->where('created_at', '>=', now()->startOfMonth())
            ->whereIn('status', ['paid', 'processing', 'ready', 'completed'])
            ->sum('total_amount');

        return view('seller.revenue', compact('orders', 'totalRevenue', 'todayRevenue', 'monthlyRevenue'));
    }
}

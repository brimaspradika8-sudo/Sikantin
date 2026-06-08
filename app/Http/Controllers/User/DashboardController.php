<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $popular = Product::with('category', 'seller')
            ->withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->limit(4)
            ->get();

        $latest = Product::with('category', 'seller')
            ->latest()
            ->limit(4)
            ->get();

        $categories = Category::withCount('products')->get();

        $favoriteProducts = $request->user()?->orders()
            ->with('items.product')
            ->latest()
            ->get()
            ->flatMap(fn($order) => $order->items->pluck('product'))
            ->unique('id')
            ->take(4);

        $announcements = [
            ['title' => 'Promo Minuman Gratis', 'subtitle' => 'Dapatkan minuman gratis setiap pembelian 2 menu utama.', 'tag' => 'Promo'],
            ['title' => 'Kantin Siaga Ramadhan', 'subtitle' => 'Menu spesial sahur dan berbuka tersedia setiap hari.', 'tag' => 'Info'],
        ];

        $testimonials = [
            ['name' => 'Alya', 'comment' => 'Makanannya enak, pesanannya cepat datang!', 'rating' => 5],
            ['name' => 'Bima', 'comment' => 'Fitur pembayaran QR sangat praktis untuk kantin.', 'rating' => 4],
            ['name' => 'Citra', 'comment' => 'Suka banget kategori dan filter produknya.', 'rating' => 5],
        ];

        return view('user.dashboard', compact('popular', 'latest', 'categories', 'favoriteProducts', 'announcements', 'testimonials'));
    }
}

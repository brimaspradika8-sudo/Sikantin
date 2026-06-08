<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Notification;
use Illuminate\Http\Request;

class SellerOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('seller'); // custom middleware to check seller role
    }

    public function index(Request $request)
    {
        $query = auth()->user()->sales()
            ->with(['user', 'items.menuItem', 'payment'])
            ->orderBy('created_at', 'desc');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', "%{$request->search}%")
                    ->orWhereHas('user', function ($q) use ($request) {
                        $q->where('name', 'like', "%{$request->search}%");
                    });
            });
        }

        $orders = $query->paginate(20);
        $stats = $this->getSellerStats();

        return view('seller.orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        return view('seller.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $validated = $request->validate([
            'status' => 'required|in:processing,ready,completed,cancelled',
            'notes' => 'nullable|string|max:500',
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $validated['status']]);

        $statusMessages = [
            'processing' => 'Pesanan sedang diproses',
            'ready' => 'Pesanan siap diambil',
            'completed' => 'Pesanan selesai',
            'cancelled' => 'Pesanan dibatalkan',
        ];

        Notification::create([
            'user_id' => $order->user_id,
            'order_id' => $order->id,
            'type' => "order_{$validated['status']}",
            'title' => $statusMessages[$validated['status']],
            'message' => "Pesanan #{$order->order_number} {$statusMessages[$validated['status']]}",
            'icon' => $this->getStatusIcon($validated['status']),
            'color' => $this->getStatusColor($validated['status']),
        ]);

        return back()->with('success', 'Status pesanan berhasil diperbarui');
    }

    public function confirmPayment(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        if ($order->payment->payment_method !== 'cash_on_pickup') {
            return back()->withError('Metode pembayaran tidak sesuai');
        }

        $order->payment->update([
            'payment_status' => 'success',
            'paid_at' => now(),
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        Notification::create([
            'user_id' => $order->user_id,
            'order_id' => $order->id,
            'type' => 'payment_verified',
            'title' => 'Pembayaran Dikonfirmasi',
            'message' => 'Pembayaran pesanan telah dikonfirmasi',
            'icon' => 'check-circle',
            'color' => 'success',
        ]);

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi');
    }

    public function dashboard()
    {
        $stats = $this->getSellerStats();

        return view('seller.dashboard', compact('stats'));
    }

    private function getSellerStats()
    {
        $today = now()->startOfDay();

        return [
            'total_orders_today' => auth()->user()->sales()
                ->whereDate('created_at', $today)
                ->count(),
            'total_revenue_today' => auth()->user()->sales()
                ->whereDate('created_at', $today)
                ->sum('total_amount'),
            'orders_processing' => auth()->user()->sales()
                ->whereIn('status', ['processing'])
                ->count(),
            'orders_completed' => auth()->user()->sales()
                ->where('status', 'completed')
                ->count(),
            'best_selling_items' => \DB::table('order_items')
                ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.seller_id', auth()->id())
                ->select('menu_items.name', \DB::raw('SUM(order_items.quantity) as total_sold'))
                ->groupBy('menu_items.id', 'menu_items.name')
                ->orderByDesc('total_sold')
                ->limit(5)
                ->get(),
        ];
    }

    private function getStatusIcon($status): string
    {
        return match($status) {
            'processing' => 'clock',
            'ready' => 'check',
            'completed' => 'check-circle',
            'cancelled' => 'x-circle',
            default => 'info',
        };
    }

    private function getStatusColor($status): string
    {
        return match($status) {
            'processing' => 'warning',
            'ready' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }
}

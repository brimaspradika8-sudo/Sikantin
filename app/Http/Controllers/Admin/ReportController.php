<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'daily');
        $filters = ['daily' => 'Harian', 'weekly' => 'Mingguan', 'monthly' => 'Bulanan', 'yearly' => 'Tahunan'];
        $data = $this->prepareReport($period);

        return view('admin.reports.index', array_merge($data, [
            'period' => $period,
            'periodLabel' => $filters[$period] ?? 'Harian',
            'filters' => $filters,
        ]));
    }

    public function export(Request $request)
    {
        $period = $request->get('period', 'daily');
        $data = $this->prepareReport($period);
        $html = view('admin.reports.export', array_merge($data, [
            'period' => $period,
            'periodLabel' => $this->getPeriodLabel($period),
        ]))->render();

        $filename = 'laporan-' . $period . '-' . now()->format('Ymd') . '.html';

        return response()->streamDownload(function () use ($html) {
            echo $html;
        }, $filename, ['Content-Type' => 'text/html']);
    }

    protected function prepareReport(string $period): array
    {
        $orders = $this->queryPeriod($period)->with(['user', 'seller'])->get();
        $revenueOrders = $orders->whereIn('status', ['paid', 'processing', 'ready', 'completed']);

        return [
            'orders' => $orders,
            'summary' => [
                'total_orders' => $orders->count(),
                'total_revenue' => $revenueOrders->sum('total_amount'),
                'paid_orders' => $orders->where('status', 'paid')->count(),
                'processing_orders' => $orders->where('status', 'processing')->count(),
                'ready_orders' => $orders->where('status', 'ready')->count(),
                'completed_orders' => $orders->where('status', 'completed')->count(),
                'cancelled_orders' => $orders->where('status', 'cancelled')->count(),
            ],
        ];
    }

    protected function queryPeriod(string $period)
    {
        return match ($period) {
            'weekly' => Order::where('created_at', '>=', Carbon::now()->subWeek()),
            'monthly' => Order::where('created_at', '>=', Carbon::now()->subMonth()),
            'yearly' => Order::where('created_at', '>=', Carbon::now()->subYear()),
            default => Order::where('created_at', '>=', Carbon::now()->subDay()),
        };
    }

    protected function getPeriodLabel(string $period): string
    {
        return match ($period) {
            'weekly' => 'Mingguan',
            'monthly' => 'Bulanan',
            'yearly' => 'Tahunan',
            default => 'Harian',
        };
    }
}

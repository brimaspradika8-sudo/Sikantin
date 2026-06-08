<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan {{ $periodLabel }}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #111827; }
        .header { margin-bottom: 24px; }
        .header h1 { font-size: 24px; margin-bottom: 8px; }
        .summary { margin-bottom: 24px; }
        .summary div { margin-bottom: 8px; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #d1d5db; padding: 10px; text-align: left; }
        .table th { background: #f9fafb; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan {{ $periodLabel }}</h1>
        <p>Dihasilkan pada {{ now()->format('d M Y H:i') }}</p>
    </div>
    <div class="summary">
        <div><strong>Total Pesanan:</strong> {{ $summary['total_orders'] }}</div>
        <div><strong>Total Pendapatan:</strong> Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</div>
        <div><strong>Status Dibayar:</strong> {{ $summary['paid_orders'] }}</div>
        <div><strong>Status Diproses:</strong> {{ $summary['processing_orders'] }}</div>
        <div><strong>Status Siap Diambil:</strong> {{ $summary['ready_orders'] }}</div>
        <div><strong>Status Selesai:</strong> {{ $summary['completed_orders'] }}</div>
        <div><strong>Status Dibatalkan:</strong> {{ $summary['cancelled_orders'] }}</div>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Pesanan</th>
                <th>Pembeli</th>
                <th>Penjual</th>
                <th>Total</th>
                <th>Status</th>
                <th>Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td>{{ $order->seller->store_name ?? '-' }}</td>
                    <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    <td>{{ $order->statusLabel() }}</td>
                    <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

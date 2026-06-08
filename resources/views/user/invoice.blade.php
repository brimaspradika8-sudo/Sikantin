<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice {{ $order->payment?->invoice_number ?? $order->order_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">
    <main class="max-w-4xl mx-auto my-8 bg-white rounded-lg shadow-sm border border-gray-200 p-8">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-6 border-b border-gray-200 pb-6">
            <div>
                <p class="text-sm uppercase tracking-wide text-gray-500">SIKANTIN Online</p>
                <h1 class="text-3xl font-black mt-1">Invoice</h1>
                <p class="text-gray-500 mt-2">{{ $order->payment?->invoice_number ?? '-' }}</p>
            </div>
            <div class="text-left sm:text-right">
                <p class="font-semibold">{{ $order->order_number }}</p>
                <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</p>
                <span class="inline-flex mt-3 rounded-lg bg-gray-100 px-3 py-2 text-sm font-semibold">{{ $order->payment?->statusLabel() ?? $order->statusLabel() }}</span>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-3 py-6 border-b border-gray-200">
            <div>
                <p class="text-xs uppercase text-gray-500">Pembeli</p>
                <p class="font-semibold mt-1">{{ $order->user->name }}</p>
                <p class="text-sm text-gray-500">{{ $order->user->email }}</p>
            </div>
            <div>
                <p class="text-xs uppercase text-gray-500">Penjual</p>
                <p class="font-semibold mt-1">{{ $order->seller->name ?? $order->vendor?->name ?? 'Penjual' }}</p>
            </div>
            <div>
                <p class="text-xs uppercase text-gray-500">Metode Bayar</p>
                <p class="font-semibold mt-1">{{ strtoupper(str_replace('_', ' ', $order->payment_method)) }}</p>
            </div>
        </div>

        <div class="py-6">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 text-left text-gray-500">
                        <th class="py-3">Produk</th>
                        <th class="py-3 text-right">Harga</th>
                        <th class="py-3 text-right">Jumlah</th>
                        <th class="py-3 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr class="border-b border-gray-100">
                            <td class="py-4 font-semibold">{{ $item->product->name }}</td>
                            <td class="py-4 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="py-4 text-right">{{ $item->quantity }}</td>
                            <td class="py-4 text-right font-semibold">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="grid gap-6 sm:grid-cols-[1fr_280px]">
            <div class="rounded-lg border border-gray-200 p-4">
                <p class="text-sm font-semibold">QR Code Transaksi</p>
                <img src="https://api.qrserver.com/v1/create-qr-code/?data={{ urlencode($order->payment?->invoice_number ?? $order->order_number) }}&size=160x160" alt="QR invoice" class="mt-4 h-40 w-40">
                @if($order->customer_note)
                    <p class="mt-4 text-sm text-gray-500">Catatan: {{ $order->customer_note }}</p>
                @endif
            </div>

            <div class="rounded-lg bg-gray-50 border border-gray-200 p-4 space-y-3">
                <div class="flex justify-between text-sm"><span>Subtotal</span><span>Rp {{ number_format($order->items->sum(fn($item) => $item->quantity * $item->price), 0, ',', '.') }}</span></div>
                <div class="flex justify-between text-sm"><span>Diskon</span><span>- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span></div>
                <div class="flex justify-between text-sm"><span>Pajak</span><span>Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</span></div>
                <div class="flex justify-between text-sm"><span>Biaya layanan</span><span>Rp {{ number_format($order->service_fee, 0, ',', '.') }}</span></div>
                <div class="pt-3 border-t border-gray-200 flex justify-between text-lg font-black"><span>Total</span><span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span></div>
            </div>
        </div>

        <div class="mt-8 flex flex-wrap gap-3 print:hidden">
            <button onclick="window.print()" class="inline-flex items-center px-5 py-3 rounded-lg bg-gray-900 text-white font-semibold">Cetak Invoice</button>
            <a href="{{ route('user.invoice.download', $order) }}" class="inline-flex items-center px-5 py-3 rounded-lg border border-gray-300 font-semibold">Download</a>
            <a href="{{ route('user.payment') }}" class="inline-flex items-center px-5 py-3 rounded-lg border border-gray-300 font-semibold">Kembali</a>
        </div>
    </main>
</body>
</html>

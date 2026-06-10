<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">Detail Pesanan</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Lihat status pesanan, QR pengambilan, dan cetak invoice.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $order->order_number }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $order->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div class="inline-flex items-center gap-2 rounded-full px-4 py-2 {{ $order->statusClass() }}">
                            {{ $order->statusLabel() }}
                        </div>
                    </div>

                    <div class="grid gap-6 mb-6">
                        @foreach($order->items as $item)
                            <div class="rounded-3xl border border-gray-200 dark:border-gray-700 p-5 bg-gray-50 dark:bg-gray-900">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ optional($item->product)->name ?? 'Produk tidak tersedia' }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $item->quantity }} x Rp {{ number_format($item->price ?? optional($item->product)->price ?? 0, 0, ',', '.') }}</p>
                                    </div>
                                    <p class="font-bold text-gray-900 dark:text-white">Rp {{ number_format($item->quantity * ($item->price ?? optional($item->product)->price ?? 0), 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="rounded-3xl border border-gray-200 dark:border-gray-700 p-6 bg-gray-50 dark:bg-gray-900">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-gray-500 dark:text-gray-400">Total Pesanan</span>
                            <span class="font-extrabold text-gray-900 dark:text-white">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-gray-500 dark:text-gray-400">Metode Pembayaran</span>
                            <span class="text-gray-900 dark:text-white">{{ strtoupper($order->payment_method) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Status Pembayaran</span>
                            <span class="text-gray-900 dark:text-white">{{ ucfirst($order->payment->payment_status ?? 'pending') }}</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Informasi Penjemputan</h3>
                        @if($order->qrcode)
                            <div class="grid gap-4 text-center">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?data={{ urlencode($order->qrcode->token) }}&size=260x260" alt="QR Pengambilan" class="mx-auto" />
                                <p class="text-sm text-gray-500 dark:text-gray-400">Token: <span class="font-semibold text-gray-900 dark:text-white">{{ $order->qrcode->token }}</span></p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Masa berlaku sampai {{ $order->qrcode->expires_at->format('d M Y H:i') }}</p>
                                @if($order->qrcode->is_used)
                                    <p class="text-sm text-red-600">QR sudah digunakan.</p>
                                @elseif($order->qrcode->is_expired)
                                    <p class="text-sm text-yellow-600">QR sudah kadaluarsa.</p>
                                @else
                                    <p class="text-sm text-green-600">QR siap discan oleh penjual.</p>
                                @endif
                            </div>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">QR belum tersedia.</p>
                        @endif
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Catatan Pesanan</h3>
                        <p class="text-gray-500 dark:text-gray-400">Pesanan offline akan aktif setelah disetujui penjual. Untuk e-wallet, tunjukkan QR kepada penjual agar status dapat diproses.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

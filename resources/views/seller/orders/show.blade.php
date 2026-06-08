<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">Detail Pesanan</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Lihat detail pesanan dan kelola status pembayaran.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                @if($order->payment && $order->payment->payment_status === 'pending' && ($order->payment->payment_proof || in_array($order->payment_method, ['cash_pickup', 'pay_at_canteen'])))
                    <form action="{{ route('seller.orders.approve-payment', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-green-500 text-white hover:bg-green-600 transition">
                            <i class="fa-solid fa-check"></i> Setujui Pembayaran
                        </button>
                    </form>
                @endif
                <a href="{{ route('seller.orders.index') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-full border border-brand-500 text-brand-600 hover:bg-brand-50 transition">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid gap-8 xl:grid-cols-[280px_1fr]">
            @include('seller.partials.sidebar')
            <div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $order->order_number }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Dibuat pada {{ $order->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div class="inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold {{ $order->statusClass() }}">
                            {{ $order->statusLabel() }}
                        </div>
                    </div>

                    <div class="grid gap-6 lg:grid-cols-[1fr_340px]">
                        <div class="space-y-4">
                            <div class="rounded-3xl border border-gray-200 dark:border-gray-700 p-5 bg-gray-50 dark:bg-gray-900">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi Pembeli</h4>
                                <p class="mt-3 text-gray-500 dark:text-gray-400">{{ $order->user->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $order->user->email }}</p>
                            </div>

                            <div class="rounded-3xl border border-gray-200 dark:border-gray-700 p-5 bg-gray-50 dark:bg-gray-900">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Produk</h4>
                                <div class="space-y-4 mt-4">
                                    @foreach($order->items as $item)
                                        <div class="rounded-3xl border border-gray-200 dark:border-gray-700 p-4 bg-white dark:bg-gray-800">
                                            <div class="flex items-center justify-between gap-4">
                                                <div>
                                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $item->product->name }}</p>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                                </div>
                                                <p class="font-bold text-gray-900 dark:text-white">Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="rounded-3xl border border-gray-200 dark:border-gray-700 p-5 bg-gray-50 dark:bg-gray-900">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Ringkasan Pesanan</h4>
                                <div class="mt-4 space-y-3 text-sm text-gray-500 dark:text-gray-400">
                                    <div class="flex justify-between"><span>Total</span><span class="font-semibold text-gray-900 dark:text-white">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span></div>
                                    <div class="flex justify-between"><span>Metode</span><span class="text-gray-900 dark:text-white">{{ strtoupper($order->payment_method) }}</span></div>
                                    <div class="flex justify-between"><span>Status Pembayaran</span><span class="text-gray-900 dark:text-white">{{ $order->payment?->statusLabel() ?? 'Menunggu Pembayaran' }}</span></div>
                                    <div class="flex justify-between"><span>Invoice</span><span class="text-gray-900 dark:text-white">{{ $order->payment->invoice_number ?? '-' }}</span></div>
                                </div>
                            </div>

                            @if($order->payment && $order->payment->payment_proof)
                                <div class="rounded-3xl border border-gray-200 dark:border-gray-700 p-5 bg-gray-50 dark:bg-gray-900">
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Bukti Transfer</h4>
                                    <div class="mt-4">
                                        @if(\Illuminate\Support\Str::endsWith($order->payment->payment_proof, ['.jpg', '.jpeg', '.png']))
                                            <img src="{{ asset('storage/'.$order->payment->payment_proof) }}" alt="Bukti transfer" class="w-full rounded-2xl border border-gray-200 dark:border-gray-700">
                                        @else
                                            <a href="{{ asset('storage/'.$order->payment->payment_proof) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-3 rounded-full border border-brand-500 text-brand-600">
                                                <i class="fa-solid fa-file-pdf"></i> Lihat Bukti Transfer
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if($order->payment && $order->payment->histories->isNotEmpty())
                                <div class="rounded-3xl border border-gray-200 dark:border-gray-700 p-5 bg-gray-50 dark:bg-gray-900">
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Riwayat Pembayaran</h4>
                                    <div class="mt-4 space-y-3">
                                        @foreach($order->payment->histories->sortByDesc('created_at') as $history)
                                            <div class="rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-3">
                                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ str_replace('_', ' ', $history->to_status) }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $history->created_at->format('d M Y H:i') }} oleh {{ $history->actor?->name ?? 'Sistem' }}</p>
                                                @if($history->note)
                                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $history->note }}</p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="rounded-3xl border border-gray-200 dark:border-gray-700 p-5 bg-gray-50 dark:bg-gray-900">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">QR Pengambilan</h4>
                                @if($order->qrcode)
                                    <div class="text-center">
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?data={{ urlencode($order->qrcode->token) }}&size=220x220" alt="QR Pengambilan" class="mx-auto mb-4" />
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Token: <span class="font-semibold text-gray-900 dark:text-white">{{ $order->qrcode->token }}</span></p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Berlaku sampai {{ $order->qrcode->expires_at->format('d M Y H:i') }}</p>
                                        @if($order->qrcode->is_used)
                                            <p class="text-sm text-green-600 mt-2">QR sudah digunakan.</p>
                                        @elseif($order->qrcode->is_expired)
                                            <p class="text-sm text-yellow-600 mt-2">QR sudah kadaluarsa.</p>
                                        @else
                                            <p class="text-sm text-green-600 mt-2">QR siap discan oleh penjual.</p>
                                        @endif
                                    </div>
                                @else
                                    <p class="text-sm text-gray-500 dark:text-gray-400">QR belum tersedia untuk pesanan ini.</p>
                                @endif
                            </div>

                            <div class="rounded-3xl border border-gray-200 dark:border-gray-700 p-5 bg-gray-50 dark:bg-gray-900">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Ubah Status</h4>
                                <form action="{{ route('seller.orders.update-status', $order) }}" method="POST" class="space-y-4">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="w-full rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3">
                                        <option value="processing" @selected($order->status === 'processing')>Diproses</option>
                                        <option value="ready" @selected($order->status === 'ready')>Siap Diambil</option>
                                        <option value="completed" @selected($order->status === 'completed')>Selesai</option>
                                        <option value="cancelled" @selected($order->status === 'cancelled')>Dibatalkan</option>
                                    </select>
                                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-full bg-brand-500 text-white hover:bg-brand-600 transition">Perbarui Status</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

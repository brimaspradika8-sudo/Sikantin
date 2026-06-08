<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Admin Dashboard</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Ikhtisar performa, transaksi, dan aktivitas pusat admin.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10 px-6">
        <div class="grid gap-6 xl:grid-cols-3">
            <div class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm">
                <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Total Pengguna</p>
                <p class="mt-4 text-4xl font-extrabold text-gray-900 dark:text-white">{{ number_format($totalUsers) }}</p>
            </div>
            <div class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm">
                <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Total Penjual</p>
                <p class="mt-4 text-4xl font-extrabold text-gray-900 dark:text-white">{{ number_format($totalSellers) }}</p>
                <p class="text-sm text-amber-600 mt-2">{{ number_format($pendingSellers) }} menunggu persetujuan</p>
            </div>
            <div class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm">
                <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Total Produk</p>
                <p class="mt-4 text-4xl font-extrabold text-gray-900 dark:text-white">{{ number_format($totalProducts) }}</p>
            </div>
            <div class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm">
                <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Total Transaksi</p>
                <p class="mt-4 text-4xl font-extrabold text-gray-900 dark:text-white">{{ number_format($totalTransactions) }}</p>
            </div>
            <div class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm xl:col-span-2">
                <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Pendapatan</p>
                <p class="mt-4 text-4xl font-extrabold text-gray-900 dark:text-white">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.6fr_1fr] mt-8">
            <div class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pesanan Terbaru</h3>
                    <a href="{{ route('admin.transactions.index') }}" class="text-brand-500 hover:text-brand-600">Lihat semua</a>
                </div>
                @if($recentOrders->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400">Belum ada transaksi terbaru.</p>
                @else
                    <div class="space-y-4">
                        @foreach($recentOrders as $order)
                            <div class="rounded-3xl border border-gray-200 dark:border-gray-700 p-4 bg-gray-50 dark:bg-gray-900">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $order->order_number }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $order->user->name }} • {{ $order->created_at->format('d M Y H:i') }}</p>
                                    </div>
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $order->statusClass() }}">{{ $order->statusLabel() }}</span>
                                </div>
                                <div class="mt-3 flex items-center justify-between text-sm text-gray-700 dark:text-gray-300">
                                    <span>{{ $order->seller->store_name ?? 'Penjual tidak tersedia' }}</span>
                                    <span class="font-semibold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Aktivitas Terakhir</h3>
                @if($recentAudit->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400">Belum ada aktivitas admin tercatat.</p>
                @else
                    <div class="space-y-4">
                        @foreach($recentAudit as $log)
                            <div class="rounded-3xl border border-gray-200 dark:border-gray-700 p-4 bg-gray-50 dark:bg-gray-900">
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $log->created_at->format('d M Y H:i') }}</p>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $log->description }}</p>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Admin: {{ optional($log->actor)->name ?? '–' }} • IP: {{ $log->ip_address ?? '–' }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>

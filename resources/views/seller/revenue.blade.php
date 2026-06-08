<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">Pendapatan</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Lihat ringkasan penjualan dan riwayat transaksi toko.</p>
            </div>
            <a href="{{ route('seller.orders.index') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-full border border-brand-500 text-brand-600 hover:bg-brand-50 transition">
                <i class="fa-solid fa-receipt"></i> Lihat Pesanan
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid gap-8 xl:grid-cols-[280px_1fr]">
            @include('seller.partials.sidebar')
            <div class="space-y-6">
                <div class="grid gap-6 sm:grid-cols-3">
                    <div class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Pendapatan</p>
                        <p class="mt-4 text-3xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Pendapatan Hari Ini</p>
                        <p class="mt-4 text-3xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Pendapatan Bulan Ini</p>
                        <p class="mt-4 text-3xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Riwayat Transaksi</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Pesanan</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($orders as $order)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition">
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $order->created_at->format('d M Y') }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $order->order_number }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-sm">
                                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : ($order->status === 'paid' || $order->status === 'ready' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                            Belum ada transaksi untuk ditampilkan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

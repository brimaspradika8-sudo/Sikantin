<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Laporan</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Unduh laporan transaksi otomatis untuk periode harian, mingguan, bulanan, atau tahunan.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                @foreach($filters as $key => $label)
                    <a href="{{ route('admin.reports.index', ['period' => $key]) }}" class="inline-flex items-center gap-2 px-4 py-3 rounded-full {{ $period === $key ? 'bg-brand-500 text-white' : 'border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-800' }} transition">{{ $label }}</a>
                @endforeach
                <a href="{{ route('admin.reports.export', ['period' => $period]) }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-green-500 text-white hover:bg-green-600 transition"><i class="fa-solid fa-file-arrow-down"></i> Unduh Laporan</a>
            </div>
        </div>

        <div class="mt-8 grid gap-6 xl:grid-cols-3">
            <div class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm">
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Pesanan</p>
                <p class="mt-4 text-4xl font-extrabold text-gray-900 dark:text-white">{{ $summary['total_orders'] }}</p>
            </div>
            <div class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm">
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Pendapatan</p>
                <p class="mt-4 text-4xl font-extrabold text-gray-900 dark:text-white">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</p>
            </div>
            <div class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm">
                <p class="text-sm text-gray-500 dark:text-gray-400">Periode</p>
                <p class="mt-4 text-4xl font-extrabold text-gray-900 dark:text-white">{{ $periodLabel }}</p>
            </div>
        </div>

        <div class="mt-8 overflow-x-auto rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Pesanan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Pembeli</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Penjual</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Total</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                    @forelse($orders as $order)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $order->order_number }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $order->user->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $order->seller->store_name ?? 'Penjual tidak tersedia' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $order->statusClass() }}">{{ $order->statusLabel() }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">Tidak ada pesanan pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>

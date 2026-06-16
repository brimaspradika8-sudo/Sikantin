<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">Dashboard Penjual</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Ringkasan penjualan, pengelolaan produk, dan status pesanan toko Anda.</p>
            </div>
            <a href="{{ route('seller.products.index') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-brand-500 text-white shadow hover:bg-brand-600 transition">
                <i class="fa-solid fa-boxes-stacked"></i> Kelola Produk
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid gap-8 xl:grid-cols-[280px_1fr]">
            @include('seller.partials.sidebar')
            <div class="space-y-6">
                <div class="grid gap-6 sm:grid-cols-3">
                    <div class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Produk</p>
                        <p class="mt-4 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalProducts }}</p>
                    </div>
                    <div class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Pesanan</p>
                        <p class="mt-4 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalOrders }}</p>
                    </div>
                    <div class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Pendapatan</p>
                        <p class="mt-4 text-3xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="grid gap-6 lg:grid-cols-2">
                    <div class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Grafik Penjualan Harian</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">7 hari terakhir</p>
                            </div>
                            <a href="{{ route('seller.revenue') }}" class="text-brand-500 text-sm hover:text-brand-600">Lihat detail</a>
                        </div>
                        <div class="mt-6 space-y-4">
                            @php $max = max($dailyData) ?: 1; @endphp
                            @foreach($dailyLabels as $index => $label)
                                <div>
                                    <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-2">
                                        <span>{{ $label }}</span>
                                        <span>Rp {{ number_format($dailyData[$index], 0, ',', '.') }}</span>
                                    </div>
                                    <div class="h-3 rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden">
                                        <div class="h-full rounded-full bg-brand-500" style="width: {{ ($dailyData[$index] / $max) * 100 }}%;"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Grafik Bulanan</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">6 bulan terakhir</p>
                            </div>
                            <a href="{{ route('seller.revenue') }}" class="text-brand-500 text-sm hover:text-brand-600">Lihat detail</a>
                        </div>
                        <div class="mt-6 space-y-4">
                            @php $maxMonth = max($monthlyData) ?: 1; @endphp
                            @foreach($monthlyLabels as $index => $label)
                                <div>
                                    <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-2">
                                        <span>{{ $label }}</span>
                                        <span>Rp {{ number_format($monthlyData[$index], 0, ',', '.') }}</span>
                                    </div>
                                    <div class="h-3 rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden">
                                        <div class="h-full rounded-full bg-indigo-500" style="width: {{ ($monthlyData[$index] / $maxMonth) * 100 }}%;"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Produk Terlaris</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Top 5 berdasarkan jumlah terjual.</p>
                        </div>
                        <a href="{{ route('seller.products.index') }}" class="text-brand-500 text-sm hover:text-brand-600">Kelola produk</a>
                    </div>
                    <div class="grid gap-4">
                        @forelse($bestSelling as $item)
                            <div class="rounded-3xl border border-gray-200 dark:border-gray-700 p-5 bg-gray-50 dark:bg-gray-900">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $item->product->name ?? 'Produk tidak ditemukan' }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $item->quantity_sold }} terjual</p>
                                    </div>
                                    <span class="text-brand-600 font-semibold">Rp {{ number_format(($item->product->price ?? 0) * ($item->quantity_sold ?? 0), 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada produk terlaris.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

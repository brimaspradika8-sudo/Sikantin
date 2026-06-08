<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">Riwayat Pembelian</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Lihat semua transaksi, filter tanggal, dan cetak invoice pesananmu.</p>
            </div>
            <a href="{{ route('user.catalog') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-brand-500 text-white shadow hover:bg-brand-600 transition">
                <i class="fa-solid fa-shop"></i> Katalog
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                @if(session('success'))
                    <div class="rounded-3xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-200 p-4 mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('user.orders.index') }}" method="GET" class="grid gap-4 sm:grid-cols-[1fr_auto_auto] items-end mb-6">
                    <label class="block">
                        <span class="text-sm text-gray-600 dark:text-gray-300">Dari tanggal</span>
                        <input type="date" name="from" value="{{ request('from') }}" class="mt-2 w-full rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3" />
                    </label>
                    <label class="block">
                        <span class="text-sm text-gray-600 dark:text-gray-300">Sampai tanggal</span>
                        <input type="date" name="to" value="{{ request('to') }}" class="mt-2 w-full rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3" />
                    </label>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full bg-brand-500 text-white hover:bg-brand-600 transition">
                        <i class="fa-solid fa-filter"></i> Filter
                    </button>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">No</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Pesanan</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Metode</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($orders as $order)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $order->order_number }}<br><span class="text-xs text-gray-500 dark:text-gray-400">{{ $order->created_at->format('d M Y H:i') }}</span></td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $order->statusClass() }}">
                                            {{ $order->statusLabel() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">{{ strtoupper($order->payment_method) }}</td>
                                    <td class="px-6 py-4 text-sm flex gap-2">
                                        <a href="{{ route('user.orders.show', $order) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-brand-500 text-white text-sm hover:bg-brand-600 transition">Lihat</a>
                                        <form action="{{ route('user.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pesanan ini dari riwayat?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-red-500 text-white text-sm hover:bg-red-600 transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                        <i class="fa-solid fa-box-open text-3xl mb-4"></i>
                                        <p class="text-lg font-semibold">Belum ada transaksi.</p>
                                        <p class="mt-2">Mulai pesan produk favoritmu sekarang.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

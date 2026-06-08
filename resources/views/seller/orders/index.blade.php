<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">Pemesanan</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Kelola semua pesanan masuk dan ubah status secara cepat.</p>
            </div>
            <a href="{{ route('seller.scan') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-brand-500 text-white shadow hover:bg-brand-600 transition">
                <i class="fa-solid fa-qrcode"></i> Buka Scanner
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid gap-8 xl:grid-cols-[280px_1fr]">
            @include('seller.partials.sidebar')
            <div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                    <form action="{{ route('seller.orders.index') }}" method="GET" class="grid gap-4 sm:grid-cols-[1fr_auto] items-end mb-6">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <label class="block">
                                <span class="text-sm text-gray-600 dark:text-gray-300">Status</span>
                                <select name="status" class="mt-2 w-full rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3">
                                    <option value="">Semua Status</option>
                                    <option value="pending_payment" @selected(request('status') == 'pending_payment')>Menunggu Pembayaran</option>
                                    <option value="paid" @selected(request('status') == 'paid')>Dibayar</option>
                                    <option value="processing" @selected(request('status') == 'processing')>Diproses</option>
                                    <option value="ready" @selected(request('status') == 'ready')>Siap Diambil</option>
                                    <option value="completed" @selected(request('status') == 'completed')>Selesai</option>
                                    <option value="cancelled" @selected(request('status') == 'cancelled')>Dibatalkan</option>
                                </select>
                            </label>
                            <label class="block">
                                <span class="text-sm text-gray-600 dark:text-gray-300">Dari</span>
                                <input type="date" name="from" value="{{ request('from') }}" class="mt-2 w-full rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3" />
                            </label>
                            <label class="block">
                                <span class="text-sm text-gray-600 dark:text-gray-300">Sampai</span>
                                <input type="date" name="to" value="{{ request('to') }}" class="mt-2 w-full rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3" />
                            </label>
                        </div>
                        <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full bg-brand-500 text-white hover:bg-brand-600 transition">
                            <i class="fa-solid fa-filter"></i> Filter
                        </button>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Pesanan</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Pembeli</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Status</th>
                                    <th class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($orders as $order)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition">
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $order->order_number }}<br><span class="text-xs text-gray-500 dark:text-gray-400">{{ $order->created_at->format('d M Y H:i') }}</span></td>
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $order->user->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-sm">
                                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $order->statusClass() }}">
                                                {{ $order->statusLabel() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <a href="{{ route('seller.orders.show', $order) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-brand-500 text-white text-sm hover:bg-brand-600 transition">Lihat</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                            <i class="fa-solid fa-box-open text-3xl mb-4"></i>
                                            <p class="text-lg font-semibold">Belum ada pesanan.</p>
                                            <p class="mt-2">Pesanan akan muncul setelah pembeli melakukan checkout.</p>
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

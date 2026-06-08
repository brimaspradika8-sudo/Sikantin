<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Transaksi</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Tinjau dan filter semua pesanan marketplace.</p>
            </div>
            <form method="GET" action="{{ route('admin.transactions.index') }}" class="flex flex-wrap gap-3 items-center">
                <select name="status" class="rounded-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3">
                    <option value="">Semua Status</option>
                    <option value="pending_payment" @selected(request('status') == 'pending_payment')>Menunggu Pembayaran</option>
                    <option value="paid" @selected(request('status') == 'paid')>Dibayar</option>
                    <option value="processing" @selected(request('status') == 'processing')>Diproses</option>
                    <option value="ready" @selected(request('status') == 'ready')>Siap Diambil</option>
                    <option value="completed" @selected(request('status') == 'completed')>Selesai</option>
                    <option value="cancelled" @selected(request('status') == 'cancelled')>Dibatalkan</option>
                </select>
                <input type="date" name="from" value="{{ request('from') }}" class="rounded-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3" />
                <input type="date" name="to" value="{{ request('to') }}" class="rounded-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3" />
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-brand-500 text-white hover:bg-brand-600 transition">Filter</button>
            </form>
        </div>

        <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5">
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Transaksi</p>
                <p class="mt-2 text-2xl font-black text-gray-900 dark:text-white">{{ number_format($stats['total']) }}</p>
            </div>
            <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5">
                <p class="text-sm text-gray-500 dark:text-gray-400">Menunggu</p>
                <p class="mt-2 text-2xl font-black text-gray-900 dark:text-white">{{ number_format($stats['pending']) }}</p>
            </div>
            <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5">
                <p class="text-sm text-gray-500 dark:text-gray-400">Berhasil</p>
                <p class="mt-2 text-2xl font-black text-gray-900 dark:text-white">{{ number_format($stats['paid']) }}</p>
            </div>
            <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5">
                <p class="text-sm text-gray-500 dark:text-gray-400">Pendapatan</p>
                <p class="mt-2 text-2xl font-black text-gray-900 dark:text-white">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</p>
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
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Pembayaran</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                    @forelse($orders as $order)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $order->order_number }}<br><span class="text-xs text-gray-500 dark:text-gray-400">{{ $order->created_at->format('d M Y H:i') }}</span></td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $order->user->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $order->seller->store_name ?? 'Penjual tidak ada' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $order->statusClass() }}">{{ $order->statusLabel() }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                <div class="font-semibold text-gray-900 dark:text-white">{{ strtoupper(str_replace('_', ' ', $order->payment_method ?? '-')) }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $order->payment?->statusLabel() ?? 'Menunggu Pembayaran' }}</div>
                                @if($order->payment?->payment_proof)
                                    <a href="{{ asset('storage/'.$order->payment->payment_proof) }}" target="_blank" class="text-xs text-brand-600 hover:underline">Lihat bukti</a>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-right">
                                @if($order->payment && $order->payment->payment_status === 'pending' && $order->payment->payment_proof)
                                    <div class="flex justify-end gap-2">
                                        <form action="{{ route('admin.transactions.approve-payment', $order) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button class="rounded-full bg-green-500 px-3 py-2 text-xs font-semibold text-white hover:bg-green-600">Terima</button>
                                        </form>
                                        <form action="{{ route('admin.transactions.reject-payment', $order) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button class="rounded-full bg-red-500 px-3 py-2 text-xs font-semibold text-white hover:bg-red-600">Tolak</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">Tidak ada transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $orders->links() }}</div>
    </div>
</x-admin-layout>

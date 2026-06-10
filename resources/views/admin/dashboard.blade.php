<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white">Admin Dashboard</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Ikhtisar performa, transaksi, dan aktivitas pusat admin.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10 px-6">
        <div class="max-w-7xl mx-auto space-y-6">
            <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
                <article class="rounded-[2rem] bg-white dark:bg-slate-900 border border-slate-200/70 dark:border-slate-700 p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20">
                    <div class="flex items-start justify-between">
                        <div class="space-y-2">
                            <p class="text-sm uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400 font-semibold">Total Pengguna</p>
                            <p class="text-4xl font-extrabold text-slate-900 dark:text-white">{{ number_format($totalUsers) }}</p>
                        </div>
                        <span class="inline-flex h-12 w-12 items-center justify-center rounded-3xl bg-amber-100 text-amber-600 shadow-sm"> <i class="fa-solid fa-user text-lg"></i> </span>
                    </div>
                </article>

                <article class="rounded-[2rem] bg-white dark:bg-slate-900 border border-slate-200/70 dark:border-slate-700 p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20">
                    <div class="flex items-start justify-between">
                        <div class="space-y-2">
                            <p class="text-sm uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400 font-semibold">Total Penjual</p>
                            <p class="text-4xl font-extrabold text-slate-900 dark:text-white">{{ number_format($totalSellers) }}</p>
                        </div>
                        <span class="inline-flex h-12 w-12 items-center justify-center rounded-3xl bg-sky-100 text-sky-600 shadow-sm"> <i class="fa-solid fa-shop text-lg"></i> </span>
                    </div>
                    <p class="mt-4 text-sm text-amber-600">{{ number_format($pendingSellers) }} menunggu persetujuan</p>
                </article>

                <article class="rounded-[2rem] bg-white dark:bg-slate-900 border border-slate-200/70 dark:border-slate-700 p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20">
                    <div class="flex items-start justify-between">
                        <div class="space-y-2">
                            <p class="text-sm uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400 font-semibold">Total Produk</p>
                            <p class="text-4xl font-extrabold text-slate-900 dark:text-white">{{ number_format($totalProducts) }}</p>
                        </div>
                        <span class="inline-flex h-12 w-12 items-center justify-center rounded-3xl bg-emerald-100 text-emerald-600 shadow-sm"> <i class="fa-solid fa-box-open text-lg"></i> </span>
                    </div>
                </article>

                <article class="rounded-[2rem] bg-white dark:bg-slate-900 border border-slate-200/70 dark:border-slate-700 p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20">
                    <div class="flex items-start justify-between">
                        <div class="space-y-2">
                            <p class="text-sm uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400 font-semibold">Total Transaksi</p>
                            <p class="text-4xl font-extrabold text-slate-900 dark:text-white">{{ number_format($totalTransactions) }}</p>
                        </div>
                        <span class="inline-flex h-12 w-12 items-center justify-center rounded-3xl bg-fuchsia-100 text-fuchsia-600 shadow-sm"> <i class="fa-solid fa-receipt text-lg"></i> </span>
                    </div>
                </article>
            </div>

            <article class="rounded-[2rem] bg-gradient-to-r from-amber-500 to-orange-500 text-white p-8 shadow-2xl shadow-amber-500/20">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm uppercase tracking-[0.25em] font-semibold opacity-90">Pendapatan</p>
                        <p class="mt-3 text-4xl font-black">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                        <p class="mt-2 text-sm opacity-90">Pendapatan total semua transaksi</p>
                    </div>
                    <div class="inline-flex h-16 w-16 items-center justify-center rounded-3xl bg-white/15 text-white text-2xl shadow-lg shadow-white/10">
                        <i class="fa-solid fa-coins"></i>
                    </div>
                </div>
            </article>

            <div class="grid gap-6 xl:grid-cols-[1.8fr_1fr]">
                <section class="rounded-[2rem] bg-white dark:bg-slate-900 border border-slate-200/70 dark:border-slate-700 p-6 shadow-xl shadow-slate-200/40 dark:shadow-black/20">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <p class="text-sm uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400 font-semibold">Pesanan Terbaru</p>
                            <h3 class="text-2xl font-bold text-slate-900 dark:text-white">Ringkasan pesanan</h3>
                        </div>
                        <a href="{{ route('admin.transactions.index') }}" class="inline-flex items-center gap-2 text-slate-700 dark:text-slate-200 font-medium hover:text-slate-900 dark:hover:text-white transition">
                            <span>Lihat semua</span>
                            <i class="fa-solid fa-arrow-right-long"></i>
                        </a>
                    </div>
                    @if($recentOrders->isEmpty())
                        <p class="text-slate-500 dark:text-slate-400">Belum ada transaksi terbaru.</p>
                    @else
                        <div class="space-y-4">
                            @foreach($recentOrders as $order)
                                <div class="rounded-[1.5rem] border border-slate-200/80 dark:border-slate-700 p-4 bg-slate-50 dark:bg-slate-950">
                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                        <div>
                                            <p class="text-base font-semibold text-slate-900 dark:text-white">{{ $order->order_number }}</p>
                                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ $order->user->name }} • {{ $order->created_at->format('d M Y H:i') }}</p>
                                        </div>
                                        <span class="inline-flex items-center rounded-full bg-slate-100 dark:bg-slate-800 px-3 py-1 text-xs font-semibold text-slate-700 dark:text-slate-200">
                                            {{ $order->statusLabel() }}
                                        </span>
                                    </div>
                                    <div class="mt-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-sm text-slate-600 dark:text-slate-400">
                                        <span>{{ $order->seller->store_name ?? 'Penjual tidak tersedia' }}</span>
                                        <span class="font-semibold text-slate-900 dark:text-white">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </section>

                <aside class="rounded-[2rem] bg-white dark:bg-slate-900 border border-slate-200/70 dark:border-slate-700 p-6 shadow-xl shadow-slate-200/40 dark:shadow-black/20">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <p class="text-sm uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400 font-semibold">Aktivitas</p>
                            <h3 class="text-2xl font-bold text-slate-900 dark:text-white">Log terbaru</h3>
                        </div>
                    </div>
                    @if($recentAudit->isEmpty())
                        <p class="text-slate-500 dark:text-slate-400">Belum ada aktivitas admin tercatat.</p>
                    @else
                        <div class="space-y-4">
                            @foreach($recentAudit as $log)
                                <div class="rounded-[1.5rem] border border-slate-200/80 dark:border-slate-700 p-4 bg-slate-50 dark:bg-slate-950">
                                    <p class="text-xs uppercase tracking-[0.25em] text-slate-400 dark:text-slate-600">{{ $log->created_at->format('d M Y H:i') }}</p>
                                    <p class="mt-2 text-sm text-slate-900 dark:text-slate-100">{{ $log->description }}</p>
                                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Admin: {{ optional($log->actor)->name ?? '–' }} • IP: {{ $log->ip_address ?? '–' }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </aside>
            </div>
        </div>
    </div>
</x-admin-layout>

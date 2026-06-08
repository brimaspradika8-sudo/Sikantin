@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-800 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Pesanan Saya</h1>
                <p class="text-slate-600 dark:text-slate-400 mt-2">Kelola dan pantau semua pesanan Anda</p>
            </div>
            <a href="{{ route('user.orders.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Pesan Makanan
            </a>
        </div>

        @if($orders->count() > 0)
            <!-- Orders Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($orders as $order)
                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md hover:shadow-lg transition overflow-hidden">
                        <!-- Order Header -->
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4 text-white">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm opacity-90">Pesanan #{{ $order->order_number }}</p>
                                    <p class="text-2xl font-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                </div>
                                <span class="px-3 py-1 {{ $order->statusClass() }} rounded-full text-xs font-semibold">
                                    {{ $order->statusLabel() }}
                                </span>
                            </div>
                        </div>

                        <!-- Order Details -->
                        <div class="px-6 py-4 space-y-3">
                            <!-- Items -->
                            <div>
                                <p class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Item Pesanan</p>
                                <div class="space-y-1">
                                    @foreach($order->items as $item)
                                        <div class="flex justify-between text-sm text-slate-600 dark:text-slate-400">
                                            <span>{{ optional($item->menuItem)->name ?? 'Produk tidak tersedia' }}</span>
                                            <span class="font-medium">×{{ $item->quantity }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Seller -->
                            <div class="border-t border-slate-200 dark:border-slate-700 pt-3">
                                <p class="text-xs text-slate-500 dark:text-slate-500">Penjual</p>
                                <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $order->seller->name }}</p>
                            </div>

                            <!-- Date -->
                            <div class="text-xs text-slate-500 dark:text-slate-500">
                                {{ $order->created_at->format('d M Y H:i') }}
                            </div>

                            <!-- Payment Status -->
                            <div class="bg-slate-100 dark:bg-slate-700 px-3 py-2 rounded">
                                <p class="text-xs text-slate-600 dark:text-slate-400">Status Pembayaran</p>
                                <p class="text-sm font-semibold text-slate-900 dark:text-white">
                                    {{ $order->payment->statusLabel() ?? 'Pending' }}
                                </p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-700 border-t border-slate-200 dark:border-slate-600 flex gap-2">
                            <a href="{{ route('user.orders.show', $order) }}" class="flex-1 text-center py-2 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 rounded font-medium text-sm hover:bg-blue-200 dark:hover:bg-blue-800 transition">
                                Detail
                            </a>
                            <a href="{{ route('user.orders.track', $order) }}" class="flex-1 text-center py-2 bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-300 rounded font-medium text-sm hover:bg-green-200 dark:hover:bg-green-800 transition">
                                Lacak
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow text-center py-16">
                <svg class="w-16 h-16 mx-auto text-slate-400 dark:text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <p class="text-slate-600 dark:text-slate-400 text-lg mb-4">Anda belum memiliki pesanan</p>
                <a href="{{ route('user.orders.create') }}" class="inline-block px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                    Buat Pesanan Pertama
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Lacak Pesanan - ' . $order->order_number)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-800 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <a href="{{ route('user.orders.index') }}" class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-700 mb-6">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke Pesanan
        </a>

        <!-- Order Header Card -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-8 mb-8">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Pesanan #{{ $order->order_number }}</h1>
                    <p class="text-slate-600 dark:text-slate-400 mt-2">{{ $order->created_at->format('d M Y H:i') }}</p>
                </div>
                <span class="px-4 py-2 {{ $order->statusClass() }} rounded-lg text-sm font-bold">
                    {{ $order->statusLabel() }}
                </span>
            </div>

            <!-- Order Summary -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Amount -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-lg p-4">
                    <p class="text-sm text-slate-600 dark:text-slate-400">Total Pesanan</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                </div>

                <!-- Seller Info -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-lg p-4">
                    <p class="text-sm text-slate-600 dark:text-slate-400">Penjual</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $order->seller->name }}</p>
                </div>

                <!-- Payment Status -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-lg p-4">
                    <p class="text-sm text-slate-600 dark:text-slate-400">Status Pembayaran</p>
                    <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $order->payment->statusLabel() ?? 'Pending' }}</p>
                </div>
            </div>
        </div>

        <!-- Progress Timeline -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-8">Progres Pesanan</h2>

            <div class="relative">
                <!-- Timeline -->
                <div class="space-y-8">
                    <!-- Step 1: Pesanan Dibuat -->
                    <div class="flex items-start">
                        <div class="flex flex-col items-center">
                            <div class="flex items-center justify-center h-10 w-10 rounded-full bg-green-500 text-white">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            @if($order->status !== 'pending_payment')
                                <div class="w-1 h-12 bg-green-500 mt-2"></div>
                            @endif
                        </div>
                        <div class="ml-6">
                            <p class="text-lg font-semibold text-slate-900 dark:text-white">Pesanan Dibuat</p>
                            <p class="text-sm text-slate-600 dark:text-slate-400">{{ $order->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>

                    <!-- Step 2: Pembayaran -->
                    <div class="flex items-start">
                        <div class="flex flex-col items-center">
                            <div class="flex items-center justify-center h-10 w-10 rounded-full {{ in_array($order->status, ['processing', 'ready', 'completed']) ? 'bg-green-500 text-white' : 'bg-slate-300 text-slate-600' }}">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    @if(in_array($order->status, ['processing', 'ready', 'completed']))
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    @else
                                        <text x="5" y="15" font-size="12">💳</text>
                                    @endif
                                </svg>
                            </div>
                            @if(in_array($order->status, ['ready', 'completed']))
                                <div class="w-1 h-12 bg-green-500 mt-2"></div>
                            @endif
                        </div>
                        <div class="ml-6">
                            <p class="text-lg font-semibold text-slate-900 dark:text-white">Pembayaran Diverifikasi</p>
                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                @if($order->payment->verified_at)
                                    {{ $order->payment->verified_at->format('d M Y H:i') }}
                                @else
                                    Menunggu verifikasi...
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Step 3: Diproses -->
                    <div class="flex items-start">
                        <div class="flex flex-col items-center">
                            <div class="flex items-center justify-center h-10 w-10 rounded-full {{ in_array($order->status, ['processing', 'ready', 'completed']) ? 'bg-green-500 text-white' : 'bg-slate-300 text-slate-600' }}">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    @if(in_array($order->status, ['processing', 'ready', 'completed']))
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    @else
                                        <text x="5" y="15" font-size="12">👨‍🍳</text>
                                    @endif
                                </svg>
                            </div>
                            @if(in_array($order->status, ['ready', 'completed']))
                                <div class="w-1 h-12 bg-green-500 mt-2"></div>
                            @endif
                        </div>
                        <div class="ml-6">
                            <p class="text-lg font-semibold text-slate-900 dark:text-white">Sedang Diproses</p>
                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                @if($order->status === 'processing')
                                    Pesanan sedang disiapkan...
                                @elseif(in_array($order->status, ['ready', 'completed']))
                                    Sudah diproses
                                @else
                                    Menunggu konfirmasi pembayaran
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Step 4: Siap Diambil -->
                    <div class="flex items-start">
                        <div class="flex flex-col items-center">
                            <div class="flex items-center justify-center h-10 w-10 rounded-full {{ in_array($order->status, ['ready', 'completed']) ? 'bg-green-500 text-white' : 'bg-slate-300 text-slate-600' }}">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    @if(in_array($order->status, ['ready', 'completed']))
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    @else
                                        <text x="5" y="15" font-size="12">🍽️</text>
                                    @endif
                                </svg>
                            </div>
                            @if($order->status === 'completed')
                                <div class="w-1 h-12 bg-green-500 mt-2"></div>
                            @endif
                        </div>
                        <div class="ml-6">
                            <p class="text-lg font-semibold text-slate-900 dark:text-white">Siap Diambil</p>
                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                @if($order->status === 'ready')
                                    Pesanan siap diambil! Segera ambil makanan Anda.
                                @elseif($order->status === 'completed')
                                    Pesanan sudah diambil
                                @else
                                    Menunggu pesanan disiapkan
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Step 5: Selesai -->
                    <div class="flex items-start">
                        <div class="flex flex-col items-center">
                            <div class="flex items-center justify-center h-10 w-10 rounded-full {{ $order->status === 'completed' ? 'bg-green-500 text-white' : 'bg-slate-300 text-slate-600' }}">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    @if($order->status === 'completed')
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    @else
                                        <text x="5" y="15" font-size="12">✅</text>
                                    @endif
                                </svg>
                            </div>
                        </div>
                        <div class="ml-6">
                            <p class="text-lg font-semibold text-slate-900 dark:text-white">Selesai</p>
                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                @if($order->status === 'completed')
                                    Terima kasih! Pesanan selesai.
                                @else
                                    Menunggu pengambilan pesanan
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="mt-12">
                <div class="flex justify-between mb-2">
                    <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Progres Keseluruhan</span>
                    <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                        @php
                            $progress = match($order->status) {
                                'pending_payment' => 20,
                                'processing' => 40,
                                'ready' => 80,
                                'completed' => 100,
                                default => 0,
                            };
                        @endphp
                        {{ $progress }}%
                    </span>
                </div>
                <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                    <div class="bg-gradient-to-r from-green-400 to-blue-500 h-2 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
                </div>
            </div>
        </div>

        <!-- Order Items Detail -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-8">
            <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Detail Item</h2>

            <div class="space-y-4">
                        @foreach($order->items as $item)
                    <div class="flex justify-between items-center p-4 bg-slate-50 dark:bg-slate-700 rounded-lg">
                        <div>
                            <p class="font-semibold text-slate-900 dark:text-white">{{ optional($item->menuItem)->name ?? 'Produk tidak tersedia' }}</p>
                            <p class="text-sm text-slate-600 dark:text-slate-400">{{ optional($item->menuItem)->description ?? '' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-slate-900 dark:text-white">{{ $item->quantity }}× Rp {{ number_format($item->unit_price ?? ($item->price ?? optional($item->menuItem)->price ?? 0), 0, ',', '.') }}</p>
                            <p class="text-sm text-slate-600 dark:text-slate-400">Rp {{ number_format($item->subtotal ?? ($item->quantity * ($item->unit_price ?? ($item->price ?? optional($item->menuItem)->price ?? 0))), 0, ',', '.') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Order Total -->
            <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-700">
                <div class="flex justify-between items-center text-lg font-bold text-slate-900 dark:text-white">
                    <span>Total</span>
                    <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Payment Info -->
        @if($order->payment)
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-8 mt-8">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Informasi Pembayaran</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Metode Pembayaran</p>
                        <p class="text-lg font-semibold text-slate-900 dark:text-white">
                            @if($order->payment->payment_method === 'cash_on_pickup')
                                Bayar Saat Ambil
                            @elseif($order->payment->payment_method === 'bank_transfer')
                                Transfer Bank
                            @else
                                {{ $order->payment->payment_method }}
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Status Pembayaran</p>
                        <p class="text-lg font-semibold text-slate-900 dark:text-white">{{ $order->payment->statusLabel() }}</p>
                    </div>
                </div>

                <!-- Bank Transfer Details -->
                @if($order->payment->payment_method === 'bank_transfer' && $order->payment->payment_status === 'pending')
                    <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border-l-4 border-blue-500">
                        <p class="text-sm font-semibold text-blue-900 dark:text-blue-300 mb-2">Lakukan Transfer ke Rekening Berikut:</p>
                        <div class="space-y-2 text-sm text-blue-800 dark:text-blue-200">
                            <p><strong>Bank:</strong> {{ $order->payment->bank_name }}</p>
                            <p><strong>Nomor Rekening:</strong> {{ $order->payment->account_number }}</p>
                            <p><strong>Atas Nama:</strong> {{ $order->payment->account_holder }}</p>
                            <p><strong>Jumlah:</strong> Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        </div>
                        <a href="{{ route('user.payments.upload-proof', $order) }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded font-medium hover:bg-blue-700 transition">
                            Upload Bukti Transfer
                        </a>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Auto-refresh every 5 seconds for real-time updates
    setInterval(function() {
        location.reload();
    }, 5000);
</script>
@endpush
@endsection

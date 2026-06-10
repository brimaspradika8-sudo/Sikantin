<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">Checkout</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Periksa pesanan, pilih metode bayar, lalu buat invoice.</p>
            </div>
            <a href="{{ route('user.orders.index') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-lg border border-brand-500 text-brand-600 hover:bg-brand-50 transition">
                <i class="fa-solid fa-clock-rotate-left"></i> Riwayat
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('user.checkout.process') }}" class="grid grid-cols-1 lg:grid-cols-[1.1fr_0.9fr] gap-8">
                @csrf

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Ringkasan Pesanan</h3>

                    @if($items->isEmpty())
                        <div class="text-center py-24 text-gray-500 dark:text-gray-400">
                            <i class="fa-solid fa-box-open text-5xl mb-6"></i>
                            <p class="text-xl font-semibold">Keranjang masih kosong</p>
                            <p class="mt-2">Tambahkan produk terlebih dahulu sebelum checkout.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($items as $item)
                                <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                                    <div class="flex items-center justify-between gap-4">
                                        <div class="flex items-center gap-4 min-w-0">
                                            <div class="h-16 w-16 rounded-lg bg-gray-100 dark:bg-gray-900 overflow-hidden shrink-0">
                                                @if($item->product->image)
                                                    <img src="{{ asset('storage/'.$item->product->image) }}" alt="{{ $item->product->name }}" class="h-full w-full object-cover">
                                                @else
                                                    <div class="h-full w-full grid place-items-center text-gray-400"><i class="fa-solid fa-bowl-food"></i></div>
                                                @endif
                                            </div>
                                            <div class="min-w-0">
                                                <p class="font-semibold text-gray-900 dark:text-white truncate">{{ $item->product->name }}</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $item->quantity }} x Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                        <p class="font-bold text-gray-900 dark:text-white whitespace-nowrap">Rp {{ number_format($item->quantity * $item->product->price, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="promo_code" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Kode Promo</label>
                                <input id="promo_code" name="promo_code" value="{{ old('promo_code') }}" placeholder="SIKANTIN10" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Diskon otomatis diproses saat pesanan dibuat.</p>
                            </div>
                            <div>
                                <label for="customer_note" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Catatan untuk Penjual</label>
                                <textarea id="customer_note" name="customer_note" rows="3" placeholder="Jangan pedas, tambah sambal, kurangi gula" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">{{ old('customer_note') }}</textarea>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 h-fit">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Metode Pembayaran</h3>

                    @if($items->isNotEmpty())
                        <div class="space-y-3">
                            @foreach([
                                'midtrans' => ['Pembayaran Online Midtrans', 'QRIS, GoPay, ShopeePay, Virtual Account, dan kartu.'],
                                'pay_at_canteen' => ['Bayar di Kantin', 'Selesaikan pembayaran langsung di kasir kantin.'],
                            ] as $value => [$title, $description])
                                <label class="flex gap-3 rounded-lg border border-gray-200 dark:border-gray-700 p-4 cursor-pointer hover:border-brand-500 transition">
                                    <input type="radio" name="payment_method" value="{{ $value }}" class="mt-1 text-brand-500" @checked(old('payment_method', 'midtrans') === $value)>
                                    <span>
                                        <span class="block font-semibold text-gray-900 dark:text-white">{{ $title }}</span>
                                        <span class="block text-sm text-gray-500 dark:text-gray-400">{{ $description }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>

                        <div class="mt-6 rounded-lg bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 p-5 space-y-3">
                            <div class="flex justify-between text-sm"><span class="text-gray-500 dark:text-gray-400">Subtotal</span><span class="font-semibold text-gray-900 dark:text-white">Rp {{ number_format($subtotal, 0, ',', '.') }}</span></div>
                            <div class="flex justify-between text-sm"><span class="text-gray-500 dark:text-gray-400">Diskon</span><span class="font-semibold text-gray-900 dark:text-white">Diproses otomatis</span></div>
                            <div class="flex justify-between text-sm"><span class="text-gray-500 dark:text-gray-400">Pajak</span><span class="font-semibold text-gray-900 dark:text-white">Rp 0</span></div>
                            <div class="flex justify-between text-sm"><span class="text-gray-500 dark:text-gray-400">Biaya layanan online</span><span class="font-semibold text-gray-900 dark:text-white">Rp 1.000</span></div>
                            <div class="pt-3 border-t border-gray-200 dark:border-gray-700 flex justify-between">
                                <span class="font-semibold text-gray-900 dark:text-white">Total mulai dari</span>
                                <span class="text-2xl font-black text-gray-900 dark:text-white">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <button type="submit" class="mt-6 w-full inline-flex items-center justify-center gap-2 px-6 py-4 rounded-lg bg-brand-500 text-white font-semibold shadow hover:bg-brand-600 transition">
                            <i class="fa-solid fa-receipt"></i> Buat Pesanan
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">Detail Produk</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Lihat deskripsi lengkap dan tambahkan produk ke keranjang.</p>
            </div>
            <a href="{{ route('user.cart') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-brand-500 text-white shadow hover:bg-brand-600 transition">
                <i class="fa-solid fa-cart-shopping"></i> Keranjang
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-[380px_1fr] gap-8 p-6">
                    <div class="space-y-6">
                        <div class="overflow-hidden rounded-3xl bg-gray-100 dark:bg-gray-700 h-96">
                            @if($product->image)
                                <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="h-full grid place-items-center text-brand-500"><i class="fa-solid fa-bowl-food text-6xl"></i></div>
                            @endif
                        </div>
                        <div class="grid gap-4">
                            <div class="rounded-3xl border border-gray-200 dark:border-gray-700 p-5 bg-white dark:bg-gray-900">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Penjual</p>
                                <p class="mt-2 font-semibold text-gray-900 dark:text-white">{{ $product->seller->store_name ?? 'Kantin Sekolah' }}</p>
                            </div>
                            <div class="rounded-3xl border border-gray-200 dark:border-gray-700 p-5 bg-white dark:bg-gray-900">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Kategori</p>
                                <p class="mt-2 font-semibold text-gray-900 dark:text-white">{{ $product->category->name ?? 'Umum' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <h3 class="text-3xl font-black text-gray-900 dark:text-white">{{ $product->name }}</h3>
                            <p class="mt-2 text-brand-600 text-2xl font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            
                        </div>
                        <div class="rounded-3xl border border-gray-200 dark:border-gray-700 p-6 bg-gray-50 dark:bg-gray-900">
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Deskripsi Produk</h4>
                            <p class="text-gray-600 dark:text-gray-300 leading-relaxed">{{ $product->description ?? 'Deskripsi belum tersedia.' }}</p>
                        </div>
                            <form action="{{ route('user.cart.add') }}" method="POST" class="grid gap-4">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div class="grid grid-cols-1 sm:grid-cols-[1fr_160px] gap-3 items-center">
                                    <div>
                                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">Jumlah</label>
                                        <input type="number" name="quantity" value="1" min="1" class="mt-2 w-full rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3" />
                                    </div>
                                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-4 rounded-full bg-brand-500 text-white font-semibold shadow hover:bg-brand-600 transition">
                                        <i class="fa-solid fa-cart-plus"></i> Tambah Keranjang
                                    </button>
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Bayar langsung dengan QRIS, Dana, OVO, GoPay, ShopeePay, atau pilih bayar offline.</p>
                            </form>
                        @if($related->isNotEmpty())
                            <div class="rounded-3xl border border-gray-200 dark:border-gray-700 p-6 bg-gray-50 dark:bg-gray-900">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-lg font-bold text-gray-900 dark:text-white">Produk Terkait</h4>
                                    <a href="{{ route('user.catalog') }}" class="text-brand-500 hover:text-brand-600">Lihat semua</a>
                                </div>
                                <div class="grid grid-cols-1 gap-4">
                                    @foreach($related as $item)
                                        <a href="{{ route('user.product.show', $item) }}" class="block rounded-2xl border border-gray-200 dark:border-gray-700 p-4 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                                            <p class="font-semibold text-gray-900 dark:text-white">{{ $item->name }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

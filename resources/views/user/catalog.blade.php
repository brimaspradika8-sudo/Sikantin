<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-3xl text-gray-900 dark:text-gray-100 tracking-tight">Katalog Produk</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-2">Cari produk, filter kategori, harga, dan urutkan sesuai kebutuhan.</p>
            </div>
            <a href="{{ route('user.cart') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-brand-500 text-white shadow-md hover:bg-brand-600 transition">
                <i class="fa-solid fa-cart-shopping"></i> Keranjang
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl shadow-sm mb-8">
                    <i class="fa-solid fa-circle-check mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 xl:grid-cols-[320px_1fr] gap-8">
                <aside class="space-y-6">
                    <form action="{{ route('user.catalog') }}" method="GET" class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Filter</h3>
                        
                        <label class="block mb-4">
                            <span class="text-sm text-gray-600 dark:text-gray-300">Pencarian</span>
                            <div class="mt-2 relative flex">
                                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari produk..." class="flex-1 rounded-l-2xl border border-r-0 border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3 text-sm" />
                                <button type="submit" class="px-4 py-3 rounded-r-2xl bg-brand-500 text-white hover:bg-brand-600 transition border border-brand-500 shrink-0">
                                    <i class="fa-solid fa-magnifying-glass text-sm"></i>
                                </button>
                            </div>
                        </label>
                    </form>
                </aside>
                <div>
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Menampilkan {{ $products->count() }} produk</p>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Semua Produk</h3>
                        </div>
                        <a href="{{ route('user.checkout') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-full border border-brand-500 text-brand-600 hover:bg-brand-50 transition">
                            <i class="fa-solid fa-money-check-dollar"></i> Lanjut Pembayaran
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @forelse($products as $product)
                            <div class="bg-white dark:bg-gray-800 rounded-3xl overflow-hidden shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-lg transition">
                                <a href="{{ route('user.product.show', $product) }}" class="block h-52 overflow-hidden bg-gray-100 dark:bg-gray-700">
                                    @if($product->image)
                                        <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition duration-500 hover:scale-105">
                                    @else
                                        <div class="h-full grid place-items-center text-brand-500"><i class="fa-solid fa-utensils text-4xl"></i></div>
                                    @endif
                                </a>
                                <div class="p-5 flex flex-col gap-4">
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.2em] text-brand-600">{{ $product->category->name ?? 'Umum' }}</p>
                                        <h4 class="mt-3 text-lg font-bold text-gray-900 dark:text-white">{{ $product->name }}</h4>
                                        <p class="mt-2 text-gray-500 dark:text-gray-400 text-sm">{{ Illuminate\Support\Str::limit($product->description, 90) }}</p>
                                    </div>
                                    <div class="flex items-center justify-between gap-4">
                                        <div>
                                            <p class="text-xl font-black text-gray-900 dark:text-white">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="flex flex-col gap-2">
                                            <a href="{{ route('user.product.show', $product) }}" class="inline-flex items-center justify-center px-4 py-2 rounded-full border border-gray-200 dark:border-gray-700 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-900 transition">Detail</a>
                                            <form action="{{ route('user.cart.add') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 rounded-full bg-brand-500 text-white text-sm font-semibold hover:bg-brand-600 transition">Tambah</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full rounded-3xl border border-dashed border-gray-300 dark:border-gray-700 p-12 text-center text-gray-500 dark:text-gray-400">
                                <i class="fa-solid fa-box-open text-4xl mb-4"></i>
                                <p class="text-lg font-semibold">Tidak ada produk yang cocok.</p>
                                <p class="mt-2">Ubah filter atau cari kata lain.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

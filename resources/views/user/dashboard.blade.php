<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-3xl text-gray-900 dark:text-gray-100 tracking-tight">Selamat datang, {{ Auth::user()->name }}!</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-2">Pesan menu kantin, cek promo, dan ambil pesanan dengan QR dalam satu aplikasi.</p>
            </div>
            <a href="{{ route('user.catalog') }}" class="inline-flex items-center px-6 py-3 rounded-full bg-brand-500 text-white shadow-lg hover:bg-brand-600 transition">
                <i class="fa-solid fa-bolt mr-2"></i> Lihat Menu
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-gradient-to-br from-brand-500 to-brand-600 text-white rounded-3xl p-8 shadow-xl relative overflow-hidden">
                    <div class="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_top_right,_theme(colors.brand.300),_transparent_40%)]"></div>
                    <span class="inline-flex items-center px-4 py-2 rounded-full bg-white/15 text-sm font-bold uppercase tracking-[0.25em] mb-4">Promo Hari Ini</span>
                    <h3 class="text-4xl sm:text-5xl font-black mb-4">Diskon 20% untuk menu paket keluarga</h3>
                    <p class="max-w-xl text-sm sm:text-base text-white/90 mb-6">Cek katalog sekarang dan nikmati potongan spesial di kantin setiap hari kerja.</p>
                    <div class="flex flex-wrap gap-3">
                        <div class="bg-white/15 border border-white/30 rounded-3xl px-4 py-3">
                            <p class="text-sm uppercase tracking-widest text-white/75">E-Wallet</p>
                            <p class="text-lg font-semibold">QRIS, OVO, GoPay</p>
                        </div>

                    </div>
                </div>
                <div class="space-y-4">
                    @foreach($announcements as $announcement)
                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl p-6 shadow-sm">
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-brand-100 text-brand-700 text-xs font-semibold uppercase tracking-[0.2em]">{{ $announcement['tag'] }}</span>
                            <h3 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">{{ $announcement['title'] }}</h3>
                            <p class="mt-2 text-gray-500 dark:text-gray-400">{{ $announcement['subtitle'] }}</p>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <p class="text-sm text-brand-500 font-semibold uppercase tracking-[0.2em]">Dashboard</p>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Produk Pilihan</h3>
                        </div>
                        <a href="{{ route('user.catalog') }}" class="text-brand-500 font-semibold hover:text-brand-600">Lihat semua</a>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($popular as $product)
                            <a href="{{ route('user.product.show', $product) }}" class="block rounded-3xl overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-xl transition">
                                <div class="h-44 bg-gray-100 dark:bg-gray-700 overflow-hidden">
                                    @if($product->image)
                                        <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="h-full grid place-items-center text-brand-400"><i class="fa-solid fa-bowl-rice text-4xl"></i></div>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $product->category->name ?? 'Tidak ada kategori' }}</p>
                                    <h4 class="text-lg font-bold text-gray-900 dark:text-white">{{ $product->name }}</h4>
                                    <p class="mt-2 text-brand-600 font-semibold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Kategori Produk</h3>
                    <div class="space-y-3">
                        @foreach($categories as $category)
                            <div class="flex items-center justify-between rounded-2xl border border-gray-200 dark:border-gray-700 p-4">
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $category->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $category->products_count }} produk</p>
                                </div>
                                <span class="text-brand-500 font-bold">&rsaquo;</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Testimoni</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Feedback siswa tentang pesan antar kantin.</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach($testimonials as $testimonial)
                        <div class="rounded-3xl border border-gray-200 dark:border-gray-700 p-5 bg-gray-50 dark:bg-gray-900">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">“{{ $testimonial['comment'] }}”</p>
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $testimonial['name'] }}</p>
                                    <p class="text-xs uppercase text-gray-400">Rating {{ $testimonial['rating'] }}/5</p>
                                </div>
                                <div class="text-yellow-500">@foreach(range(1,5) as $star) <i class="fa-solid fa-star text-xs"></i> @endforeach</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            @if($favoriteProducts->isNotEmpty())
                <section class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Produk Favorit Kamu</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Hasil rekomendasi dari pesanan terakhir.</p>
                        </div>
                        <a href="{{ route('user.catalog') }}" class="text-brand-500 font-semibold hover:text-brand-600">Jelajahi lagi</a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach($favoriteProducts as $product)
                            <a href="{{ route('user.product.show', $product) }}" class="block rounded-3xl border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-xl transition">
                                <div class="h-40 bg-gray-100 dark:bg-gray-700 overflow-hidden">
                                    @if($product->image)
                                        <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="h-full grid place-items-center text-brand-400"><i class="fa-solid fa-utensils text-4xl"></i></div>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $product->category->name ?? 'Lainnya' }}</p>
                                    <h4 class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $product->name }}</h4>
                                    <p class="mt-2 text-brand-600 font-semibold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </div>
</x-app-layout>

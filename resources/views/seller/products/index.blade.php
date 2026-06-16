<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">Kelola Produk</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Tambahkan, perbarui, dan hapus produk untuk toko Anda.</p>
            </div>
            <a href="{{ route('seller.products.create') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-brand-500 text-white shadow hover:bg-brand-600 transition">
                <i class="fa-solid fa-plus"></i> Tambah Produk
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid gap-8 xl:grid-cols-[280px_1fr]">
            @include('seller.partials.sidebar')
            <div>
                @if(session('success'))
                    <div class="mb-6 rounded-3xl border border-green-200 bg-green-50 p-4 text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm overflow-x-auto">
                    <table class="min-w-full text-left divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Nama Produk</th>
                                <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Harga</th>
                                <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Status</th>
                                <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($products as $product)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition">
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $product->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($product->is_open)
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-green-100 text-green-800">
                                                <i class="fa-solid fa-check text-xs"></i> Dijual
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-red-100 text-red-800">
                                                <i class="fa-solid fa-xmark text-xs"></i> Habis
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm space-x-3">
                                        <a href="{{ route('seller.products.edit', $product) }}" class="text-brand-600 hover:text-brand-800">Edit</a>
                                        <form action="{{ route('seller.products.toggle-status', $product) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-amber-600 hover:text-amber-800">
                                                {{ $product->is_open ? 'Tandai Habis' : 'Jual Lagi' }}
                                            </button>
                                        </form>
                                        <form action="{{ route('seller.products.destroy', $product) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Hapus produk ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">Belum ada produk. Tambahkan produk baru untuk mulai berjualan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

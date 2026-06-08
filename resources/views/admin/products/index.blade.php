<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Produk</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Daftar semua produk marketplace kantin.</p>
            </div>
            <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-wrap gap-3 items-center">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..." class="rounded-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3" />
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-brand-500 text-white hover:bg-brand-600 transition">Cari</button>
            </form>
        </div>

        <div class="mt-8 overflow-x-auto rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Produk</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Penjual</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Harga</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Stok</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                    @forelse($products as $product)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $product->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $product->seller->store_name ?? 'Penjual tidak tersedia' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $product->stock }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="#" class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-brand-500 text-brand-600 text-xs font-semibold hover:bg-brand-50 transition">Lihat</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">Tidak ada produk ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $products->links() }}</div>
    </div>
</x-admin-layout>

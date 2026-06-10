<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white">Produk</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Daftar produk marketplace kantin dengan kontrol mudah.</p>
            </div>
            <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-col sm:flex-row sm:items-center gap-3 w-full sm:w-auto">
                <div class="relative w-full sm:w-[320px]">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..." class="w-full rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 px-5 py-3 shadow-sm" />
                    <i class="fa-solid fa-magnifying-glass absolute right-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                </div>
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-full bg-amber-500 px-5 py-3 text-white font-semibold shadow-lg shadow-amber-500/20 hover:bg-amber-600 transition">Cari</button>
            </form>
        </div>

        <div class="mt-8 rounded-[2rem] bg-white dark:bg-slate-900 border border-slate-200/70 dark:border-slate-700 shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left border-separate border-spacing-0">
                    <thead class="bg-slate-50 dark:bg-slate-950">
                        <tr>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Produk</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Penjual</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Harga</th>
                            <th class="px-6 py-4"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200/70 dark:divide-slate-700">
                        @forelse($products as $product)
                            <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-800">
                                <td class="px-6 py-5 text-sm text-slate-900 dark:text-slate-100">{{ $product->name }}</td>
                                <td class="px-6 py-5 text-sm text-slate-600 dark:text-slate-300">{{ $product->seller->store_name ?? 'Penjual tidak tersedia' }}</td>
                                <td class="px-6 py-5 text-sm text-slate-900 dark:text-slate-100">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td class="px-6 py-5 text-right">
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Hapus produk ini?')" class="inline-flex items-center gap-2 rounded-full bg-red-500 px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.12em] text-white hover:bg-red-600 transition">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center text-slate-500 dark:text-slate-400">Tidak ada produk ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">{{ $products->links() }}</div>
    </div>
</x-admin-layout>

<div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
    <div class="grid gap-6">
        <label class="block">
            <span class="text-sm text-gray-600 dark:text-gray-300">Nama Produk</span>
            <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" class="mt-2 w-full rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3" required />
        </label>

        <label class="block">
            <span class="text-sm text-gray-600 dark:text-gray-300">Kategori</span>
            <select name="category_id" class="mt-2 w-full rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3" required>
                <option value="">Pilih kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? '') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </label>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <label class="block">
                <span class="text-sm text-gray-600 dark:text-gray-300">Harga</span>
                <input type="number" name="price" value="{{ old('price', $product->price ?? '') }}" min="0" step="0.01" class="mt-2 w-full rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3" required />
            </label>
            <label class="block">
                <span class="text-sm text-gray-600 dark:text-gray-300">Stok</span>
                <input type="number" name="stock" value="{{ old('stock', $product->stock ?? '') }}" min="0" class="mt-2 w-full rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3" required />
            </label>
        </div>

        <label class="block">
            <span class="text-sm text-gray-600 dark:text-gray-300">Deskripsi</span>
            <textarea name="description" rows="5" class="mt-2 w-full rounded-3xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3">{{ old('description', $product->description ?? '') }}</textarea>
        </label>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full bg-brand-500 text-white font-semibold shadow hover:bg-brand-600 transition">
                {{ $buttonText ?? 'Simpan Produk' }}
            </button>
            <a href="{{ route('seller.products.index') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-100 transition">Batal</a>
        </div>
    </div>
</div>

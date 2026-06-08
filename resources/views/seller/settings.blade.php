<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">Pengaturan</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Perbarui informasi toko dan kontak seller.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid gap-8 xl:grid-cols-[280px_1fr]">
            @include('seller.partials.sidebar')
            <div>
                @if(session('success'))
                    <div class="rounded-3xl bg-green-50 border border-green-200 text-green-700 p-4 mb-6">{{ session('success') }}</div>
                @endif

                <div class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm">
                    <form action="{{ route('seller.settings.update') }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PATCH')
                        <label class="block">
                            <span class="text-sm text-gray-600 dark:text-gray-300">Nama Penjual</span>
                            <input name="name" value="{{ old('name', $user->name) }}" class="mt-2 w-full rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3" required />
                        </label>
                        <label class="block">
                            <span class="text-sm text-gray-600 dark:text-gray-300">Nama Toko</span>
                            <input name="store_name" value="{{ old('store_name', $user->store_name) }}" class="mt-2 w-full rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3" />
                        </label>
                        <label class="block">
                            <span class="text-sm text-gray-600 dark:text-gray-300">Telepon</span>
                            <input name="phone" value="{{ old('phone', $user->phone) }}" class="mt-2 w-full rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3" />
                        </label>
                        <label class="block">
                            <span class="text-sm text-gray-600 dark:text-gray-300">Alamat Toko</span>
                            <textarea name="address" rows="4" class="mt-2 w-full rounded-3xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3">{{ old('address', $user->address) }}</textarea>
                        </label>
                        <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full bg-brand-500 text-white hover:bg-brand-600 transition">Simpan Pengaturan</button>
                    </form>

                    <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Status Toko</h3>
                        <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 p-4 mb-4">
                            @if($user->is_closed)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <i class="fa-solid fa-lock text-red-500 text-lg"></i>
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-white">Toko Sedang Tutup</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Pembeli tidak dapat melihat atau membeli produk Anda</p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <i class="fa-solid fa-unlock text-green-500 text-lg"></i>
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-white">Toko Sedang Buka</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Pembeli dapat melihat dan membeli produk Anda</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <form action="{{ route('seller.settings.toggle-closed') }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full {{ $user->is_closed ? 'bg-green-500 hover:bg-green-600' : 'bg-red-500 hover:bg-red-600' }} text-white transition">
                                <i class="fa-solid {{ $user->is_closed ? 'fa-unlock' : 'fa-lock' }}"></i>
                                {{ $user->is_closed ? 'Buka Toko' : 'Tutup Toko' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

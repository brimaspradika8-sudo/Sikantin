<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Permintaan Akun Penjual') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <div class="space-y-4">
                    <p class="text-gray-600 dark:text-gray-300">Isi data bisnis Anda agar admin dapat meninjau permintaan akun penjual. Setelah disetujui, akun penjual Anda akan aktif.</p>
                    <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 p-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Catatan: Anda akan menggunakan email dan password yang sama untuk login setelah permintaan disetujui.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('profile.seller-request.submit') }}" class="mt-6 space-y-6">
                    @csrf

                    <div>
                        <label for="store_name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nama Usaha</label>
                        <input id="store_name" name="store_name" type="text" value="{{ old('store_name', $user->store_name) }}" class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:border-brand-500 focus:ring-brand-500" required />
                        @error('store_name')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nomor Telepon</label>
                        <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}" class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:border-brand-500 focus:ring-brand-500" required />
                        @error('phone')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Alamat</label>
                        <textarea id="address" name="address" rows="4" class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:border-brand-500 focus:ring-brand-500" required>{{ old('address', $user->address) }}</textarea>
                        @error('address')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 px-4 py-3 rounded-full border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-900 transition">
                            <i class="fa-solid fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-brand-500 text-white hover:bg-brand-600 transition">
                            <i class="fa-solid fa-paper-plane"></i> Kirim Permintaan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

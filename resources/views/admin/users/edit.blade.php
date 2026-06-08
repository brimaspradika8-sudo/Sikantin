<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Edit Pengguna</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Perbarui informasi akun pengguna.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10 px-6">
        <div class="max-w-3xl mx-auto rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm p-6">
            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
                @csrf
                @method('PATCH')

                <label class="block">
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Nama</span>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="mt-2 w-full rounded-3xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3" required />
                </label>

                <label class="block">
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Email</span>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="mt-2 w-full rounded-3xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3" required />
                </label>

                <label class="block">
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Telepon</span>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="mt-2 w-full rounded-3xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3" />
                </label>

                <label class="block">
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Status Akun</span>
                    <select name="status" class="mt-2 w-full rounded-3xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3" required>
                        <option value="active" @selected(old('status', $user->status) === 'active')>Aktif</option>
                        <option value="inactive" @selected(old('status', $user->status) === 'inactive')>Nonaktif</option>
                        <option value="pending" @selected(old('status', $user->status) === 'pending')>Menunggu</option>
                        <option value="rejected" @selected(old('status', $user->status) === 'rejected')>Ditolak</option>
                    </select>
                </label>

                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full bg-brand-500 text-white text-sm font-semibold hover:bg-brand-600 transition">Simpan Perubahan</button>
                    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full border border-gray-300 dark:border-gray-700 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-900 transition">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>

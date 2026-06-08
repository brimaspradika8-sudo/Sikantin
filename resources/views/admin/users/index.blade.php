<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Kelola User</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Lihat, edit, hapus, dan nonaktifkan akun pengguna sekolah.</p>
            </div>
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-3 items-center">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email" class="rounded-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3" />
                <select name="status" class="rounded-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3">
                    <option value="">Semua Status</option>
                    <option value="active" @selected(request('status') == 'active')>Aktif</option>
                    <option value="inactive" @selected(request('status') == 'inactive')>Nonaktif</option>
                    <option value="pending" @selected(request('status') == 'pending')>Menunggu</option>
                    <option value="rejected" @selected(request('status') == 'rejected')>Ditolak</option>
                </select>
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-brand-500 text-white hover:bg-brand-600 transition">Filter</button>
            </form>
        </div>

        <div class="mt-8 overflow-x-auto rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Nama</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Telepon</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Status</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                    @forelse($users as $user)
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $user->phone ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : ($user->status === 'inactive' ? 'bg-gray-100 text-gray-800' : ($user->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')) }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-sm space-x-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-brand-500 text-white text-xs font-semibold hover:bg-brand-600 transition">Edit</a>
                                <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-gray-300 dark:border-gray-600 text-xs font-semibold text-gray-700 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700 transition">{{ $user->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                                </form>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus pengguna ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-red-500 text-white text-xs font-semibold hover:bg-red-600 transition">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">Tidak ada pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $users->links() }}</div>
    </div>
</x-admin-layout>

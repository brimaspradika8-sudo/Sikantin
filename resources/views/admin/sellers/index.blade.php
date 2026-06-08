<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Kelola Penjual</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Tinjau pendaftaran penjual, setujui atau tolak akun mereka.</p>
            </div>
            <form method="GET" action="{{ route('admin.sellers.index') }}" class="flex flex-wrap gap-3 items-center">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau toko" class="rounded-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3" />
                <select name="status" class="rounded-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3">
                    <option value="">Semua Status</option>
                    <option value="active" @selected(request('status') == 'active')>Aktif</option>
                    <option value="pending" @selected(request('status') == 'pending')>Menunggu Persetujuan</option>
                    <option value="rejected" @selected(request('status') == 'rejected')>Ditolak</option>
                </select>
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-brand-500 text-white hover:bg-brand-600 transition">Filter</button>
            </form>
        </div>

        <div class="mt-8 overflow-x-auto rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Nama Toko</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Penjual</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Status</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                    @forelse($sellers as $seller)
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $seller->store_name ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $seller->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $seller->email }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $seller->status === 'active' ? 'bg-green-100 text-green-800' : ($seller->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($seller->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-sm space-x-2">
                                @if($seller->status === 'pending')
                                    <form action="{{ route('admin.sellers.approve', $seller) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-green-500 text-white text-xs font-semibold hover:bg-green-600 transition">Setujui</button>
                                    </form>
                                    <form action="{{ route('admin.sellers.reject', $seller) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-red-500 text-white text-xs font-semibold hover:bg-red-600 transition">Tolak</button>
                                    </form>
                                @else
                                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-gray-300 dark:border-gray-700 text-xs text-gray-700 dark:text-gray-200">Tidak tersedia</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">Tidak ada penjual.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $sellers->links() }}</div>
    </div>
</x-admin-layout>

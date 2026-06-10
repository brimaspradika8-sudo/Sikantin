<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white">Kelola Penjual</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Tinjau pendaftaran penjual, approve/reject dan hapus akun dengan cepat.</p>
            </div>
            <form method="GET" action="{{ route('admin.sellers.index') }}" class="flex flex-col sm:flex-row sm:items-center gap-3 w-full sm:w-auto">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau toko" class="w-full sm:w-[320px] rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 px-5 py-3 shadow-sm" />
                <select name="status" class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 px-5 py-3 shadow-sm">
                    <option value="">Semua Status</option>
                    <option value="active" @selected(request('status') == 'active')>Aktif</option>
                    <option value="pending" @selected(request('status') == 'pending')>Menunggu Persetujuan</option>
                    <option value="rejected" @selected(request('status') == 'rejected')>Ditolak</option>
                </select>
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-full bg-amber-500 px-5 py-3 text-white font-semibold shadow-lg shadow-amber-500/20 hover:bg-amber-600 transition">Filter</button>
            </form>
        </div>

        <div class="mt-8 rounded-[2rem] bg-white dark:bg-slate-900 border border-slate-200/70 dark:border-slate-700 shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left border-separate border-spacing-0">
                    <thead class="bg-slate-50 dark:bg-slate-950">
                        <tr>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Nama Toko</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Penjual</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Email</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Status</th>
                            <th class="px-6 py-4"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200/70 dark:divide-slate-700">
                        @forelse($sellers as $seller)
                            <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-800">
                                <td class="px-6 py-5 text-sm font-medium text-slate-900 dark:text-slate-100">{{ $seller->store_name ?? '-' }}</td>
                                <td class="px-6 py-5 text-sm text-slate-600 dark:text-slate-300">{{ $seller->name }}</td>
                                <td class="px-6 py-5 text-sm text-slate-600 dark:text-slate-300">{{ $seller->email }}</td>
                                <td class="px-6 py-5 text-sm">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $seller->status === 'active' ? 'bg-emerald-100 text-emerald-700' : ($seller->status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
                                        {{ ucfirst($seller->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-right space-x-2">
                                    @if($seller->status === 'pending')
                                        <form action="{{ route('admin.sellers.approve', $seller) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-emerald-500 px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.12em] text-white hover:bg-emerald-600 transition">Setujui</button>
                                        </form>
                                        <form action="{{ route('admin.sellers.reject', $seller) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-orange-500 px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.12em] text-white hover:bg-orange-600 transition">Tolak</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.sellers.destroy', $seller) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Hapus akun penjual ini? Semua produk terkait juga akan dihapus.')" class="inline-flex items-center gap-2 rounded-full bg-red-500 px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.12em] text-white hover:bg-red-600 transition">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center text-slate-500 dark:text-slate-400">Tidak ada penjual.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">{{ $sellers->links() }}</div>
    </div>
</x-admin-layout>

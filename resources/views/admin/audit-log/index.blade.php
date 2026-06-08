<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Audit Log</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Catatan aktivitas admin dan perubahan sistem penting.</p>
            </div>
            <form method="GET" action="{{ route('admin.audit-log.index') }}" class="flex flex-wrap gap-3 items-center">
                <input type="text" name="actor" value="{{ request('actor') }}" placeholder="Cari admin..." class="rounded-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3" />
                <input type="date" name="from" value="{{ request('from') }}" class="rounded-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3" />
                <input type="date" name="to" value="{{ request('to') }}" class="rounded-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-3" />
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-brand-500 text-white hover:bg-brand-600 transition">Cari</button>
            </form>
        </div>

        <div class="mt-8 overflow-x-auto rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Waktu</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Admin</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Tindakan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Deskripsi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                    @forelse($logs as $log)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $log->created_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ optional($log->actor)->name ?? '–' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ str_replace('_', ' ', $log->action) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $log->description }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $log->ip_address ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">Belum ada aktivitas tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $logs->links() }}</div>
    </div>
</x-admin-layout>

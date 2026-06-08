<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Pengaturan Admin</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Kelola preferensi utama admin dan konfigurasi umum.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10 px-6">
        <div class="max-w-3xl mx-auto rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Konfigurasi Aplikasi</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Pengaturan admin ini merupakan halaman placeholder untuk pengaturan sistem dan branding kantin.</p>

            <div class="mt-6 rounded-3xl border border-dashed border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 p-6">
                <p class="text-sm text-gray-600 dark:text-gray-300">Pada fase berikutnya, Anda dapat menambahkan opsi pengaturan global seperti:</p>
                <ul class="mt-4 list-disc list-inside space-y-2 text-gray-700 dark:text-gray-300">
                    <li>Informasi kontak admin</li>
                    <li>Jam operasional kantin</li>
                    <li>Template email otomatis</li>
                    <li>Pengaturan notifikasi</li>
                </ul>
            </div>
        </div>
    </div>
</x-admin-layout>

<aside class="space-y-6">
    <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
        <p class="text-sm text-gray-500 dark:text-gray-400">Seller Panel</p>
        <h2 class="mt-2 text-xl font-bold text-gray-900 dark:text-white">{{ Auth::user()->store_name ?? Auth::user()->name }}</h2>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
    </div>

</aside>

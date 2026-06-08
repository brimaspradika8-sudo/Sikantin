<aside class="space-y-6">
    <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
        <p class="text-sm text-gray-500 dark:text-gray-400">Seller Panel</p>
        <h2 class="mt-2 text-xl font-bold text-gray-900 dark:text-white">{{ Auth::user()->store_name ?? Auth::user()->name }}</h2>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
    </div>

    <nav class="space-y-3 bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
        @php
            $routes = [
                ['route' => 'seller.dashboard', 'icon' => 'fa-chart-line', 'label' => 'Dashboard'],
                ['route' => 'seller.products.index', 'icon' => 'fa-boxes-stacked', 'label' => 'Kelola Produk'],
                ['route' => 'seller.orders.index', 'icon' => 'fa-receipt', 'label' => 'Pemesanan'],
                ['route' => 'seller.revenue', 'icon' => 'fa-wallet', 'label' => 'Pendapatan'],
                ['route' => 'seller.scan', 'icon' => 'fa-qrcode', 'label' => 'Scan QR'],
                ['route' => 'seller.settings', 'icon' => 'fa-gear', 'label' => 'Pengaturan'],
            ];
        @endphp

        @foreach($routes as $item)
            <a href="{{ route($item['route']) }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold {{ request()->routeIs($item['route']) ? 'bg-brand-500 text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-900' }} transition">
                <i class="fa-solid {{ $item['icon'] }} w-5"></i>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>
</aside>

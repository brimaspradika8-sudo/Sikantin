<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SiKantin') }} - Admin</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts & Tailwind CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>
    <body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white flex flex-col h-full shadow-lg">
            <div class="h-16 flex items-center px-6 bg-gray-900 font-bold text-xl text-yellow-500 border-b border-gray-700">
                <i class="fa-solid fa-store mr-2"></i> SiKantin Admin
            </div>
            
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg bg-gray-700 text-yellow-500 font-medium">
                    <i class="fa-solid fa-gauge mr-3 w-5"></i> Dashboard
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-700 text-gray-300 hover:text-white transition">
                    <i class="fa-solid fa-users mr-3 w-5"></i> Kelola User
                </a>
                <a href="{{ route('admin.sellers.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-700 text-gray-300 hover:text-white transition">
                    <i class="fa-solid fa-shop mr-3 w-5"></i> Kelola Penjual
                </a>
                <a href="{{ route('admin.products.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-700 text-gray-300 hover:text-white transition">
                    <i class="fa-solid fa-boxes-stacked mr-3 w-5"></i> Produk
                </a>
                <a href="{{ route('admin.transactions.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-700 text-gray-300 hover:text-white transition">
                    <i class="fa-solid fa-list-check mr-3 w-5"></i> Pemesanan
                </a>
                <a href="{{ route('admin.reports.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-700 text-gray-300 hover:text-white transition">
                    <i class="fa-solid fa-chart-line mr-3 w-5"></i> Pendapatan
                </a>
                <a href="{{ route('admin.audit-log.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-700 text-gray-300 hover:text-white transition">
                    <i class="fa-solid fa-clock-rotate-left mr-3 w-5"></i> Audit Log
                </a>
                <a href="{{ route('admin.settings') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-700 text-gray-300 hover:text-white transition">
                    <i class="fa-solid fa-gear mr-3 w-5"></i> Pengaturan
                </a>
            </nav>

            <div class="p-4 border-t border-gray-700">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-4 py-3 rounded-lg bg-red-600 hover:bg-red-700 text-white transition">
                        <i class="fa-solid fa-right-from-bracket mr-3 w-5"></i> Log Out
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col h-full overflow-hidden">
            <!-- Topbar -->
            <header class="h-16 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between px-6 shadow-sm">
                @if (isset($header))
                    <div class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        {{ $header }}
                    </div>
                @else
                    <div></div>
                @endif

                <div class="flex items-center text-gray-600 dark:text-gray-300">
                    <span class="mr-4"><i class="fa-solid fa-user-shield text-yellow-500 mr-2"></i> {{ Auth::user()->name }}</span>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 dark:bg-gray-900">
                {{ $slot }}
            </main>
        </div>

    </body>
</html>

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
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            html, body {
                max-width: 100%;
                overflow-x: hidden;
            }
            .nav-item {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                padding: 0.75rem 1rem;
                border-radius: 0.75rem;
                color: #94a3b8;
                text-decoration: none;
                transition: all 0.3s ease-out;
                white-space: nowrap;
            }
            .nav-item:hover {
                color: white;
                background: linear-gradient(90deg, rgba(245,158,11,0.1), rgba(249,115,22,0.1));
            }
            .nav-item.active {
                background: linear-gradient(90deg, #f59e0b, #ea580c);
                color: white;
                box-shadow: 0 20px 45px rgba(249,115,22,0.18);
            }
            .nav-item i {
                width: 1.25rem;
                min-width: 1.25rem;
                text-align: center;
            }
        </style>
    </head>
    <body x-data="{ sidebarOpen: false }" class="font-sans antialiased bg-gray-50 dark:bg-gray-950 min-h-screen overflow-x-hidden lg:overflow-hidden">
        <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false" class="fixed inset-0 z-30 bg-slate-950/60 lg:hidden" style="display: none;"></div>
        
        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 left-0 z-40 w-72 max-w-[85vw] -translate-x-full bg-gradient-to-b from-slate-900 to-slate-800 text-white flex flex-col shadow-2xl transition-transform duration-300 lg:static lg:z-auto lg:h-screen lg:w-64 lg:max-w-none lg:translate-x-0"
            :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }"
        >
            <!-- Logo -->
            <div class="h-20 flex items-center px-6 bg-gradient-to-r from-amber-500 to-amber-600 shadow-lg">
                <div class="flex flex-1 items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center backdrop-blur-sm">
                        <i class="fa-solid fa-store text-white text-lg"></i>
                    </div>
                    <div>
                        <p class="font-bold text-white text-sm">SiKantin</p>
                        <p class="text-xs text-amber-100">Admin Panel</p>
                    </div>
                </div>
                <button type="button" @click="sidebarOpen = false" class="inline-flex h-10 w-10 items-center justify-center rounded-lg text-white/90 hover:bg-white/15 lg:hidden">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <!-- Navigation -->
            <nav class="flex-1 px-4 py-8 space-y-2 overflow-y-auto">
                <p class="text-xs uppercase tracking-widest text-gray-500 font-semibold px-4 mb-4">Menu Utama</p>
                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-gauge"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
                
                <p class="text-xs uppercase tracking-widest text-gray-500 font-semibold px-4 mt-6 mb-4">Manajemen</p>
                <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users"></i>
                    <span class="font-medium">Kelola User</span>
                </a>
                <a href="{{ route('admin.sellers.index') }}" class="nav-item {{ request()->routeIs('admin.sellers.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-shop"></i>
                    <span class="font-medium">Kelola Penjual</span>
                </a>
                <a href="{{ route('admin.products.index') }}" class="nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-boxes-stacked"></i>
                    <span class="font-medium">Produk</span>
                </a>
                
                <p class="text-xs uppercase tracking-widest text-gray-500 font-semibold px-4 mt-6 mb-4">Laporan</p>
                <a href="{{ route('admin.transactions.index') }}" class="nav-item {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-receipt"></i>
                    <span class="font-medium">Transaksi</span>
                </a>
                <a href="{{ route('admin.audit-log.index') }}" class="nav-item {{ request()->routeIs('admin.audit-log.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    <span class="font-medium">Audit Log</span>
                </a>
            </nav>

            <!-- Logout Button -->
            <div class="p-4 border-t border-slate-700">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-red-600/20 text-red-400 hover:bg-red-600/40 hover:text-red-300 transition-all duration-300">
                        <i class="fa-solid fa-right-from-bracket"></i> Log Out
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex min-h-screen w-full flex-1 flex-col lg:h-screen lg:overflow-hidden">
            <!-- Topbar -->
            <header class="min-h-16 lg:h-20 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700 flex items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8 shadow-sm backdrop-blur-sm bg-white/80 dark:bg-slate-800/80">
                <div class="flex min-w-0 items-center gap-3">
                    <button type="button" @click="sidebarOpen = true" class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-gray-200 bg-white text-gray-700 shadow-sm hover:bg-gray-50 dark:border-slate-700 dark:bg-slate-900 dark:text-gray-200 dark:hover:bg-slate-800 lg:hidden">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    @if (isset($header))
                        <div class="truncate font-bold text-lg text-gray-900 dark:text-gray-100 leading-tight sm:text-2xl">
                            {{ $header }}
                        </div>
                    @else
                        <div></div>
                    @endif
                </div>

                <div class="flex shrink-0 items-center gap-3 text-gray-600 dark:text-gray-400">
                    <div class="flex items-center gap-3 rounded-lg bg-gray-100 px-2 py-2 dark:bg-slate-700 sm:px-4">
                        <div class="h-8 w-8 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center">
                            <i class="fa-solid fa-user-shield text-white text-sm"></i>
                        </div>
                        <span class="hidden max-w-40 truncate font-medium text-sm text-gray-900 dark:text-gray-200 sm:inline">{{ Auth::user()->name }}</span>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 dark:bg-gray-950">
                <div class="px-4 py-6 sm:px-6 lg:p-8">
                    {{ $slot }}
                </div>
            </main>
        </div>

    </body>
</html>

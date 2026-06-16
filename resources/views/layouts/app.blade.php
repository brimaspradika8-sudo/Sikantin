<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SiKantin') }}</title>

        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        <!-- Scripts & Tailwind CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        fontFamily: { sans: ['Outfit', 'sans-serif'] },
                        colors: {
                            brand: { 400: '#FFD54F', 500: '#FFC107', 600: '#FFB300', 700: '#FFA000' }
                        }
                    }
                }
            }

            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }

            function toggleTheme() {
                const isDark = document.documentElement.classList.toggle('dark');
                localStorage.theme = isDark ? 'dark' : 'light';
                const icon = document.getElementById('themeToggleIcon');
                const responsiveIcon = document.getElementById('themeToggleIconResponsive');
                [icon, responsiveIcon].forEach((btn) => {
                    if (!btn) return;
                    btn.classList.toggle('fa-sun', isDark);
                    btn.classList.toggle('fa-moon', !isDark);
                });
            }
        </script>
        <style>
            html, body {
                max-width: 100%;
                overflow-x: hidden;
            }
            body { font-family: 'Outfit', sans-serif; }
            img, video, canvas, svg {
                max-width: 100%;
            }
            table {
                max-width: 100%;
            }
        </style>
    </head>
    <body class="font-sans antialiased text-gray-900 bg-gray-50 dark:bg-gray-900">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if(isset($header) || View::hasSection('header'))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-5 px-4 sm:py-6 sm:px-6 lg:px-8">
                        @isset($header)
                            {{ $header }}
                        @else
                            @yield('header')
                        @endisset
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                @isset($slot)
                    {{ $slot }}
                @endisset

                @yield('content')
            </main>
        </div>
    </body>
    <script>
        window.Laravel = {
            userId: {{ Auth::id() ?? 'null' }},
            pusherKey: '{{ env("PUSHER_APP_KEY") }}',
            pusherCluster: '{{ env("PUSHER_APP_CLUSTER") }}'
        };
    </script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>
    <script>
        (function(){
            async function initNotifications(){
                const userId = window.Laravel.userId;
                if (!userId) return;

                // fetch initial notifications
                const res = await fetch('/notifications');
                if (res.ok) {
                    const data = await res.json();
                    const badge = document.getElementById('notificationBadge');
                    if (data.unread && data.unread > 0) {
                        badge.style.display = 'inline-block';
                        badge.textContent = data.unread;
                    }
                    // cache initial notifications count
                    window.__initialNotifications = data.notifications || [];
                }

                if (window.Laravel.pusherKey) {
                    Pusher.logToConsole = false;
                    const echo = new window.Echo({
                        broadcaster: 'pusher',
                        key: window.Laravel.pusherKey,
                        cluster: window.Laravel.pusherCluster || undefined,
                        forceTLS: true,
                    });

                    echo.private('App.Models.User.' + userId)
                        .notification(function(notification){
                            const badge = document.getElementById('notificationBadge');
                            if (badge.style.display === 'none') badge.style.display = 'inline-block';
                            badge.textContent = (parseInt(badge.textContent || '0') + 1).toString();
                            // prepend new item if menu open
                            if (document.getElementById('notificationMenu') && document.getElementById('notificationMenu').classList.contains('block')) {
                                renderNotifications([notification, ...(window.__initialNotifications || [])]);
                            }
                        });
                }
                // attach bell handler
                const bell = document.getElementById('notificationBell');
                if (bell) {
                    bell.addEventListener('click', async (e) => {
                        const menu = document.getElementById('notificationMenu');
                        if (!menu) return;
                        const isHidden = menu.classList.contains('hidden');
                        if (isHidden) {
                            await fetchNotifications();
                            menu.classList.remove('hidden');
                            menu.classList.add('block');
                        } else {
                            menu.classList.remove('block');
                            menu.classList.add('hidden');
                        }
                    });
                }

                async function fetchNotifications() {
                    const res = await fetch('/notifications');
                    if (!res.ok) return;
                    const data = await res.json();
                    window.__initialNotifications = data.notifications || [];
                    renderNotifications(window.__initialNotifications);
                    const badge = document.getElementById('notificationBadge');
                    if (data.unread && data.unread > 0) {
                        badge.style.display = 'inline-block';
                        badge.textContent = data.unread;
                    } else {
                        badge.style.display = 'none';
                    }
                }

                function renderNotifications(items) {
                    const list = document.getElementById('notificationList');
                    if (!list) return;
                    list.innerHTML = '';
                    if (!items || items.length === 0) {
                        list.innerHTML = '<div class="p-4 text-sm text-gray-500 dark:text-gray-400">Belum ada notifikasi.</div>';
                        return;
                    }
                    items.forEach(n => {
                        const id = n.id || n.notification?.id || n._id || '';
                        const data = n.data || n.notification?.data || n;
                        const when = n.created_at ?? (n.notification?.created_at ?? '');
                        const html = `
                            <div data-notification-item="${id}" class="p-3 hover:bg-gray-50 dark:hover:bg-gray-900 flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="text-sm text-gray-800 dark:text-gray-100">${data.order_number ? 'Pesanan ' + data.order_number : (data.message ?? 'Notifikasi baru')}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">${when}</div>
                                </div>
                                <div class="ms-3 text-right">
                                    <button data-id="${id}" class="mark-read text-xs text-blue-600 dark:text-blue-400">Tandai dibaca</button>
                                </div>
                            </div>
                        `;
                        list.insertAdjacentHTML('beforeend', html);
                    });

                    // attach handlers
                    list.querySelectorAll('.mark-read').forEach(btn => {
                        btn.addEventListener('click', async (e) => {
                            const id = btn.getAttribute('data-id');
                            if (!id) return;
                            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                            const res = await fetch('/notifications/' + encodeURIComponent(id) + '/read', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                                body: JSON.stringify({})
                            });
                            if (res.ok) {
                                const item = btn.closest('[data-notification-item]');
                                if (item) item.remove();
                                // decrement badge
                                const badge = document.getElementById('notificationBadge');
                                const current = parseInt(badge.textContent || '0');
                                const next = Math.max(0, current - 1);
                                if (next === 0) badge.style.display = 'none';
                                badge.textContent = next.toString();
                            }
                        });
                    });
                }
            }
            initNotifications();
        })();
    </script>
</html>

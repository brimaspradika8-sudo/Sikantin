<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SiKantin') }} - Masuk</title>

        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        <!-- Tailwind & AOS -->
        <script src="https://cdn.tailwindcss.com"></script>
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
        </script>
        <style>
            html, body {
                max-width: 100%;
                overflow-x: hidden;
            }
            .glass { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(16px); border: 1px solid rgba(255,255,255,0.4); }
            .dark .glass { background: rgba(31, 41, 55, 0.9); border: 1px solid rgba(255,255,255,0.1); }
            body { font-family: 'Outfit', sans-serif; }
            .input-field { @apply border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-brand-500 focus:ring-brand-500 rounded-xl shadow-sm transition-all; }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased relative min-h-screen flex items-center justify-center overflow-x-hidden px-4 py-6 sm:px-6">
        
        <!-- Elegant Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-brand-400 via-brand-500 to-orange-500 z-0"></div>
        
        <!-- Decorative Shapes -->
        <div class="absolute top-0 left-0 h-72 w-72 bg-white opacity-10 rounded-full mix-blend-overlay filter blur-3xl transform -translate-x-1/2 -translate-y-1/2 sm:h-96 sm:w-96"></div>
        <div class="absolute bottom-0 right-0 h-80 w-80 bg-orange-600 opacity-20 rounded-full mix-blend-overlay filter blur-3xl transform translate-x-1/3 translate-y-1/3 sm:h-[40rem] sm:w-[40rem]"></div>
        
        <div class="relative z-10 w-full max-w-md px-5 py-8 glass shadow-2xl rounded-3xl transform transition-all duration-500 sm:px-10 sm:py-12 sm:rounded-[2rem] sm:hover:scale-[1.02]" data-aos="zoom-in" data-aos-duration="1000">
            <div class="flex flex-col items-center justify-center mb-8">
                <a href="/" class="group flex flex-col items-center">
                    <div class="w-16 h-16 bg-gradient-to-tr from-brand-600 to-brand-400 rounded-2xl flex items-center justify-center shadow-lg mb-4 group-hover:rotate-12 transition-transform duration-300 sm:h-20 sm:w-20">
                        <i class="fa-solid fa-burger text-3xl text-white sm:text-4xl"></i>
                    </div>
                    <h1 class="text-3xl font-black text-gray-800 dark:text-white tracking-tight sm:text-4xl">Si<span class="text-brand-600">Kantin</span></h1>
                    <p class="text-sm font-medium text-gray-500 mt-1 uppercase tracking-widest">Sekolah Masa Kini</p>
                </a>
            </div>

                @isset($slot)
                    {{ $slot }}
                @endisset

                @yield('content')
        </div>

        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <script>AOS.init();</script>
    </body>
</html>

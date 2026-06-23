<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'E-Reporting') }} — Login</title>

        <!-- Google Fonts: Inter & JetBrains Mono -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden bg-slate-900">
            <!-- Background Image -->
            <div class="absolute inset-0 z-0">
                <img src="{{ asset('images/login-bg-3.jpg') }}" alt="PTBA Kertapati Port" class="w-full h-full object-cover object-center opacity-90">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/70 via-slate-900/30 to-slate-900/50"></div>
            </div>

            <!-- Logo -->
            <div class="relative z-10 flex flex-col items-center scroll-animate">
                <a href="/" class="group flex items-center gap-3 sm:gap-4 transition-transform hover:scale-105 duration-300 cursor-pointer">
                    <img src="{{ asset('images/logo.png') }}" alt="Application Logo" class="h-10 sm:h-14 w-auto drop-shadow-lg">
                    <div class="flex flex-col">
                        <span class="font-extrabold text-2xl sm:text-3xl tracking-widest text-slate-800 leading-none drop-shadow-lg">E-REPORTING</span>
                        <span class="font-mono text-[10px] sm:text-xs font-bold tracking-[0.3em] text-amber-400 mt-0.5 sm:mt-1 drop-shadow-md">KERTAPATI PORT</span>
                    </div>
                </a>
            </div>

            <!-- Login Card — Frosted Glass on light -->
            <div class="w-[92%] sm:w-full sm:max-w-md mt-6 sm:mt-8 px-6 sm:px-8 py-8 sm:py-10 bg-white/95 backdrop-blur-xl shadow-2xl border border-white/60 rounded-2xl relative z-10 scroll-animate delay-200">
                <div class="mb-6 text-center">
                    <h2 class="text-xl font-bold text-slate-800 mb-1">Selamat Datang</h2>
                    <p class="text-sm text-slate-500">Silakan masuk menggunakan kredensial Anda</p>
                </div>
                {{ $slot }}
            </div>

            <div class="mt-10 text-center relative z-10 text-xs font-mono text-slate-800/50">
                &copy; {{ date('Y') }} PT Bukit Asam Tbk. All rights reserved.
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.scroll-animate').forEach(function(el) {
                    el.classList.add('animated');
                });
            });
        </script>
    </body>
</html>

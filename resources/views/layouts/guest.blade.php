<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-white antialiased selection:bg-secondary selection:text-slate-950">
        <!-- Main Background Container -->
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden bg-slate-950">
            <!-- Background Image with Overlays -->
            <div class="absolute inset-0 z-0">
                <img src="{{ asset('images/login-bg-3.jpg') }}" alt="PTBA Kertapati Port" class="w-full h-full object-cover object-center opacity-100 contrast-125 saturate-125 drop-shadow-2xl">
                <!-- Subtle Gradient Overlay to ensure text readability -->
                <div class="absolute inset-0 bg-slate-900/10"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-slate-950/40"></div>
            </div>

            <div class="relative z-10 flex flex-col items-center">
                <!-- App Logo -->
                <a href="/" class="group flex items-center gap-4 transition-transform hover:scale-105 duration-300">
                    <div class="flex items-center justify-center">
                        <img src="{{ asset('images/logo.png') }}" alt="Application Logo" class="h-14 w-auto drop-shadow-lg group-hover:drop-shadow-xl transition-all">
                    </div>
                    <div class="flex flex-col">
                        <span class="font-black text-3xl tracking-widest text-white leading-none drop-shadow-lg">E-REPORTING</span>
                        <span class="font-mono text-xs font-bold tracking-[0.3em] text-secondary mt-1 drop-shadow-md">KERTAPATI PORT</span>
                    </div>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-8 px-8 py-10 bg-slate-900/40 backdrop-blur-2xl shadow-[0_8px_30px_rgb(0,0,0,0.3)] border border-white/10 rounded-3xl relative z-10 transition-all duration-500 hover:border-yellow-500/30 hover:shadow-yellow-500/20">
                <div class="mb-6 text-center">
                    <h2 class="text-xl font-bold text-white mb-1">Selamat Datang</h2>
                    <p class="text-xs text-slate-400 font-mono">Silakan masuk menggunakan kredensial Anda</p>
                </div>
                {{ $slot }}
            </div>
            
            <div class="mt-10 text-center relative z-10 text-3xs font-mono text-slate-500">
                &copy; {{ date('Y') }} PT Bukit Asam Tbk. All rights reserved.
            </div>
        </div>
    </body>
</html>

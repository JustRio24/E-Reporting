<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'E-Reporting Inspeksi Fasilitas Pelabuhan') }}</title>

        <!-- Google Fonts: Inter & JetBrains Mono -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

        <!-- Leaflet.js (GIS Mapping) -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

        <!-- SweetAlert2 (Interactive Alerts) -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

        <!-- Chart.js (Dashboard Analytics) -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @php
            $roleBadgeBg = 'bg-slate-100';
            $roleBadgeText = 'text-slate-600';

            if (auth()->check()) {
                if (auth()->user()->isAdmin()) {
                    $roleBadgeBg = 'bg-amber-50';
                    $roleBadgeText = 'text-amber-700';
                } elseif (auth()->user()->isInspector()) {
                    $roleBadgeBg = 'bg-blue-50';
                    $roleBadgeText = 'text-blue-700';
                } elseif (auth()->user()->isSupervisor()) {
                    $roleBadgeBg = 'bg-teal-50';
                    $roleBadgeText = 'text-teal-700';
                } elseif (auth()->user()->isMaintenance()) {
                    $roleBadgeBg = 'bg-orange-50';
                    $roleBadgeText = 'text-orange-700';
                }
            }
        @endphp
    </head>
    <body class="h-full font-sans antialiased text-slate-800 bg-surface-50">
        <div class="flex h-full min-h-screen overflow-hidden">
            <!-- ═══ Sidebar (Desktop) ═══ -->
            <aside class="hidden lg:flex lg:flex-col lg:w-[260px] bg-white border-r border-gray-200 shadow-sidebar flex-shrink-0">
                <!-- Branding -->
                <div class="flex items-center gap-3 px-5 py-5 border-b border-gray-100">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-9 w-auto">
                    <div>
                        <h1 class="text-base font-extrabold tracking-wide text-slate-800 leading-none">E-REPORTING</h1>
                        <p class="text-[10px] font-mono font-semibold tracking-[0.2em] text-primary mt-0.5">KERTAPATI PORT</p>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-3 py-5 space-y-1 overflow-y-auto">
                    <a href="{{ route('dashboard') }}" class="sidebar-link {{ Request::is('dashboard*') ? 'active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" /></svg>
                        Dashboard
                    </a>

                    <a href="{{ route('gis.index') }}" class="sidebar-link {{ Request::is('gis-monitoring*') ? 'active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        GIS Monitoring
                    </a>

                    @if(auth()->user()->isAdmin() || auth()->user()->isSupervisor() || auth()->user()->isInspector())
                        <a href="{{ route('damage-reports.index') }}" class="sidebar-link {{ Request::is('damage-reports*') ? 'active' : '' }}">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            Laporan Kerusakan
                        </a>
                    @endif

                    @if(auth()->user()->isAdmin() || auth()->user()->isSupervisor() || auth()->user()->isMaintenance())
                        <a href="{{ route('work-orders.index') }}" class="sidebar-link {{ Request::is('work-orders*') ? 'active' : '' }}">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                            Perintah Kerja (WO)
                        </a>
                    @endif

                    @if(auth()->user()->isAdmin())
                        <div class="sidebar-section-title">MASTER DATA</div>

                        <a href="{{ route('facilities.index') }}" class="sidebar-link {{ Request::is('facilities*') ? 'active' : '' }}">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                            Fasilitas
                        </a>

                        <a href="{{ route('facility-categories.index') }}" class="sidebar-link {{ Request::is('facility-categories*') ? 'active' : '' }}">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                            Kategori Fasilitas
                        </a>

                        <a href="{{ route('locations.index') }}" class="sidebar-link {{ Request::is('locations*') ? 'active' : '' }}">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>
                            Lokasi / Area
                        </a>

                        <a href="{{ route('damage-categories.index') }}" class="sidebar-link {{ Request::is('damage-categories*') ? 'active' : '' }}">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            Kategori Kerusakan
                        </a>

                        <div class="sidebar-section-title">SISTEM</div>

                        <a href="{{ route('users.index') }}" class="sidebar-link {{ Request::is('users*') ? 'active' : '' }}">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                            Manajemen User
                        </a>
                    @endif

                    @if(auth()->user()->isAdmin() || auth()->user()->isSupervisor())
                        <div class="sidebar-section-title">LAPORAN</div>

                        <a href="{{ route('reports.index') }}" class="sidebar-link {{ Request::is('reports*') ? 'active' : '' }}">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            Rekap & Cetak PDF
                        </a>
                    @endif
                </nav>

                <!-- User Profile in Sidebar Footer -->
                <div class="p-4 border-t border-gray-100 bg-surface-50">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-primary flex items-center justify-center text-slate-800 font-bold text-sm">
                            {{ substr(auth()->user()->name, 0, 2) }}
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-sm font-semibold text-slate-800 truncate">{{ auth()->user()->name }}</p>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold tracking-wider uppercase font-mono {{ $roleBadgeBg }} {{ $roleBadgeText }}">
                                {{ auth()->user()->role->value }}
                            </span>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- ═══ Main Panel ═══ -->
            <div class="flex flex-col flex-1 h-full overflow-hidden">
                <!-- Top Header -->
                <header class="flex items-center justify-between px-6 py-3.5 bg-white border-b border-gray-200 shadow-header z-10 flex-shrink-0">
                    <div class="flex items-center gap-4">
                        <!-- Mobile menu trigger (hidden in favor of bottom nav) -->
                        <button class="hidden text-slate-500 hover:text-primary focus:outline-none transition-colors cursor-pointer" id="mobile-menu-btn-top">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                        </button>
                        <h2 class="text-lg font-bold text-slate-800">
                            @yield('page-title', 'Dashboard')
                        </h2>
                    </div>

                    <!-- Right Controls -->
                    <div class="flex items-center gap-3">
                        <!-- Notification Bell -->
                        <div class="relative">
                            <button id="notification-btn" class="relative p-2 text-slate-400 hover:text-primary hover:bg-primary-50 rounded-lg focus:outline-none transition-all duration-200 cursor-pointer">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                                @if(isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
                                    <span class="absolute -top-0.5 -right-0.5 inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-slate-800 bg-accent rounded-full">
                                        {{ $unreadNotificationsCount }}
                                    </span>
                                @endif
                            </button>

                            <!-- Notification Dropdown -->
                            <div id="notification-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded-xl shadow-lg py-1 z-50 animate-slide-down">
                                <div class="px-4 py-3 font-semibold text-xs border-b border-gray-100 flex justify-between items-center text-slate-600">
                                    <span>Pemberitahuan</span>
                                    @if(isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
                                        <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-primary hover:underline transition-colors cursor-pointer">Tandai semua dibaca</button>
                                        </form>
                                    @endif
                                </div>
                                <div class="max-h-64 overflow-y-auto">
                                    @if(isset($unreadNotificationsList) && $unreadNotificationsList->count() > 0)
                                        @foreach($unreadNotificationsList as $notif)
                                            <div class="px-4 py-3 hover:bg-surface-50 border-b border-gray-50 transition-colors">
                                                <div class="flex justify-between items-start">
                                                    <span class="text-xs font-semibold text-slate-800">{{ $notif->title }}</span>
                                                    <span class="text-[10px] font-mono text-slate-400 ml-2 flex-shrink-0">{{ $notif->created_at->diffForHumans() }}</span>
                                                </div>
                                                <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $notif->message }}</p>
                                                <form action="{{ route('notifications.read', $notif->id) }}" method="POST" class="mt-2 text-right">
                                                    @csrf
                                                    <button type="submit" class="text-[10px] font-bold text-primary hover:underline cursor-pointer">Tandai Dibaca</button>
                                                </form>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="px-4 py-8 text-center text-xs text-slate-400">
                                            Tidak ada notifikasi baru.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- User Profile Dropdown -->
                        <div class="relative">
                            <button id="profile-dropdown-btn" class="flex items-center gap-2 text-sm font-medium text-slate-600 hover:text-primary focus:outline-none transition-colors cursor-pointer">
                                <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-slate-800 font-bold text-xs">
                                    {{ substr(auth()->user()->name, 0, 2) }}
                                </div>
                                <span class="hidden md:flex items-center gap-1">
                                    {{ auth()->user()->name }}
                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </span>
                            </button>

                            <div id="profile-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-xl shadow-lg py-1 z-50 animate-slide-down">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-sm text-slate-600 hover:bg-surface-50 hover:text-primary transition-colors cursor-pointer">Profil Saya</a>
                                <div class="border-t border-gray-100"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 font-medium cursor-pointer transition-colors">
                                        Keluar / Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 bg-surface-50 pb-28 lg:pb-6">
                    @yield('content')
                </main>
            </div>
        </div>

        <!-- ═══ Mobile Floating Bottom Navigation ═══ -->
        <nav class="lg:hidden fixed bottom-6 left-4 right-4 z-40 bg-white/90 backdrop-blur-xl border border-white/60 shadow-[0_8px_30px_rgb(0,0,0,0.12)] rounded-3xl flex justify-around items-center px-2 py-3">
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center gap-1 w-16 text-slate-400 hover:text-primary {{ Request::is('dashboard*') ? 'text-primary' : '' }} transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" /></svg>
                <span class="text-[10px] font-semibold tracking-wide">Beranda</span>
            </a>
            
            <a href="{{ route('gis.index') }}" class="flex flex-col items-center justify-center gap-1 w-16 text-slate-400 hover:text-primary {{ Request::is('gis-monitoring*') ? 'text-primary' : '' }} transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                <span class="text-[10px] font-semibold tracking-wide">GIS</span>
            </a>

            @if(auth()->user()->isAdmin() || auth()->user()->isSupervisor() || auth()->user()->isInspector())
            <a href="{{ route('damage-reports.index') }}" class="flex flex-col items-center justify-center gap-1 w-16 text-slate-400 hover:text-primary {{ Request::is('damage-reports*') ? 'text-primary' : '' }} transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                <span class="text-[10px] font-semibold tracking-wide">Laporan</span>
            </a>
            @endif

            @if(auth()->user()->isAdmin() || auth()->user()->isSupervisor() || auth()->user()->isMaintenance())
            <a href="{{ route('work-orders.index') }}" class="flex flex-col items-center justify-center gap-1 w-16 text-slate-400 hover:text-primary {{ Request::is('work-orders*') ? 'text-primary' : '' }} transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                <span class="text-[10px] font-semibold tracking-wide">WO</span>
            </a>
            @endif

            <button id="mobile-menu-btn" class="flex flex-col items-center justify-center gap-1 w-16 text-slate-400 hover:text-primary focus:outline-none transition-colors cursor-pointer">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                <span class="text-[10px] font-semibold tracking-wide">Menu</span>
            </button>
        </nav>

        <!-- ═══ Mobile Sidebar Overlay ═══ -->
        <div id="mobile-sidebar" class="hidden fixed inset-0 z-50 flex">
            <div class="fixed inset-0 bg-slate-900/30 backdrop-blur-sm transition-opacity" id="mobile-sidebar-backdrop"></div>

            <aside class="relative flex flex-col w-[280px] h-full bg-white shadow-xl">
                <div class="flex items-center justify-between px-5 py-5 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-auto">
                        <div>
                            <h1 class="text-base font-extrabold tracking-wide text-slate-800 leading-none">E-REPORTING</h1>
                            <p class="text-[10px] font-mono font-semibold tracking-[0.2em] text-primary mt-0.5">KERTAPATI PORT</p>
                        </div>
                    </div>
                    <button class="text-slate-400 hover:text-slate-600 transition-colors cursor-pointer" id="mobile-menu-close-btn">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <nav class="flex-1 px-3 py-5 space-y-1 overflow-y-auto" id="mobile-nav-container">
                </nav>

                <div class="p-4 border-t border-gray-100 bg-surface-50">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-primary flex items-center justify-center text-slate-800 font-bold text-sm">
                            {{ substr(auth()->user()->name, 0, 2) }}
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-sm font-semibold text-slate-800 truncate">{{ auth()->user()->name }}</p>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold tracking-wider uppercase font-mono {{ $roleBadgeBg }} {{ $roleBadgeText }}">
                                {{ auth()->user()->role->value }}
                            </span>
                        </div>
                    </div>
                </div>
            </aside>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // ─── Dropdown Toggles ───
                const profileBtn = document.getElementById('profile-dropdown-btn');
                const profileDropdown = document.getElementById('profile-dropdown');
                const notificationBtn = document.getElementById('notification-btn');
                const notificationDropdown = document.getElementById('notification-dropdown');
                const mobileMenuBtn = document.getElementById('mobile-menu-btn');
                const mobileSidebar = document.getElementById('mobile-sidebar');
                const mobileSidebarBackdrop = document.getElementById('mobile-sidebar-backdrop');
                const mobileMenuCloseBtn = document.getElementById('mobile-menu-close-btn');

                if (profileBtn) {
                    profileBtn.addEventListener('click', function (e) {
                        e.stopPropagation();
                        profileDropdown.classList.toggle('hidden');
                        if (notificationDropdown) notificationDropdown.classList.add('hidden');
                    });
                }

                if (notificationBtn) {
                    notificationBtn.addEventListener('click', function (e) {
                        e.stopPropagation();
                        notificationDropdown.classList.toggle('hidden');
                        if (profileDropdown) profileDropdown.classList.add('hidden');
                    });
                }

                document.addEventListener('click', function () {
                    if (profileDropdown) profileDropdown.classList.add('hidden');
                    if (notificationDropdown) notificationDropdown.classList.add('hidden');
                });

                // ─── Mobile Menu ───
                if (mobileMenuBtn && mobileSidebar) {
                    const desktopNav = document.querySelector('aside nav');
                    const mobileNav = document.getElementById('mobile-nav-container');
                    if (desktopNav && mobileNav) {
                        mobileNav.innerHTML = desktopNav.innerHTML;
                    }

                    mobileMenuBtn.addEventListener('click', function () {
                        mobileSidebar.classList.remove('hidden');
                    });

                    const closeMobileMenu = function () {
                        mobileSidebar.classList.add('hidden');
                    };

                    mobileSidebarBackdrop.addEventListener('click', closeMobileMenu);
                    mobileMenuCloseBtn.addEventListener('click', closeMobileMenu);
                }

                // ─── Scroll Animation Observer ───
                const scrollElements = document.querySelectorAll('.scroll-animate, .scroll-animate-left, .scroll-animate-right, .scroll-animate-scale');
                if (scrollElements.length > 0 && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                    const observer = new IntersectionObserver(function(entries) {
                        entries.forEach(function(entry) {
                            if (entry.isIntersecting) {
                                entry.target.classList.add('animated');
                                observer.unobserve(entry.target);
                            }
                        });
                    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

                    scrollElements.forEach(function(el) {
                        observer.observe(el);
                    });
                } else {
                    scrollElements.forEach(function(el) {
                        el.classList.add('animated');
                    });
                }

                // ─── SweetAlert2 Flash Messages ───
                @if(session('success'))
                    Swal.fire({
                        title: 'Berhasil!',
                        text: "{{ session('success') }}",
                        icon: 'success',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#1961a1',
                        customClass: { popup: 'rounded-xl', confirmButton: 'rounded-lg px-4 py-2 font-semibold text-sm' }
                    });
                @endif

                @if(session('error'))
                    Swal.fire({
                        title: 'Error!',
                        text: "{{ session('error') }}",
                        icon: 'error',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#dc2626',
                        customClass: { popup: 'rounded-xl', confirmButton: 'rounded-lg px-4 py-2 font-semibold text-sm' }
                    });
                @endif
            });
        </script>
        @stack('scripts')
    </body>
</html>

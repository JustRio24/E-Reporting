<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gradient-to-br from-slate-900 via-slate-950 to-zinc-900">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'E-Reporting Inspeksi Fasilitas Pelabuhan') }}</title>

        <!-- Google Fonts: IBM Plex Sans & JetBrains Mono -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

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
            $roleAccentBorder = 'border-slate-500';
            $roleAccentText = 'text-slate-400';
            $roleAccentHoverText = 'hover:text-slate-300';
            $roleAccentShadow = 'shadow-slate-500/20';
            $roleBadgeBg = 'bg-slate-500/10';
            $roleBadgeText = 'text-slate-300';
            $roleBadgeBorder = 'border-slate-500/30';
            
            if (auth()->check()) {
                if (auth()->user()->isAdmin()) {
                    $roleAccentBorder = 'border-yellow-500';
                    $roleAccentText = 'text-yellow-400';
                    $roleAccentHoverText = 'hover:text-yellow-300';
                    $roleAccentShadow = 'shadow-yellow-500/20';
                    $roleBadgeBg = 'bg-yellow-500/10';
                    $roleBadgeText = 'text-yellow-300';
                    $roleBadgeBorder = 'border-yellow-500/30';
                } elseif (auth()->user()->isInspector()) {
                    $roleAccentBorder = 'border-blue-600';
                    $roleAccentText = 'text-blue-400';
                    $roleAccentHoverText = 'hover:text-blue-300';
                    $roleAccentShadow = 'shadow-blue-500/20';
                    $roleBadgeBg = 'bg-blue-500/10';
                    $roleBadgeText = 'text-blue-300';
                    $roleBadgeBorder = 'border-blue-500/30';
                } elseif (auth()->user()->isSupervisor()) {
                    $roleAccentBorder = 'border-teal-600';
                    $roleAccentText = 'text-teal-400';
                    $roleAccentHoverText = 'hover:text-teal-300';
                    $roleAccentShadow = 'shadow-teal-500/20';
                    $roleBadgeBg = 'bg-teal-500/10';
                    $roleBadgeText = 'text-teal-300';
                    $roleBadgeBorder = 'border-teal-500/30';
                } elseif (auth()->user()->isMaintenance()) {
                    $roleAccentBorder = 'border-orange-500';
                    $roleAccentText = 'text-orange-400';
                    $roleAccentHoverText = 'hover:text-orange-300';
                    $roleAccentShadow = 'shadow-orange-500/30';
                    $roleBadgeBg = 'bg-orange-500/10';
                    $roleBadgeText = 'text-orange-300';
                    $roleBadgeBorder = 'border-orange-500/30';
                }
            }
        @endphp
    </head>
    <body class="h-full font-sans antialiased text-white bg-gradient-to-br from-slate-900 via-slate-950 to-zinc-900">
        <div class="flex h-full min-h-screen overflow-hidden">
            <!-- Sidebar (Desktop View) -->
            <aside class="hidden lg:flex lg:flex-col lg:w-64 bg-gradient-to-b from-slate-950 via-blue-900/20 to-slate-950 text-slate-300 border-r border-slate-800 flex-shrink-0">
                <!-- Sidebar Branding Header -->
                <div class="flex items-center justify-between px-6 py-5 bg-slate-950 border-b border-slate-800">
                    <div>
                        <h1 class="text-lg font-bold tracking-wider text-white">E-REPORTING</h1>
                        <p class="text-xs font-mono tracking-widest text-primary-container">KERTAPATI PORT</p>
                    </div>
                </div>

                <!-- Navigation Links -->
                <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                    <!-- General / Dashboard -->
                    <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-md hover:bg-slate-800/60 hover:backdrop-blur-md {{ $roleAccentHoverText }} transition-all duration-300 {{ Request::is('dashboard*') ? 'bg-gradient-to-br from-slate-900/80 via-blue-900/40 to-yellow-600/10 backdrop-blur-md border-l-4 shadow-lg ' . $roleAccentBorder . ' ' . $roleAccentShadow . ' ' . $roleAccentText : '' }}">
                        <svg class="w-5 h-5 mr-3 text-slate-300 group-{{ $roleAccentHoverText }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                        </svg>
                        Dashboard
                    </a>

                    <!-- GIS Monitor -->
                    <a href="{{ route('gis.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-md hover:bg-slate-800/60 hover:backdrop-blur-md {{ $roleAccentHoverText }} transition-all duration-300 {{ Request::is('gis-monitoring*') ? 'bg-gradient-to-br from-slate-900/80 via-blue-900/40 to-yellow-600/10 backdrop-blur-md border-l-4 shadow-lg ' . $roleAccentBorder . ' ' . $roleAccentShadow . ' ' . $roleAccentText : '' }}">
                        <svg class="w-5 h-5 mr-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        GIS Monitoring
                    </a>

                    <!-- Damage Reports (Inspectors, Supervisors, Admins) -->
                    @if(auth()->user()->isAdmin() || auth()->user()->isSupervisor() || auth()->user()->isInspector())
                        <a href="{{ route('damage-reports.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-md hover:bg-slate-800/60 hover:backdrop-blur-md {{ $roleAccentHoverText }} transition-all duration-300 {{ Request::is('damage-reports*') ? 'bg-gradient-to-br from-slate-900/80 via-blue-900/40 to-yellow-600/10 backdrop-blur-md border-l-4 shadow-lg ' . $roleAccentBorder . ' ' . $roleAccentShadow . ' ' . $roleAccentText : '' }}">
                            <svg class="w-5 h-5 mr-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Laporan Kerusakan
                        </a>
                    @endif

                    <!-- Work Orders (Supervisors, Maintenance, Admins) -->
                    @if(auth()->user()->isAdmin() || auth()->user()->isSupervisor() || auth()->user()->isMaintenance())
                        <a href="{{ route('work-orders.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-md hover:bg-slate-800/60 hover:backdrop-blur-md {{ $roleAccentHoverText }} transition-all duration-300 {{ Request::is('work-orders*') ? 'bg-gradient-to-br from-slate-900/80 via-blue-900/40 to-yellow-600/10 backdrop-blur-md border-l-4 shadow-lg ' . $roleAccentBorder . ' ' . $roleAccentShadow . ' ' . $roleAccentText : '' }}">
                            <svg class="w-5 h-5 mr-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            Perintah Kerja (WO)
                        </a>
                    @endif

                    <!-- Facility Management (Admin only) -->
                    @if(auth()->user()->isAdmin())
                        <div class="pt-4 pb-2 text-xs font-mono font-bold tracking-widest text-slate-300 uppercase">MASTER DATA</div>

                        <a href="{{ route('facilities.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-md hover:bg-slate-800/60 hover:backdrop-blur-md {{ $roleAccentHoverText }} transition-all duration-300 {{ Request::is('facilities*') ? 'bg-gradient-to-br from-slate-900/80 via-blue-900/40 to-yellow-600/10 backdrop-blur-md border-l-4 shadow-lg ' . $roleAccentBorder . ' ' . $roleAccentShadow . ' ' . $roleAccentText : '' }}">
                            <svg class="w-5 h-5 mr-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Fasilitas
                        </a>

                        <a href="{{ route('facility-categories.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-md hover:bg-slate-800/60 hover:backdrop-blur-md {{ $roleAccentHoverText }} transition-all duration-300 {{ Request::is('facility-categories*') ? 'bg-gradient-to-br from-slate-900/80 via-blue-900/40 to-yellow-600/10 backdrop-blur-md border-l-4 shadow-lg ' . $roleAccentBorder . ' ' . $roleAccentShadow . ' ' . $roleAccentText : '' }}">
                            <svg class="w-5 h-5 mr-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            Kategori Fasilitas
                        </a>

                        <a href="{{ route('locations.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-md hover:bg-slate-800/60 hover:backdrop-blur-md {{ $roleAccentHoverText }} transition-all duration-300 {{ Request::is('locations*') ? 'bg-gradient-to-br from-slate-900/80 via-blue-900/40 to-yellow-600/10 backdrop-blur-md border-l-4 shadow-lg ' . $roleAccentBorder . ' ' . $roleAccentShadow . ' ' . $roleAccentText : '' }}">
                            <svg class="w-5 h-5 mr-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                            Lokasi / Area
                        </a>

                        <a href="{{ route('damage-categories.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-md hover:bg-slate-800/60 hover:backdrop-blur-md {{ $roleAccentHoverText }} transition-all duration-300 {{ Request::is('damage-categories*') ? 'bg-gradient-to-br from-slate-900/80 via-blue-900/40 to-yellow-600/10 backdrop-blur-md border-l-4 shadow-lg ' . $roleAccentBorder . ' ' . $roleAccentShadow . ' ' . $roleAccentText : '' }}">
                            <svg class="w-5 h-5 mr-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Kategori Kerusakan
                        </a>

                        <div class="pt-4 pb-2 text-xs font-mono font-bold tracking-widest text-slate-300 uppercase">SISTEM</div>

                        <a href="{{ route('users.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-md hover:bg-slate-800/60 hover:backdrop-blur-md {{ $roleAccentHoverText }} transition-all duration-300 {{ Request::is('users*') ? 'bg-gradient-to-br from-slate-900/80 via-blue-900/40 to-yellow-600/10 backdrop-blur-md border-l-4 shadow-lg ' . $roleAccentBorder . ' ' . $roleAccentShadow . ' ' . $roleAccentText : '' }}">
                            <svg class="w-5 h-5 mr-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Manajemen User
                        </a>
                    @endif

                    <!-- Reporting (Admin and Supervisor) -->
                    @if(auth()->user()->isAdmin() || auth()->user()->isSupervisor())
                        <div class="pt-4 pb-2 text-xs font-mono font-bold tracking-widest text-slate-300 uppercase">LAPORAN</div>

                        <a href="{{ route('reports.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-md hover:bg-slate-800/60 hover:backdrop-blur-md {{ $roleAccentHoverText }} transition-all duration-300 {{ Request::is('reports*') ? 'bg-gradient-to-br from-slate-900/80 via-blue-900/40 to-yellow-600/10 backdrop-blur-md border-l-4 shadow-lg ' . $roleAccentBorder . ' ' . $roleAccentShadow . ' ' . $roleAccentText : '' }}">
                            <svg class="w-5 h-5 mr-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Rekap & Cetak PDF
                        </a>
                    @endif
                </nav>

                <!-- User Profile Summary in Sidebar Footer -->
                <div class="p-4 border-t border-slate-800 bg-slate-950">
                    <div class="flex items-center">
                        <div class="w-9 h-9 rounded-full bg-primary flex items-center justify-center text-white font-bold text-sm tracking-wide">
                            {{ substr(auth()->user()->name, 0, 2) }}
                        </div>
                        <div class="ml-3 overflow-hidden">
                            <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-2xs font-bold tracking-wider uppercase font-mono {{ $roleBadgeBg }} {{ $roleBadgeText }} border {{ $roleBadgeBorder }} transition-all duration-300">
                                {{ auth()->user()->role->value }}
                            </span>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Panel -->
            <div class="flex flex-col flex-1 h-full overflow-hidden">
                <!-- Top Header -->
                <header class="flex items-center justify-between px-6 py-4 bg-gradient-to-br from-slate-900/80 via-blue-900/40 to-yellow-600/10 backdrop-blur-md border-b border-slate-700/50 shadow-sm z-10 flex-shrink-0">
                    <!-- Title / Left side -->
                    <div class="flex items-center">
                        <!-- Mobile menu trigger -->
                        <button class="mr-4 text-slate-300 lg:hidden focus:outline-none" id="mobile-menu-btn">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <h2 class="text-xl font-bold text-white tracking-tight">
                            @yield('page-title', 'Dashboard')
                        </h2>
                    </div>

                    <!-- Right Controls (Notifications & Profile) -->
                    <div class="flex items-center space-x-4">
                        <!-- Notification Tray Dropdown -->
                        <div class="relative">
                            <button id="notification-btn" class="relative p-1 text-slate-300 {{ $roleAccentHoverText }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary">
                                <span class="sr-only">Notifikasi</span>
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                @if(isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
                                    <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-3xs font-mono font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-primary rounded-full">
                                        {{ $unreadNotificationsCount }}
                                    </span>
                                @endif
                            </button>

                            <!-- Notification Dropdown Panel -->
                            <div id="notification-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-gradient-to-br from-slate-900/80 via-blue-900/40 to-yellow-600/10 backdrop-blur-md border border-slate-700/50 border-t-2 border-t-yellow-500/70 transition-all duration-300 hover:border-yellow-500/50 hover:shadow-lg hover:shadow-yellow-500/10 rounded-md shadow-lg py-1 z-50">
                                <div class="px-4 py-2 font-semibold text-xs border-b border-slate-100 flex justify-between items-center text-slate-300">
                                    <span>Pemberitahuan</span>
                                    @if(isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
                                        <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="hover:text-primary transition-colors">Tandai semua dibaca</button>
                                        </form>
                                    @endif
                                </div>
                                <div class="max-h-64 overflow-y-auto">
                                    @if(isset($unreadNotificationsList) && $unreadNotificationsList->count() > 0)
                                        @foreach($unreadNotificationsList as $notif)
                                            <div class="px-4 py-3 hover:bg-gradient-to-br from-slate-900 via-slate-950 to-zinc-900 border-b border-slate-50 flex flex-col">
                                                <div class="flex justify-between items-start">
                                                    <span class="text-xs font-semibold text-white">{{ $notif->title }}</span>
                                                    <span class="text-4xs font-mono text-slate-300">{{ $notif->created_at->diffForHumans() }}</span>
                                                </div>
                                                <p class="text-2xs text-slate-300 mt-1 leading-normal">{{ $notif->message }}</p>
                                                <form action="{{ route('notifications.read', $notif->id) }}" method="POST" class="mt-2 text-right">
                                                    @csrf
                                                    <button type="submit" class="text-4xs font-bold text-secondary hover:underline">Tandai Dibaca</button>
                                                </form>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="px-4 py-6 text-center text-xs text-slate-300">
                                            Tidak ada notifikasi baru.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- User Profile Dropdown -->
                        <div class="relative">
                            <button id="profile-dropdown-btn" class="flex items-center text-sm font-medium text-slate-300 {{ $roleAccentHoverText }} focus:outline-none">
                                <div class="w-8 h-8 rounded-full bg-secondary flex items-center justify-center text-white font-bold text-xs tracking-wide">
                                    {{ substr(auth()->user()->name, 0, 2) }}
                                </div>
                                <span class="hidden md:flex items-center ml-2">
                                    {{ auth()->user()->name }}
                                    <svg class="w-4 h-4 ml-1 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </span>
                            </button>

                            <!-- Profile Panel -->
                            <div id="profile-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-gradient-to-br from-slate-900/80 via-blue-900/40 to-yellow-600/10 backdrop-blur-md border border-slate-700/50 border-t-2 border-t-yellow-500/70 transition-all duration-300 hover:border-yellow-500/50 hover:shadow-lg hover:shadow-yellow-500/10 rounded-md shadow-lg py-1 z-50">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-xs text-slate-300 hover:bg-slate-700/50">Profil Saya</a>
                                <div class="border-t border-slate-100"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-xs text-red-600 hover:bg-red-500/10 font-medium">
                                        Keluar / Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content Area -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 bg-gradient-to-br from-slate-900 via-slate-950 to-zinc-900">
                    @yield('content')
                </main>
            </div>
        </div>

        <!-- Mobile Sidebar Overlay Menu -->
        <div id="mobile-sidebar" class="hidden fixed inset-0 z-40 flex">
            <!-- Backdrop overlay -->
            <div class="fixed inset-0 bg-gradient-to-br from-slate-900 via-slate-950 to-zinc-900/60 backdrop-blur-sm" id="mobile-sidebar-backdrop"></div>

            <!-- Mobile content panel -->
            <aside class="relative flex flex-col w-64 h-full bg-gradient-to-b from-slate-950 via-blue-900/20 to-slate-950 text-slate-300">
                <div class="flex items-center justify-between px-6 py-5 bg-slate-950 border-b border-slate-800">
                    <div>
                        <h1 class="text-lg font-bold tracking-wider text-white">E-REPORTING</h1>
                        <p class="text-xs font-mono tracking-widest text-primary-container">KERTAPATI PORT</p>
                    </div>
                    <button class="text-slate-300 {{ $roleAccentHoverText }}" id="mobile-menu-close-btn">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto" id="mobile-nav-container">
                    <!-- Dynamically populated via cloning desktop or writing script -->
                </nav>

                <div class="p-4 border-t border-slate-800 bg-slate-950">
                    <div class="flex items-center">
                        <div class="w-9 h-9 rounded-full bg-primary flex items-center justify-center text-white font-bold text-sm tracking-wide">
                            {{ substr(auth()->user()->name, 0, 2) }}
                        </div>
                        <div class="ml-3 overflow-hidden">
                            <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-2xs font-bold tracking-wider uppercase font-mono {{ $roleBadgeBg }} {{ $roleBadgeText }} border {{ $roleBadgeBorder }} transition-all duration-300">
                                {{ auth()->user()->role->value }}
                            </span>
                        </div>
                    </div>
                </div>
            </aside>
        </div>

        <script>
            // Toggle dropdown panel displays
            document.addEventListener('DOMContentLoaded', function () {
                const profileBtn = document.getElementById('profile-dropdown-btn');
                const profileDropdown = document.getElementById('profile-dropdown');
                const notificationBtn = document.getElementById('notification-btn');
                const notificationDropdown = document.getElementById('notification-dropdown');
                const mobileMenuBtn = document.getElementById('mobile-menu-btn');
                const mobileSidebar = document.getElementById('mobile-sidebar');
                const mobileSidebarBackdrop = document.getElementById('mobile-sidebar-backdrop');
                const mobileMenuCloseBtn = document.getElementById('mobile-menu-close-btn');

                // Profile Dropdown Toggle
                if (profileBtn) {
                    profileBtn.addEventListener('click', function (e) {
                        e.stopPropagation();
                        profileDropdown.classList.toggle('hidden');
                        if (notificationDropdown) notificationDropdown.classList.add('hidden');
                    });
                }

                // Notification Dropdown Toggle
                if (notificationBtn) {
                    notificationBtn.addEventListener('click', function (e) {
                        e.stopPropagation();
                        notificationDropdown.classList.toggle('hidden');
                        if (profileDropdown) profileDropdown.classList.add('hidden');
                    });
                }

                // Document Click (close all dropdowns)
                document.addEventListener('click', function () {
                    if (profileDropdown) profileDropdown.classList.add('hidden');
                    if (notificationDropdown) notificationDropdown.classList.add('hidden');
                });

                // Mobile Menu Toggle
                if (mobileMenuBtn && mobileSidebar) {
                    // Clone desktop nav links to mobile nav container
                    const desktopNav = document.querySelector('nav');
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

                // SweetAlert2 flash message handles
                @if(session('success'))
                    Swal.fire({
                        title: 'Berhasil!',
                        text: "{{ session('success') }}",
                        icon: 'success',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#1961a1', // Industrial Blue
                        customClass: {
                            popup: 'rounded-lg',
                            confirmButton: 'rounded px-4 py-2 font-semibold text-sm'
                        }
                    });
                @endif

                @if(session('error'))
                    Swal.fire({
                        title: 'Error!',
                        text: "{{ session('error') }}",
                        icon: 'error',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#ba1a1a', // Safety Red
                        customClass: {
                            popup: 'rounded-lg',
                            confirmButton: 'rounded px-4 py-2 font-semibold text-sm'
                        }
                    });
                @endif
            });
        </script>
        @stack('scripts')
    </body>
</html>

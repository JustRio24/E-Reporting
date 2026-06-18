@extends('layouts.app')

@section('page-title', 'Laporan Kerusakan Fasilitas')

@section('content')
<div class="space-y-6">
    <!-- Header with tab status & filters -->
    <div class="bg-gradient-to-br from-slate-900/80 via-blue-900/40 to-yellow-600/10 backdrop-blur-md p-4 rounded-lg border border-slate-700/50 shadow-sm space-y-4 transition-all duration-500 hover:border-yellow-500/30 hover:shadow-lg hover:shadow-yellow-500/5">
        <!-- Status Tabs -->
        <div class="border-b border-slate-700/50 overflow-x-auto flex">
            <nav class="flex space-x-6 min-w-max text-xs font-bold uppercase tracking-wider font-mono">
                <a href="{{ route('damage-reports.index', array_merge(request()->except('status'), ['status' => ''])) }}" 
                    class="pb-3 border-b-2 px-1 {{ !request('status') ? 'border-primary text-primary' : 'border-transparent text-slate-400 hover:text-slate-200' }}">
                    Semua
                </a>
                @foreach($statuses as $status)
                    <a href="{{ route('damage-reports.index', array_merge(request()->except('status'), ['status' => $status->value])) }}" 
                        class="pb-3 border-b-2 px-1 {{ request('status') === $status->value ? 'border-primary text-primary' : 'border-transparent text-slate-400 hover:text-slate-200' }}">
                        {{ str_replace('_', ' ', $status->value) }}
                    </a>
                @endforeach
            </nav>
        </div>

        <!-- Filter Form -->
        <form action="{{ route('damage-reports.index') }}" method="GET" class="flex flex-wrap items-center gap-3 justify-between">
            <!-- Keep active status in hidden if present -->
            <input type="hidden" name="status" value="{{ request('status') }}">

            <div class="flex flex-wrap items-center gap-3">
                <div class="w-full sm:w-64 relative">
                    <input type="text" name="search" placeholder="Cari nomor atau judul laporan..." value="{{ $filters['search'] ?? '' }}" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary pl-8 py-2">
                    <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <select name="severity" class="text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2">
                    <option value="">Semua Tingkat Kerusakan</option>
                    @foreach($severities as $sev)
                        <option value="{{ $sev->value }}" {{ (isset($filters['severity']) && $filters['severity'] === $sev->value) ? 'selected' : '' }}>
                            {{ strtoupper($sev->value) }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="bg-secondary text-white text-xs font-semibold px-4 py-2 rounded hover:bg-secondary-dark transition-colors">
                    Filter
                </button>
                @if(!empty(request()->except('status')))
                    <a href="{{ route('damage-reports.index', ['status' => request('status')]) }}" class="text-xs text-slate-400 hover:text-slate-200 underline">Reset</a>
                @endif
            </div>

            @if(auth()->user()->isInspector() || auth()->user()->isAdmin())
                <a href="{{ route('damage-reports.create') }}" class="inline-flex items-center justify-center bg-primary text-white text-xs font-bold tracking-wider uppercase px-4 py-2.5 rounded hover:bg-primary-dark transition-colors">
                    Buat Laporan Baru
                </a>
            @endif
        </form>
    </div>

    <!-- Data List/Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($reports as $report)
            <div class="bg-gradient-to-br from-slate-900/80 via-blue-900/40 to-yellow-600/10 backdrop-blur-md rounded-lg border shadow-sm overflow-hidden flex flex-col transition-all duration-150 hover:shadow-md
                @if($report->severity->value === 'critical' && $report->status->value !== 'completed') border-red-300 ring-1 ring-red-500/10 @else border-slate-700/50 @endif transition-all duration-500 hover:border-yellow-500/30 hover:shadow-lg hover:shadow-yellow-500/5">
                
                <!-- Report Photo Header -->
                <div class="h-44 bg-slate-700/50 relative">
                    @if($report->photos->first())
                        <img src="{{ asset('storage/' . $report->photos->first()->photo_path) }}" alt="Foto Kerusakan" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-350 bg-slate-700/50">
                            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                    
                    <!-- Severity & Status Badges -->
                    <div class="absolute top-2.5 left-2.5 flex flex-col gap-1.5 z-10">
                        <span class="inline-flex px-2 py-0.5 rounded text-4xs font-mono font-bold tracking-wider uppercase text-white shadow-sm
                            @if($report->severity->value === 'low') bg-slate-500
                            @elseif($report->severity->value === 'medium') bg-amber-500
                            @elseif($report->severity->value === 'high') bg-orange-600
                            @else bg-red-650 @endif">
                            {{ $report->severity->value }}
                        </span>
                    </div>

                    <div class="absolute top-2.5 right-2.5 z-10">
                        <span class="inline-flex px-2 py-0.5 rounded text-4xs font-bold tracking-wider uppercase text-white shadow-sm
                            @if($report->status->value === 'draft') bg-slate-600
                            @elseif($report->status->value === 'reported') bg-blue-600
                            @elseif($report->status->value === 'verified') bg-indigo-650
                            @elseif($report->status->value === 'assigned') bg-amber-500
                            @elseif($report->status->value === 'in_progress') bg-orange-500
                            @elseif($report->status->value === 'waiting_verification') bg-yellow-500
                            @else bg-emerald-600 @endif">
                            {{ str_replace('_', ' ', $report->status->value) }}
                        </span>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-5 flex-1 flex flex-col">
                    <span class="block text-4xs font-mono font-bold tracking-widest text-slate-400 uppercase">{{ $report->report_number }}</span>
                    <h4 class="text-sm font-bold text-white mt-1 line-clamp-1 hover:text-secondary transition-colors">
                        <a href="{{ route('damage-reports.show', $report->id) }}">{{ $report->title }}</a>
                    </h4>
                    
                    <p class="text-2xs text-slate-400 mt-2 line-clamp-2 leading-relaxed flex-1">{{ $report->description }}</p>
                    
                    <!-- Facility & Location metadata -->
                    <div class="mt-4 pt-3 border-t border-slate-100 space-y-1.5 text-3xs font-mono">
                        <div class="flex items-center text-slate-300">
                            <span class="w-16 font-bold uppercase">Fasilitas:</span>
                            <span class="truncate">{{ $report->facility->facility_name }}</span>
                        </div>
                        <div class="flex items-center text-slate-300">
                            <span class="w-16 font-bold uppercase">Lokasi:</span>
                            <span class="truncate">{{ $report->facility->location->name }}</span>
                        </div>
                    </div>
                </div>

                <!-- Card Footer Actions -->
                <div class="px-5 py-3.5 bg-slate-900/40 border-t border-slate-100 flex justify-between items-center text-3xs">
                    <span class="font-mono text-slate-455">Oleh: {{ $report->reporter->name }}</span>
                    <a href="{{ route('damage-reports.show', $report->id) }}" class="font-bold text-secondary hover:underline flex items-center">
                        Lihat Detail &rarr;
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-gradient-to-br from-slate-900/80 via-blue-900/40 to-yellow-600/10 backdrop-blur-md rounded-lg border border-slate-700/50 py-16 text-center text-yellow-200/80 font-mono text-xs transition-all duration-500 hover:border-yellow-500/30 hover:shadow-lg hover:shadow-yellow-500/5">
                Tidak ada laporan kerusakan yang ditemukan.
            </div>
        @endforelse
    </div>

    @if($reports->hasPages())
        <div class="bg-gradient-to-br from-slate-900/80 via-blue-900/40 to-yellow-600/10 backdrop-blur-md rounded-lg border border-slate-700/50 p-4 transition-all duration-500 hover:border-yellow-500/30 hover:shadow-lg hover:shadow-yellow-500/5">
            {{ $reports->links() }}
        </div>
    @endif
</div>
@endsection

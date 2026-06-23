@extends('layouts.app')

@section('page-title', 'Laporan Kerusakan Fasilitas')

@section('content')
<div class="space-y-6">
    <!-- Header with filters -->
    <div class="card p-5 space-y-4 scroll-animate">
        <!-- Status Tabs -->
        <div class="border-b border-gray-200 overflow-x-auto flex">
            <nav class="flex space-x-6 min-w-max text-xs font-semibold uppercase tracking-wider">
                <a href="{{ route('damage-reports.index', array_merge(request()->except('status'), ['status' => ''])) }}" 
                    class="pb-3 border-b-2 px-1 transition-colors cursor-pointer {{ !request('status') ? 'border-primary text-primary' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
                    Semua
                </a>
                @foreach($statuses as $status)
                    <a href="{{ route('damage-reports.index', array_merge(request()->except('status'), ['status' => $status->value])) }}" 
                        class="pb-3 border-b-2 px-1 transition-colors cursor-pointer {{ request('status') === $status->value ? 'border-primary text-primary' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
                        {{ str_replace('_', ' ', $status->value) }}
                    </a>
                @endforeach
            </nav>
        </div>

        <!-- Filter Form -->
        <form action="{{ route('damage-reports.index') }}" method="GET" class="flex flex-wrap items-center gap-3 justify-between">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <div class="flex flex-wrap items-center gap-3">
                <div class="w-full sm:w-64 relative">
                    <input type="text" name="search" placeholder="Cari nomor atau judul laporan..." value="{{ $filters['search'] ?? '' }}" class="input-clean text-sm pl-9 py-2">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <select name="severity" class="select-clean text-sm py-2">
                    <option value="">Semua Tingkat Kerusakan</option>
                    @foreach($severities as $sev)
                        <option value="{{ $sev->value }}" {{ (isset($filters['severity']) && $filters['severity'] === $sev->value) ? 'selected' : '' }}>
                            {{ strtoupper($sev->value) }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary btn-sm cursor-pointer">Filter</button>
                @if(!empty(request()->except('status')))
                    <a href="{{ route('damage-reports.index', ['status' => request('status')]) }}" class="text-xs text-slate-400 hover:text-primary underline cursor-pointer">Reset</a>
                @endif
            </div>

            @if(auth()->user()->isInspector() || auth()->user()->isAdmin())
                <a href="{{ route('damage-reports.create') }}" class="btn btn-accent btn-sm uppercase tracking-wider cursor-pointer">
                    Buat Laporan Baru
                </a>
            @endif
        </form>
    </div>

    <!-- Report Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($reports as $report)
            <div class="card overflow-hidden flex flex-col scroll-animate
                @if($report->severity->value === 'critical' && $report->status->value !== 'completed') border-red-200 ring-1 ring-red-100 @endif">
                
                <!-- Photo -->
                <div class="h-44 bg-slate-100 relative">
                    @if($report->photos->first())
                        <img src="{{ asset('storage/' . $report->photos->first()->photo_path) }}" alt="Foto Kerusakan" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-500">
                            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                    
                    <!-- Badges -->
                    <div class="absolute top-2.5 left-2.5 flex flex-col gap-1.5 z-10">
                        <span class="badge text-[10px] font-bold uppercase text-slate-800
                            @if($report->severity->value === 'low') bg-slate-400
                            @elseif($report->severity->value === 'medium') bg-amber-500
                            @elseif($report->severity->value === 'high') bg-orange-500
                            @else bg-red-600 @endif">
                            {{ $report->severity->value }}
                        </span>
                    </div>
                    <div class="absolute top-2.5 right-2.5 z-10">
                        <span class="badge text-[10px] font-bold uppercase text-slate-800
                            @if($report->status->value === 'draft') bg-slate-400
                            @elseif($report->status->value === 'reported') bg-blue-500
                            @elseif($report->status->value === 'verified') bg-indigo-500
                            @elseif($report->status->value === 'assigned') bg-amber-500
                            @elseif($report->status->value === 'in_progress') bg-orange-500
                            @elseif($report->status->value === 'waiting_verification') bg-yellow-500
                            @else bg-emerald-500 @endif">
                            {{ str_replace('_', ' ', $report->status->value) }}
                        </span>
                    </div>
                </div>

                <!-- Body -->
                <div class="p-5 flex-1 flex flex-col">
                    <span class="block text-[10px] font-mono font-bold tracking-widest text-slate-400 uppercase">{{ $report->report_number }}</span>
                    <h4 class="text-sm font-bold text-slate-800 mt-1 line-clamp-1 hover:text-primary transition-colors">
                        <a href="{{ route('damage-reports.show', $report->id) }}" class="cursor-pointer">{{ $report->title }}</a>
                    </h4>
                    <p class="text-xs text-slate-500 mt-2 line-clamp-2 leading-relaxed flex-1">{{ $report->description }}</p>
                    
                    <div class="mt-4 pt-3 border-t border-gray-100 space-y-1.5 text-[11px]">
                        <div class="flex items-center text-slate-500">
                            <span class="w-16 font-semibold">Fasilitas:</span>
                            <span class="truncate">{{ $report->facility->facility_name }}</span>
                        </div>
                        <div class="flex items-center text-slate-500">
                            <span class="w-16 font-semibold">Lokasi:</span>
                            <span class="truncate">{{ $report->facility->location->name }}</span>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-5 py-3 bg-surface-50 border-t border-gray-100 flex justify-between items-center text-xs">
                    <span class="text-slate-400">Oleh: {{ $report->reporter->name }}</span>
                    <a href="{{ route('damage-reports.show', $report->id) }}" class="font-semibold text-primary hover:underline cursor-pointer">
                        Lihat Detail &rarr;
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full card py-16 text-center text-slate-400 text-sm">
                Tidak ada laporan kerusakan yang ditemukan.
            </div>
        @endforelse
    </div>

    @if($reports->hasPages())
        <div class="card p-4">
            {{ $reports->links() }}
        </div>
    @endif
</div>
@endsection

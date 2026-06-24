@extends('layouts.app')

@section('page-title', 'Surat Perintah Kerja (Work Order)')

@section('content')
<div class="space-y-6">
    <!-- Filters Card -->
    <div class="card p-4 transition-all duration-300 hover:shadow-card-hover">
        <form action="{{ route('work-orders.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <div class="w-full sm:w-64 relative">
                <input type="text" name="search" placeholder="Cari nomor WO atau judul laporan..." value="{{ $filters['search'] ?? '' }}" class="w-full text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 pl-8 py-2">
                <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <select name="status" class="text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 py-2">
                <option value="">Semua Status WO</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->value }}" {{ (isset($filters['status']) && $filters['status'] === $status->value) ? 'selected' : '' }}>
                        {{ strtoupper($status->value) }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="bg-secondary text-slate-800 text-xs font-semibold px-4 py-2 rounded hover:bg-secondary-dark transition-colors">
                Filter
            </button>
            @if(!empty($filters))
                <a href="{{ route('work-orders.index') }}" class="text-xs text-slate-400 hover:text-primary underline">Reset</a>
            @endif
        </form>
    </div>

    <!-- Data List -->
    <div class="card overflow-hidden transition-all duration-300 hover:shadow-card-hover">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900 text-slate-800 font-mono text-xs uppercase">
                        <th class="py-3.5 px-4 font-semibold w-1/6">No. WO</th>
                        <th class="py-3.5 px-4 font-semibold w-1/4">Tugas Perbaikan</th>
                        <th class="py-3.5 px-4 font-semibold w-1/6">Ditugaskan Kepada</th>
                        <th class="py-3.5 px-4 font-semibold w-1/8 text-center">Progres</th>
                        <th class="py-3.5 px-4 font-semibold w-1/6">Batas Waktu (Due)</th>
                        <th class="py-3.5 px-4 font-semibold text-center w-1/8">Status</th>
                        <th class="py-3.5 px-4 font-semibold text-right w-1/8">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50 text-xs">
                    @forelse($workOrders as $wo)
                        <tr class="hover:bg-slate-700/30 transition-colors">
                            <td class="py-3.5 px-4 font-mono font-bold text-slate-800">{{ $wo->wo_number }}</td>
                            <td class="py-3.5 px-4">
                                <span class="block font-semibold text-slate-200 line-clamp-1">{{ $wo->damageReport->title }}</span>
                                <span class="block text-[10px] font-mono text-slate-400 mt-0.5">{{ $wo->damageReport->facility->facility_name }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-500 font-semibold">{{ $wo->assignee->name }}</td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <div class="w-12 bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                        <div class="bg-success h-full" style="width: {{ $wo->progress_percentage }}%"></div>
                                    </div>
                                    <span class="font-mono text-[11px] font-bold text-slate-500">{{ $wo->progress_percentage }}%</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-slate-500">
                                <span class="{{ $wo->isOverdue() ? 'text-red-600 font-bold' : '' }}">
                                    {{ $wo->due_date->format('d M Y') }}
                                </span>
                                @if($wo->isOverdue() && $wo->status->value !== 'completed')
                                    <span class="inline-block bg-red-500/20 text-red-400 text-[10px] font-bold font-sans px-1 rounded ml-1">TELAT</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold tracking-wider uppercase text-slate-800 shadow-3xs
                                    @if($wo->status->value === 'pending') bg-slate-500
                                    @elseif($wo->status->value === 'in_progress') bg-amber-500
                                    @elseif($wo->status->value === 'completed') bg-emerald-600
                                    @else bg-red-600 @endif">
                                    {{ $wo->status->value }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('work-orders.show', $wo->id) }}" class="inline-block text-[11px] font-bold text-secondary hover:underline">
                                    Kelola &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400 font-mono">
                                Data surat perintah kerja tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($workOrders->hasPages())
            <div class="px-5 py-4 border-t border-gray-200 bg-surface-50">
                {{ $workOrders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

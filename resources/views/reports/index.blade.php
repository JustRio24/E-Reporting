@extends('layouts.app')

@section('page-title', 'Laporan Rekapitulasi & Cetak PDF')

@section('content')
<div class="space-y-6">
    <!-- Filter Panel -->
    <div class="card p-6 transition-all duration-300 hover:shadow-card-hover">
        <h3 class="text-xs font-bold uppercase tracking-wider text-yellow-400 font-mono mb-4">Parameter Laporan Rekapitulasi</h3>
        <form action="{{ route('reports.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- Start Date -->
            <div>
                <label for="start_date" class="block text-[11px] font-mono font-bold uppercase text-blue-200 mb-1">Tanggal Mulai</label>
                <input type="date" name="start_date" id="start_date" value="{{ $filters['start_date'] ?? '' }}" class="w-full text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 py-2">
            </div>

            <!-- End Date -->
            <div>
                <label for="end_date" class="block text-[11px] font-mono font-bold uppercase text-blue-200 mb-1">Tanggal Selesai</label>
                <input type="date" name="end_date" id="end_date" value="{{ $filters['end_date'] ?? '' }}" class="w-full text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 py-2">
            </div>

            <!-- Facility -->
            <div>
                <label for="facility_id" class="block text-[11px] font-mono font-bold uppercase text-blue-200 mb-1">Fasilitas</label>
                <select name="facility_id" id="facility_id" class="w-full text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 py-2 font-mono">
                    <option value="">Semua Fasilitas</option>
                    @foreach($facilities as $fac)
                        <option value="{{ $fac->id }}" {{ (isset($filters['facility_id']) && $filters['facility_id'] == $fac->id) ? 'selected' : '' }}>
                            {{ $fac->facility_code }} - {{ $fac->facility_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-[11px] font-mono font-bold uppercase text-blue-200 mb-1">Status Laporan</label>
                <select name="status" id="status" class="w-full text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 py-2 font-mono">
                    <option value="">Semua Status</option>
                    @foreach($statuses as $st)
                        <option value="{{ $st->value }}" {{ (isset($filters['status']) && $filters['status'] === $st->value) ? 'selected' : '' }}>
                            {{ strtoupper(str_replace('_', ' ', $st->value)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Severity -->
            <div>
                <label for="severity" class="block text-[11px] font-mono font-bold uppercase text-blue-200 mb-1">Tingkat Keparahan</label>
                <select name="severity" id="severity" class="w-full text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 py-2 font-mono">
                    <option value="">Semua Tingkat</option>
                    @foreach($severities as $sev)
                        <option value="{{ $sev->value }}" {{ (isset($filters['severity']) && $filters['severity'] === $sev->value) ? 'selected' : '' }}>
                            {{ strtoupper($sev->value) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Form buttons -->
            <div class="md:col-span-5 flex justify-end gap-3 pt-3 border-t border-gray-100">
                <a href="{{ route('reports.index') }}" class="bg-slate-150 text-slate-500 text-xs font-semibold px-4 py-2.5 rounded hover:bg-slate-200">
                    Reset Filter
                </a>
                <button type="submit" class="bg-secondary text-slate-800 text-xs font-bold tracking-wider uppercase px-5 py-2.5 rounded hover:bg-secondary-dark transition-colors">
                    Preview Data
                </button>
                <a href="{{ route('reports.export', $filters) }}" class="bg-primary text-slate-800 text-xs font-bold tracking-wider uppercase px-5 py-2.5 rounded hover:bg-primary-dark transition-colors flex items-center shadow-sm">
                    <span class="mr-2">💾</span> Ekspor PDF
                </a>
            </div>
        </form>
    </div>

    <!-- Preview Results Card -->
    <div class="card overflow-hidden transition-all duration-300 hover:shadow-card-hover">
        <div class="px-5 py-4 border-b border-gray-200 bg-surface-50 flex justify-between items-center">
            <h3 class="text-sm font-bold text-yellow-400 uppercase font-mono tracking-wider">Hasil Rekapitulasi ({{ $reports->count() }} Data)</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900 text-white font-mono text-xs uppercase">
                        <th class="py-3.5 px-4 font-semibold w-1/8">No. Laporan</th>
                        <th class="py-3.5 px-4 font-semibold w-1/8">Tanggal</th>
                        <th class="py-3.5 px-4 font-semibold w-1/4">Fasilitas</th>
                        <th class="py-3.5 px-4 font-semibold w-1/4">Judul Kerusakan</th>
                        <th class="py-3.5 px-4 font-semibold w-1/10 text-center">Keparahan</th>
                        <th class="py-3.5 px-4 font-semibold w-1/8 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50 text-xs">
                    @forelse($reports as $rep)
                        <tr class="hover:bg-slate-700/30 transition-colors">
                            <td class="py-3.5 px-4 font-mono font-bold text-slate-800 select-all">{{ $rep->report_number }}</td>
                            <td class="py-3.5 px-4 font-mono text-slate-550">{{ $rep->created_at->format('d/m/Y') }}</td>
                            <td class="py-3.5 px-4">
                                <span class="block font-semibold text-slate-200">{{ $rep->facility->facility_name }}</span>
                                <span class="block text-[10px] font-mono text-blue-200 mt-0.5">{{ $rep->facility->facility_code }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-500 leading-normal">{{ $rep->title }}</td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-mono font-bold tracking-wider uppercase text-slate-800
                                    @if($rep->severity->value === 'low') bg-slate-500
                                    @elseif($rep->severity->value === 'medium') bg-amber-505
                                    @elseif($rep->severity->value === 'high') bg-orange-600
                                    @else bg-red-600 @endif">
                                    {{ $rep->severity->value }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold tracking-wider uppercase text-slate-800
                                    @if($rep->status->value === 'draft') bg-slate-400
                                    @elseif($rep->status->value === 'reported') bg-blue-600
                                    @elseif($rep->status->value === 'verified') bg-indigo-500
                                    @elseif($rep->status->value === 'assigned') bg-amber-500
                                    @elseif($rep->status->value === 'in_progress') bg-orange-500
                                    @elseif($rep->status->value === 'waiting_verification') bg-yellow-500
                                    @else bg-emerald-600 @endif">
                                    {{ str_replace('_', ' ', $rep->status->value) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 font-mono">
                                Tidak ada data rekapitulasi yang memenuhi parameter filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

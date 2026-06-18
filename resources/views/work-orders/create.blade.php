@extends('layouts.app')

@section('page-title', 'Buat Perintah Kerja (Work Order)')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Referenced Damage Report Summary Card -->
    <div class="bg-gradient-to-br from-slate-900/80 via-blue-900/40 to-yellow-600/10 backdrop-blur-md rounded-lg border border-slate-700/50 shadow-sm overflow-hidden p-5 flex flex-col sm:flex-row gap-4 transition-all duration-500 hover:border-yellow-500/30 hover:shadow-lg hover:shadow-yellow-500/5">
        @if($damageReport->photos->first())
            <div class="w-full sm:w-28 h-20 rounded overflow-hidden border border-slate-100 flex-shrink-0">
                <img src="{{ asset('storage/' . $damageReport->photos->first()->photo_path) }}" alt="Kerusakan" class="w-full h-full object-cover">
            </div>
        @endif
        <div class="flex-1">
            <span class="text-4xs font-mono font-bold text-slate-400 uppercase">{{ $damageReport->report_number }}</span>
            <h4 class="text-xs font-bold text-slate-905 mt-0.5">{{ $damageReport->title }}</h4>
            <p class="text-2xs text-slate-400 line-clamp-1 mt-1 leading-normal">{{ $damageReport->description }}</p>
            <div class="mt-2.5 flex gap-4 text-3xs font-mono text-slate-400">
                <div>Fasilitas: <span class="font-bold text-slate-300">{{ $damageReport->facility->facility_name }}</span></div>
                <div>Tingkat: <span class="font-bold text-red-650 uppercase">{{ $damageReport->severity->value }}</span></div>
            </div>
        </div>
    </div>

    <!-- WO Creation Form Card -->
    <div class="bg-gradient-to-br from-slate-900/80 via-blue-900/40 to-yellow-600/10 backdrop-blur-md rounded-lg border border-slate-700/50 shadow-sm overflow-hidden transition-all duration-500 hover:border-yellow-500/30 hover:shadow-lg hover:shadow-yellow-500/5">
        <div class="px-6 py-4 border-b border-slate-700/50 bg-slate-900/40 flex justify-between items-center">
            <h3 class="text-sm font-bold text-yellow-400 uppercase font-mono tracking-wider">Formulir Penugasan Kerja</h3>
            <a href="{{ route('damage-reports.show', $damageReport->id) }}" class="text-xs text-slate-400 hover:text-slate-200">
                &larr; Batal
            </a>
        </div>

        <form action="{{ route('work-orders.store') }}" method="POST" class="p-6 space-y-5">
            @csrf
            <!-- Pass referenced report ID -->
            <input type="hidden" name="damage_report_id" value="{{ $damageReport->id }}">

            <!-- Assignee -->
            <div>
                <label for="assigned_to" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Pilih Petugas (Maintenance Team)</label>
                <select name="assigned_to" id="assigned_to" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2" required>
                    <option value="" disabled selected>Pilih petugas...</option>
                    @foreach($maintenanceUsers as $mUser)
                        <option value="{{ $mUser->id }}" {{ old('assigned_to') == $mUser->id ? 'selected' : '' }}>
                            {{ $mUser->name }} ({{ $mUser->phone ?? 'No telp -' }})
                        </option>
                    @endforeach
                </select>
                @error('assigned_to')
                    <p class="text-2xs text-red-650 mt-1 font-mono">{{ $message }}</p>
                @enderror
            </div>

            <!-- Due Date -->
            <div>
                <label for="due_date" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Batas Waktu Penyelesaian (Due Date)</label>
                <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2" required>
                @error('due_date')
                    <p class="text-2xs text-red-650 mt-1 font-mono">{{ $message }}</p>
                @enderror
            </div>

            <!-- Instruction Notes -->
            <div>
                <label for="notes" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Catatan Instruksi Perbaikan</label>
                <textarea name="notes" id="notes" rows="4" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2" placeholder="Tuliskan petunjuk teknis perbaikan, perkiraan alokasi material, standar keselamatan kerja (K3), atau instruksi khusus lainnya...">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-2xs text-red-650 mt-1 font-mono">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="bg-primary text-white text-xs font-bold tracking-wider uppercase px-6 py-3 rounded hover:bg-primary-dark transition-colors">
                    Tugaskan Pekerjaan (WO)
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('page-title', 'Buat Perintah Kerja (Work Order)')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Referenced Damage Report Summary Card -->
    <div class="card overflow-hidden p-5 flex flex-col sm:flex-row gap-4 transition-all duration-300 hover:shadow-card-hover">
        @if($damageReport->photos->first())
            <div class="w-full sm:w-28 h-20 rounded overflow-hidden border border-gray-100 flex-shrink-0">
                <img src="{{ asset('storage/' . $damageReport->photos->first()->photo_path) }}" alt="Kerusakan" class="w-full h-full object-cover">
            </div>
        @endif
        <div class="flex-1">
            <span class="text-[10px] font-mono font-bold text-slate-400 uppercase">{{ $damageReport->report_number }}</span>
            <h4 class="text-xs font-bold text-slate-905 mt-0.5">{{ $damageReport->title }}</h4>
            <p class="text-xs text-slate-400 line-clamp-1 mt-1 leading-normal">{{ $damageReport->description }}</p>
            <div class="mt-2.5 flex gap-4 text-[11px] font-mono text-slate-400">
                <div>Fasilitas: <span class="font-bold text-slate-500">{{ $damageReport->facility->facility_name }}</span></div>
                <div>Tingkat: <span class="font-bold text-red-600 uppercase">{{ $damageReport->severity->value }}</span></div>
            </div>
        </div>
    </div>

    <!-- WO Creation Form Card -->
    <div class="card overflow-hidden transition-all duration-300 hover:shadow-card-hover">
        <div class="px-6 py-4 border-b border-gray-200 bg-surface-50 flex justify-between items-center">
            <h3 class="text-sm font-bold text-yellow-400 uppercase font-mono tracking-wider">Formulir Penugasan Kerja</h3>
            <a href="{{ route('damage-reports.show', $damageReport->id) }}" class="text-xs text-slate-400 hover:text-primary">
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
                <select name="assigned_to" id="assigned_to" class="w-full text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 py-2" required>
                    <option value="" disabled selected>Pilih petugas...</option>
                    @foreach($maintenanceUsers as $mUser)
                        <option value="{{ $mUser->id }}" {{ old('assigned_to') == $mUser->id ? 'selected' : '' }}>
                            {{ $mUser->name }} ({{ $mUser->phone ?? 'No telp -' }})
                        </option>
                    @endforeach
                </select>
                @error('assigned_to')
                    <p class="text-xs text-red-600 mt-1 font-mono">{{ $message }}</p>
                @enderror
            </div>

            <!-- Due Date -->
            <div>
                <label for="due_date" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Batas Waktu Penyelesaian (Due Date)</label>
                <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}" class="w-full text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 py-2" required>
                @error('due_date')
                    <p class="text-xs text-red-600 mt-1 font-mono">{{ $message }}</p>
                @enderror
            </div>

            <!-- Instruction Notes -->
            <div>
                <label for="notes" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Catatan Instruksi Perbaikan</label>
                <textarea name="notes" id="notes" rows="4" class="w-full text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 py-2" placeholder="Tuliskan petunjuk teknis perbaikan, perkiraan alokasi material, standar keselamatan kerja (K3), atau instruksi khusus lainnya...">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-xs text-red-600 mt-1 font-mono">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <div class="pt-4 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-primary text-slate-800 text-xs font-bold tracking-wider uppercase px-6 py-3 rounded hover:bg-primary-dark transition-colors">
                    Tugaskan Pekerjaan (WO)
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

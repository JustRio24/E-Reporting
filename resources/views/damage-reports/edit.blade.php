@extends('layouts.app')

@section('page-title', 'Edit Laporan Kerusakan')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
            <h3 class="text-sm font-bold text-slate-800 uppercase font-mono tracking-wider">Edit Laporan: {{ $report->report_number }}</h3>
            <a href="{{ route('damage-reports.show', $report->id) }}" class="text-xs text-slate-500 hover:text-slate-800">
                &larr; Batal & Kembali
            </a>
        </div>

        <form action="{{ route('damage-reports.update', $report->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div>
                <label for="title" class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1">Judul Laporan / Kerusakan</label>
                <input type="text" name="title" id="title" value="{{ old('title', $report->title) }}" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2" required>
                @error('title')
                    <p class="text-2xs text-red-650 mt-1 font-mono">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Facility -->
                <div class="sm:col-span-1">
                    <label for="facility_id" class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1">Fasilitas Terkait</label>
                    <select name="facility_id" id="facility_id" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2" required>
                        @foreach($facilities as $fac)
                            <option value="{{ $fac->id }}" {{ old('facility_id', $report->facility_id) == $fac->id ? 'selected' : '' }}>
                                {{ $fac->facility_code }} - {{ $fac->facility_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('facility_id')
                        <p class="text-2xs text-red-650 mt-1 font-mono">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Damage Category -->
                <div class="sm:col-span-1">
                    <label for="damage_category_id" class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1">Kategori Kerusakan</label>
                    <select name="damage_category_id" id="damage_category_id" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('damage_category_id', $report->damage_category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('damage_category_id')
                        <p class="text-2xs text-red-650 mt-1 font-mono">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Severity -->
                <div class="sm:col-span-1">
                    <label for="severity" class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1">Tingkat Keparahan</label>
                    <select name="severity" id="severity" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2" required>
                        @foreach($severities as $sev)
                            <option value="{{ $sev->value }}" {{ old('severity', $report->severity->value) === $sev->value ? 'selected' : '' }}>
                                {{ strtoupper($sev->value) }}
                            </option>
                        @endforeach
                    </select>
                    @error('severity')
                        <p class="text-2xs text-red-650 mt-1 font-mono">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1">Deskripsi Kerusakan</label>
                <textarea name="description" id="description" rows="5" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2" required>{{ old('description', $report->description) }}</textarea>
                @error('description')
                    <p class="text-2xs text-red-650 mt-1 font-mono">{{ $message }}</p>
                @enderror
            </div>

            <!-- Current Photos -->
            <div>
                <span class="block text-xs font-bold uppercase tracking-wider text-slate-655 mb-2">Foto Saat Ini</span>
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                    @foreach($report->photos as $photo)
                        <div class="relative h-20 rounded overflow-hidden border border-slate-200">
                            <img src="{{ asset('storage/' . $photo->photo_path) }}" alt="Foto" class="w-full h-full object-cover">
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Upload More Photos -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1">Unggah Foto Baru (Menambahkan foto saat ini, Maksimal 5 foto)</label>
                <input id="photos" name="photos[]" type="file" class="w-full text-xs" multiple accept="image/*">
                @error('photos')
                    <p class="text-2xs text-red-650 mt-1 font-mono">{{ $message }}</p>
                @enderror
            </div>

            <!-- Coordinates -->
            <div class="bg-slate-50 p-4 rounded border border-slate-200">
                <span class="block text-xs font-mono font-bold text-slate-600 mb-2">Override Koordinat GIS (Opsional)</span>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="latitude" class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1">Latitude</label>
                        <input type="text" name="latitude" id="latitude" value="{{ old('latitude', $report->latitude) }}" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2 font-mono">
                    </div>

                    <div>
                        <label for="longitude" class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1">Longitude</label>
                        <input type="text" name="longitude" id="longitude" value="{{ old('longitude', $report->longitude) }}" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2 font-mono">
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="bg-primary text-white text-xs font-bold tracking-wider uppercase px-6 py-3 rounded hover:bg-primary-dark transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

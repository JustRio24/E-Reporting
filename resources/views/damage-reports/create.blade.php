@extends('layouts.app')

@section('page-title', 'Buat Laporan Kerusakan')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
            <h3 class="text-sm font-bold text-slate-800 uppercase font-mono tracking-wider">Formulir Laporan Kerusakan</h3>
            <a href="{{ route('damage-reports.index') }}" class="text-xs text-slate-500 hover:text-slate-800">
                &larr; Kembali
            </a>
        </div>

        <form action="{{ route('damage-reports.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf

            <!-- Title -->
            <div>
                <label for="title" class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1">Judul Laporan / Kerusakan</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2" placeholder="Contoh: Ban Conveyor Sobek di Jalur CVY-01A" required>
                @error('title')
                    <p class="text-2xs text-red-650 mt-1 font-mono">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Facility -->
                <div class="sm:col-span-1">
                    <label for="facility_id" class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1">Fasilitas Terkait</label>
                    <select name="facility_id" id="facility_id" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2" required>
                        <option value="" disabled selected>Pilih fasilitas...</option>
                        @foreach($facilities as $fac)
                            <option value="{{ $fac->id }}" {{ old('facility_id') == $fac->id ? 'selected' : '' }}>
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
                        <option value="" disabled selected>Pilih kategori...</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('damage_category_id') == $cat->id ? 'selected' : '' }}>
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
                        <option value="" disabled selected>Pilih keparahan...</option>
                        @foreach($severities as $sev)
                            <option value="{{ $sev->value }}" {{ old('severity') === $sev->value ? 'selected' : '' }}>
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
                <textarea name="description" id="description" rows="5" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2" placeholder="Tuliskan secara lengkap kondisi kerusakan, kronologi kejadian (jika ada), dampak operasi, dan kebutuhan penanganan darurat..." required>{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-2xs text-red-650 mt-1 font-mono">{{ $message }}</p>
                @enderror
            </div>

            <!-- Photos -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1">Foto Kerusakan (RULE-007: Min 1, Max 5, Max 4MB/foto)</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-xs text-slate-600">
                            <label for="photos" class="relative cursor-pointer bg-white rounded font-semibold text-secondary hover:text-secondary-dark focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-secondary">
                                <span>Pilih beberapa gambar</span>
                                <input id="photos" name="photos[]" type="file" class="sr-only" multiple accept="image/*" required>
                            </label>
                        </div>
                        <p class="text-3xs text-slate-400 font-mono">PNG, JPG, JPEG hingga 4MB</p>
                        <p class="text-3xs text-slate-500 font-semibold" id="file-count-label"></p>
                    </div>
                </div>
                @error('photos')
                    <p class="text-2xs text-red-650 mt-1 font-mono">{{ $message }}</p>
                @enderror
                @error('photos.*')
                    <p class="text-2xs text-red-650 mt-1 font-mono">{{ $message }}</p>
                @enderror
            </div>

            <!-- Coordinates Override -->
            <div class="bg-slate-50 p-4 rounded border border-slate-200">
                <span class="block text-xs font-mono font-bold text-slate-600 mb-2">Override Koordinat GIS (Opsional - default mewarisi koordinat Fasilitas)</span>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="latitude" class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1">Latitude</label>
                        <input type="text" name="latitude" id="latitude" value="{{ old('latitude') }}" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2 font-mono" placeholder="Gunakan koordinat kustom jika letak persis berbeda...">
                        @error('latitude')
                            <p class="text-2xs text-red-650 mt-1 font-mono">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="longitude" class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1">Longitude</label>
                        <input type="text" name="longitude" id="longitude" value="{{ old('longitude') }}" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2 font-mono" placeholder="Gunakan koordinat kustom jika letak persis berbeda...">
                        @error('longitude')
                            <p class="text-2xs text-red-650 mt-1 font-mono">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Submit buttons -->
            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <!-- Save as Draft -->
                <button type="submit" name="action" value="draft" class="bg-slate-200 text-slate-800 text-xs font-bold tracking-wider uppercase px-4 py-3 rounded hover:bg-slate-300 transition-colors">
                    Simpan Sebagai Draft
                </button>
                
                <!-- Send/Report -->
                <button type="submit" name="action" value="submit" class="bg-primary text-white text-xs font-bold tracking-wider uppercase px-6 py-3 rounded hover:bg-primary-dark transition-colors">
                    Kirim Laporan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('photos');
        const fileCountLabel = document.getElementById('file-count-label');

        fileInput.addEventListener('change', function() {
            const files = fileInput.files;
            if (files.length > 0) {
                fileCountLabel.textContent = files.length + " gambar terpilih.";
            } else {
                fileCountLabel.textContent = "";
            }
        });
    });
</script>
@endpush

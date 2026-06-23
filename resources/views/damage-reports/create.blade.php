@extends('layouts.app')

@section('page-title', 'Buat Laporan Kerusakan')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="card overflow-hidden transition-all duration-300 hover:shadow-card-hover">
        <div class="px-6 py-4 border-b border-gray-200 bg-surface-50 flex justify-between items-center">
            <h3 class="text-sm font-bold text-yellow-400 uppercase font-mono tracking-wider">Formulir Laporan Kerusakan</h3>
            <a href="{{ route('damage-reports.index') }}" class="text-xs text-slate-400 hover:text-primary">
                &larr; Kembali
            </a>
        </div>

        <form action="{{ route('damage-reports.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf

            <!-- Title -->
            <div>
                <label for="title" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Judul Laporan / Kerusakan</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" class="w-full text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 py-2" placeholder="Contoh: Ban Conveyor Sobek di Jalur CVY-01A" required>
                @error('title')
                    <p class="text-xs text-red-600 mt-1 font-mono">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Facility -->
                <div class="sm:col-span-1">
                    <label for="facility_id" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Fasilitas Terkait</label>
                    <select name="facility_id" id="facility_id" class="w-full text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 py-2" required>
                        <option value="" disabled selected>Pilih fasilitas...</option>
                        @foreach($facilities as $fac)
                            <option value="{{ $fac->id }}" {{ old('facility_id') == $fac->id ? 'selected' : '' }}>
                                {{ $fac->facility_code }} - {{ $fac->facility_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('facility_id')
                        <p class="text-xs text-red-600 mt-1 font-mono">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Damage Category -->
                <div class="sm:col-span-1">
                    <label for="damage_category_id" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Kategori Kerusakan</label>
                    <select name="damage_category_id" id="damage_category_id" class="w-full text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 py-2" required>
                        <option value="" disabled selected>Pilih kategori...</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('damage_category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('damage_category_id')
                        <p class="text-xs text-red-600 mt-1 font-mono">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Severity -->
                <div class="sm:col-span-1">
                    <label for="severity" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Tingkat Keparahan</label>
                    <select name="severity" id="severity" class="w-full text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 py-2" required>
                        <option value="" disabled selected>Pilih keparahan...</option>
                        @foreach($severities as $sev)
                            <option value="{{ $sev->value }}" {{ old('severity') === $sev->value ? 'selected' : '' }}>
                                {{ strtoupper($sev->value) }}
                            </option>
                        @endforeach
                    </select>
                    @error('severity')
                        <p class="text-xs text-red-600 mt-1 font-mono">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Deskripsi Kerusakan</label>
                <textarea name="description" id="description" rows="5" class="w-full text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 py-2" placeholder="Tuliskan secara lengkap kondisi kerusakan, kronologi kejadian (jika ada), dampak operasi, dan kebutuhan penanganan darurat..." required>{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-xs text-red-600 mt-1 font-mono">{{ $message }}</p>
                @enderror
            </div>

            <!-- Photos -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Foto Kerusakan (RULE-007: Min 1, Max 5, Max 4MB/foto)</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-600/50 border-dashed rounded">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-xs text-slate-500">
                            <label for="photos" class="relative cursor-pointer bg-white rounded font-semibold text-secondary hover:text-secondary-dark focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-secondary">
                                <span>Pilih beberapa gambar</span>
                                <input id="photos" name="photos[]" type="file" class="sr-only" multiple accept="image/*" required>
                            </label>
                        </div>
                        <p class="text-[11px] text-slate-400 font-mono">PNG, JPG, JPEG hingga 4MB</p>
                        <p class="text-[11px] text-slate-400 font-semibold" id="file-count-label"></p>
                    </div>
                </div>
                @error('photos')
                    <p class="text-xs text-red-600 mt-1 font-mono">{{ $message }}</p>
                @enderror
                @error('photos.*')
                    <p class="text-xs text-red-600 mt-1 font-mono">{{ $message }}</p>
                @enderror
            </div>

            <!-- Coordinates Override -->
            <div class="bg-surface-50 p-4 rounded border border-gray-200">
                <span class="block text-xs font-mono font-bold text-slate-900 mb-2">Pilih Lokasi Kerusakan pada Peta (Opsional)</span>
                <p class="text-xs text-slate-500 mb-3">Klik pada peta untuk menempatkan pin. Koordinat akan terisi secara otomatis.</p>
                
                <div id="mapPicker" class="w-full h-64 rounded border border-slate-300 mb-4 z-10"></div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="latitude" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Latitude</label>
                        <input type="text" name="latitude" id="latitude" value="{{ old('latitude') }}" class="w-full text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 py-2 font-mono" placeholder="Gunakan koordinat kustom jika letak persis berbeda...">
                        @error('latitude')
                            <p class="text-xs text-red-600 mt-1 font-mono">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="longitude" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Longitude</label>
                        <input type="text" name="longitude" id="longitude" value="{{ old('longitude') }}" class="w-full text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 py-2 font-mono" placeholder="Gunakan koordinat kustom jika letak persis berbeda...">
                        @error('longitude')
                            <p class="text-xs text-red-600 mt-1 font-mono">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Submit buttons -->
            <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                <!-- Save as Draft -->
                <button type="submit" name="action" value="draft" class="bg-slate-200 text-slate-200 text-xs font-bold tracking-wider uppercase px-4 py-3 rounded hover:bg-slate-300 transition-colors">
                    Simpan Sebagai Draft
                </button>
                
                <!-- Send/Report -->
                <button type="submit" name="action" value="submit" class="bg-primary text-slate-800 text-xs font-bold tracking-wider uppercase px-6 py-3 rounded hover:bg-primary-dark transition-colors">
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
        // File Upload preview
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

        // Map Picker Initialization
        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');
        
        // Default to Palembang or existing input values
        let startLat = latInput.value ? parseFloat(latInput.value) : -3.0182;
        let startLng = lngInput.value ? parseFloat(lngInput.value) : 104.7493;
        let defaultZoom = latInput.value ? 16 : 15;

        const ptbaBounds = [
            [-3.0330, 104.7340], // SouthWest
            [-3.0030, 104.7640]  // NorthEast
        ];

        const map = L.map('mapPicker', {
            maxBounds: ptbaBounds,
            maxBoundsViscosity: 1.0,
            minZoom: 15
        }).setView([startLat, startLng], defaultZoom > 15 ? defaultZoom : 16);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        let marker;

        // If there's an existing value (e.g. from validation error), place the marker
        if (latInput.value && lngInput.value) {
            marker = L.marker([startLat, startLng], {draggable: true}).addTo(map);
            
            marker.on('dragend', function (e) {
                const position = marker.getLatLng();
                latInput.value = position.lat.toFixed(6);
                lngInput.value = position.lng.toFixed(6);
            });
        }

        // Click on map to place/move marker
        map.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;

            if (marker) {
                marker.setLatLng(e.latlng);
            } else {
                marker = L.marker(e.latlng, {draggable: true}).addTo(map);
                marker.on('dragend', function (event) {
                    const position = marker.getLatLng();
                    latInput.value = position.lat.toFixed(6);
                    lngInput.value = position.lng.toFixed(6);
                });
            }

            // Update inputs
            latInput.value = lat.toFixed(6);
            lngInput.value = lng.toFixed(6);
        });
    });
</script>
@endpush

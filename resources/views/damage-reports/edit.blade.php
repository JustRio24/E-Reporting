@extends('layouts.app')

@section('page-title', 'Edit Laporan Kerusakan')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="card overflow-hidden transition-all duration-300 hover:shadow-card-hover">
        <div class="px-6 py-4 border-b border-gray-200 bg-surface-50 flex justify-between items-center">
            <h3 class="text-sm font-bold text-yellow-400 uppercase font-mono tracking-wider">Edit Laporan: {{ $report->report_number }}</h3>
            <a href="{{ route('damage-reports.show', $report->id) }}" class="text-xs text-slate-400 hover:text-primary">
                &larr; Batal & Kembali
            </a>
        </div>

        <form action="{{ route('damage-reports.update', $report->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div>
                <label for="title" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Judul Laporan / Kerusakan</label>
                <input type="text" name="title" id="title" value="{{ old('title', $report->title) }}" class="w-full text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 py-2" required>
                @error('title')
                    <p class="text-xs text-red-600 mt-1 font-mono">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Facility -->
                <div class="sm:col-span-1">
                    <label for="facility_id" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Fasilitas Terkait</label>
                    <select name="facility_id" id="facility_id" class="w-full text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 py-2" required>
                        @foreach($facilities as $fac)
                            <option value="{{ $fac->id }}" {{ old('facility_id', $report->facility_id) == $fac->id ? 'selected' : '' }}>
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
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('damage_category_id', $report->damage_category_id) == $cat->id ? 'selected' : '' }}>
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
                        @foreach($severities as $sev)
                            <option value="{{ $sev->value }}" {{ old('severity', $report->severity->value) === $sev->value ? 'selected' : '' }}>
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
                <textarea name="description" id="description" rows="5" class="w-full text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 py-2" required>{{ old('description', $report->description) }}</textarea>
                @error('description')
                    <p class="text-xs text-red-600 mt-1 font-mono">{{ $message }}</p>
                @enderror
            </div>

            <!-- Current Photos -->
            <div>
                <span class="block text-xs font-bold uppercase tracking-wider text-slate-655 mb-2">Foto Saat Ini</span>
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                    @foreach($report->photos as $photo)
                        <div class="relative h-20 rounded overflow-hidden border border-gray-200">
                            <img src="{{ asset('storage/' . $photo->photo_path) }}" alt="Foto" class="w-full h-full object-cover">
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Upload More Photos -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Unggah Foto Baru (Menambahkan foto saat ini, Maksimal 5 foto)</label>
                <input id="photos" name="photos[]" type="file" class="w-full text-xs" multiple accept="image/*">
                @error('photos')
                    <p class="text-xs text-red-600 mt-1 font-mono">{{ $message }}</p>
                @enderror
            </div>

            <!-- Coordinates -->
            <div class="bg-surface-50 p-4 rounded border border-gray-200">
                <span class="block text-xs font-mono font-bold text-slate-900 mb-2">Pilih Lokasi Kerusakan pada Peta (Opsional)</span>
                <p class="text-xs text-slate-500 mb-3">Geser pin atau klik pada peta untuk mengubah koordinat.</p>
                
                <div id="mapPicker" class="w-full h-64 rounded border border-slate-300 mb-4 z-10"></div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="latitude" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Latitude</label>
                        <input type="text" name="latitude" id="latitude" value="{{ old('latitude', $report->latitude) }}" class="w-full text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 py-2 font-mono">
                    </div>

                    <div>
                        <label for="longitude" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Longitude</label>
                        <input type="text" name="longitude" id="longitude" value="{{ old('longitude', $report->longitude) }}" class="w-full text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 py-2 font-mono">
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="pt-4 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-primary text-slate-800 text-xs font-bold tracking-wider uppercase px-6 py-3 rounded hover:bg-primary-dark transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
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

        // If there's an existing value (e.g. from validation error or existing record), place the marker
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

@extends('layouts.app')

@section('page-title', 'Edit Fasilitas')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card overflow-hidden transition-all duration-300 hover:shadow-card-hover">
        <div class="px-6 py-4 border-b border-gray-200 bg-surface-50 flex justify-between items-center">
            <h3 class="text-sm font-bold text-yellow-400 uppercase font-mono tracking-wider">Edit Fasilitas: {{ $facility->facility_code }}</h3>
            <a href="{{ route('facilities.index') }}" class="text-xs text-slate-400 hover:text-primary">
                &larr; Kembali
            </a>
        </div>

        <form action="{{ route('facilities.update', $facility->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Facility Code -->
                <div>
                    <label for="facility_code" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Kode Fasilitas</label>
                    <input type="text" name="facility_code" id="facility_code" value="{{ old('facility_code', $facility->facility_code) }}" class="w-full text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 py-2 font-mono" required>
                    @error('facility_code')
                        <p class="text-xs text-red-600 mt-1 font-mono">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Facility Name -->
                <div>
                    <label for="facility_name" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Nama Fasilitas</label>
                    <input type="text" name="facility_name" id="facility_name" value="{{ old('facility_name', $facility->facility_name) }}" class="w-full text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 py-2" required>
                    @error('facility_name')
                        <p class="text-xs text-red-600 mt-1 font-mono">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Facility Category -->
                <div>
                    <label for="facility_category_id" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Kategori Fasilitas</label>
                    <select name="facility_category_id" id="facility_category_id" class="w-full text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 py-2" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('facility_category_id', $facility->facility_category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('facility_category_id')
                        <p class="text-xs text-red-600 mt-1 font-mono">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Location -->
                <div>
                    <label for="location_id" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Lokasi / Area</label>
                    <select name="location_id" id="location_id" class="w-full text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 py-2" required>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" {{ old('location_id', $facility->location_id) == $loc->id ? 'selected' : '' }}>
                                {{ $loc->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('location_id')
                        <p class="text-xs text-red-600 mt-1 font-mono">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Deskripsi Fasilitas</label>
                <textarea name="description" id="description" rows="3" class="w-full text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 py-2">{{ old('description', $facility->description) }}</textarea>
                @error('description')
                    <p class="text-xs text-red-600 mt-1 font-mono">{{ $message }}</p>
                @enderror
            </div>

            <!-- Photo Upload -->
            <div>
                <label for="photo" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Foto Fasilitas (Opsional)</label>
                
                @if($facility->photo_path)
                    <div class="mb-3">
                        <div class="text-[10px] text-slate-500 mb-2">Foto saat ini:</div>
                        <img src="{{ asset('storage/' . $facility->photo_path) }}" alt="{{ $facility->facility_name }}" class="w-40 h-40 object-cover rounded-lg border border-gray-200">
                    </div>
                @endif

                <div class="flex items-center gap-4">
                    <label class="flex-1 cursor-pointer">
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-primary transition-colors">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="text-xs text-gray-600 mt-2">Klik untuk upload foto baru</div>
                            <div class="text-[10px] text-gray-500 mt-1">JPG, PNG, max 10MB (auto compress ke 2MB)</div>
                        </div>
                        <input type="file" name="photo" id="photo" class="hidden" accept="image/*" onchange="previewImage(this)">
                    </label>
                    <div id="image-preview" class="w-32 h-32 hidden">
                        <img id="preview-img" class="w-full h-full object-cover rounded-lg border border-gray-200" src="" alt="Preview">
                        <button type="button" onclick="removeImage()" class="mt-1 text-xs text-red-600 hover:text-red-700 w-full">Hapus</button>
                    </div>
                </div>
                @error('photo')
                    <p class="text-xs text-red-600 mt-1 font-mono">{{ $message }}</p>
                @enderror
            </div>

            <!-- Coordinates -->
            <div class="bg-surface-50 p-4 rounded border border-gray-200">
                <span class="block text-xs font-mono font-bold text-slate-500 mb-2">Koordinat GIS Fasilitas (Opsional)</span>
                
                <p class="text-xs text-slate-500 mb-3">Geser pin atau klik pada peta untuk mengubah koordinat.</p>
                <div id="mapPicker" class="w-full h-64 rounded border border-slate-300 mb-4 z-10"></div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="latitude" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Latitude</label>
                        <input type="text" name="latitude" id="latitude" value="{{ old('latitude', $facility->latitude) }}" class="w-full text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 py-2 font-mono">
                        @error('latitude')
                            <p class="text-xs text-red-600 mt-1 font-mono">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="longitude" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Longitude</label>
                        <input type="text" name="longitude" id="longitude" value="{{ old('longitude', $facility->longitude) }}" class="w-full text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 py-2 font-mono">
                        @error('longitude')
                            <p class="text-xs text-red-600 mt-1 font-mono">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

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
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('image-preview').classList.remove('hidden');
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeImage() {
        document.getElementById('photo').value = '';
        document.getElementById('image-preview').classList.add('hidden');
        document.getElementById('preview-img').src = '';
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Map Picker Initialization
        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');
        
        // Default to Kertapati Port or existing values
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

        // If there's an existing value, place the marker
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

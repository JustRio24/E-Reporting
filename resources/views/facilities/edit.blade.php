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

        <form action="{{ route('facilities.update', $facility->id) }}" method="POST" class="p-6 space-y-5">
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

            <!-- Coordinates -->
            <div class="bg-surface-50 p-4 rounded border border-gray-200">
                <span class="block text-xs font-mono font-bold text-slate-500 mb-2">Koordinat GIS Fasilitas (Opsional)</span>
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

@extends('layouts.app')

@section('page-title', 'Tambah Fasilitas Baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
            <h3 class="text-sm font-bold text-slate-800 uppercase font-mono tracking-wider">Formulir Fasilitas Pelabuhan</h3>
            <a href="{{ route('facilities.index') }}" class="text-xs text-slate-500 hover:text-slate-800">
                &larr; Kembali
            </a>
        </div>

        <form action="{{ route('facilities.store') }}" method="POST" class="p-6 space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Facility Code -->
                <div>
                    <label for="facility_code" class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1">Kode Fasilitas</label>
                    <input type="text" name="facility_code" id="facility_code" value="{{ old('facility_code') }}" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2 font-mono" placeholder="Contoh: FAC-DMG-01" required>
                    @error('facility_code')
                        <p class="text-2xs text-red-650 mt-1 font-mono">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Facility Name -->
                <div>
                    <label for="facility_name" class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1">Nama Fasilitas</label>
                    <input type="text" name="facility_name" id="facility_name" value="{{ old('facility_name') }}" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2" placeholder="Contoh: Jetty Loading Arm 1" required>
                    @error('facility_name')
                        <p class="text-2xs text-red-650 mt-1 font-mono">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Facility Category -->
                <div>
                    <label for="facility_category_id" class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1">Kategori Fasilitas</label>
                    <select name="facility_category_id" id="facility_category_id" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2" required>
                        <option value="" disabled selected>Pilih kategori...</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('facility_category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('facility_category_id')
                        <p class="text-2xs text-red-650 mt-1 font-mono">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Location -->
                <div>
                    <label for="location_id" class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1">Lokasi / Area</label>
                    <select name="location_id" id="location_id" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2" required>
                        <option value="" disabled selected>Pilih lokasi...</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" {{ old('location_id') == $loc->id ? 'selected' : '' }}>
                                {{ $loc->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('location_id')
                        <p class="text-2xs text-red-650 mt-1 font-mono">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1">Deskripsi Fasilitas</label>
                <textarea name="description" id="description" rows="3" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2" placeholder="Jelaskan mengenai detail, spesifikasi, atau identitas fisik fasilitas ini...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-2xs text-red-650 mt-1 font-mono">{{ $message }}</p>
                @enderror
            </div>

            <!-- Coordinates -->
            <div class="bg-slate-50 p-4 rounded border border-slate-200">
                <span class="block text-xs font-mono font-bold text-slate-600 mb-2">Koordinat GIS Fasilitas (Opsional - default mengikuti Lokasi/Area)</span>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="latitude" class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1">Latitude</label>
                        <input type="text" name="latitude" id="latitude" value="{{ old('latitude') }}" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2 font-mono" placeholder="Contoh: -2.992683">
                        @error('latitude')
                            <p class="text-2xs text-red-650 mt-1 font-mono">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="longitude" class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1">Longitude</label>
                        <input type="text" name="longitude" id="longitude" value="{{ old('longitude') }}" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2 font-mono" placeholder="Contoh: 104.731776">
                        @error('longitude')
                            <p class="text-2xs text-red-650 mt-1 font-mono">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="bg-primary text-white text-xs font-bold tracking-wider uppercase px-6 py-3 rounded hover:bg-primary-dark transition-colors">
                    Simpan Fasilitas
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

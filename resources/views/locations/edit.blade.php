@extends('layouts.app')

@section('page-title', 'Edit Lokasi')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
            <h3 class="text-sm font-bold text-slate-800 uppercase font-mono tracking-wider">Edit Lokasi: {{ $location->name }}</h3>
            <a href="{{ route('locations.index') }}" class="text-xs text-slate-500 hover:text-slate-800">
                &larr; Kembali
            </a>
        </div>

        <form action="{{ route('locations.update', $location->id) }}" method="POST" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1">Nama Area / Lokasi</label>
                <input type="text" name="name" id="name" value="{{ old('name', $location->name) }}" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2" required>
                @error('name')
                    <p class="text-2xs text-red-650 mt-1 font-mono">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1">Deskripsi</label>
                <textarea name="description" id="description" rows="3" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2">{{ old('description', $location->description) }}</textarea>
                @error('description')
                    <p class="text-2xs text-red-650 mt-1 font-mono">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="latitude" class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1">Latitude</label>
                    <input type="text" name="latitude" id="latitude" value="{{ old('latitude', $location->latitude) }}" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2 font-mono">
                    @error('latitude')
                        <p class="text-2xs text-red-650 mt-1 font-mono">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="longitude" class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1">Longitude</label>
                    <input type="text" name="longitude" id="longitude" value="{{ old('longitude', $location->longitude) }}" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2 font-mono">
                    @error('longitude')
                        <p class="text-2xs text-red-650 mt-1 font-mono">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="bg-primary text-white text-xs font-bold tracking-wider uppercase px-6 py-3 rounded hover:bg-primary-dark transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

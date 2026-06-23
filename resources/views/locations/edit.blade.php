@extends('layouts.app')

@section('page-title', 'Edit Lokasi')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card overflow-hidden transition-all duration-300 hover:shadow-card-hover">
        <div class="px-6 py-4 border-b border-gray-200 bg-surface-50 flex justify-between items-center">
            <h3 class="text-sm font-bold text-yellow-400 uppercase font-mono tracking-wider">Edit Lokasi: {{ $location->name }}</h3>
            <a href="{{ route('locations.index') }}" class="text-xs text-slate-400 hover:text-primary">
                &larr; Kembali
            </a>
        </div>

        <form action="{{ route('locations.update', $location->id) }}" method="POST" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Nama Area / Lokasi</label>
                <input type="text" name="name" id="name" value="{{ old('name', $location->name) }}" class="w-full text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 py-2" required>
                @error('name')
                    <p class="text-xs text-red-600 mt-1 font-mono">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Deskripsi</label>
                <textarea name="description" id="description" rows="3" class="w-full text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 py-2">{{ old('description', $location->description) }}</textarea>
                @error('description')
                    <p class="text-xs text-red-600 mt-1 font-mono">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="latitude" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Latitude</label>
                    <input type="text" name="latitude" id="latitude" value="{{ old('latitude', $location->latitude) }}" class="w-full text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 py-2 font-mono">
                    @error('latitude')
                        <p class="text-xs text-red-600 mt-1 font-mono">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="longitude" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Longitude</label>
                    <input type="text" name="longitude" id="longitude" value="{{ old('longitude', $location->longitude) }}" class="w-full text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 py-2 font-mono">
                    @error('longitude')
                        <p class="text-xs text-red-600 mt-1 font-mono">{{ $message }}</p>
                    @enderror
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

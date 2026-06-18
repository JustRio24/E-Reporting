@extends('layouts.app')

@section('page-title', 'Tambah Lokasi Baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-gradient-to-br from-slate-900/80 via-blue-900/40 to-yellow-600/10 backdrop-blur-md rounded-lg border border-slate-700/50 shadow-sm overflow-hidden transition-all duration-500 hover:border-yellow-500/30 hover:shadow-lg hover:shadow-yellow-500/5">
        <div class="px-6 py-4 border-b border-slate-700/50 bg-slate-900/40 flex justify-between items-center">
            <h3 class="text-sm font-bold text-yellow-400 uppercase font-mono tracking-wider">Formulir Lokasi Pelabuhan</h3>
            <a href="{{ route('locations.index') }}" class="text-xs text-slate-400 hover:text-slate-200">
                &larr; Kembali
            </a>
        </div>

        <form action="{{ route('locations.store') }}" method="POST" class="p-6 space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Nama Area / Lokasi</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2" placeholder="Contoh: Transfer Tower 2 (TT2)" required>
                @error('name')
                    <p class="text-2xs text-red-650 mt-1 font-mono">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Deskripsi</label>
                <textarea name="description" id="description" rows="3" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2" placeholder="Jelaskan mengenai detail letak atau fungsi area tersebut...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-2xs text-red-650 mt-1 font-mono">{{ $message }}</p>
                @enderror
            </div>

            <!-- GPS Coordinates -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="latitude" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Latitude</label>
                    <input type="text" name="latitude" id="latitude" value="{{ old('latitude') }}" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2 font-mono" placeholder="Contoh: -2.993412">
                    @error('latitude')
                        <p class="text-2xs text-red-650 mt-1 font-mono">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="longitude" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Longitude</label>
                    <input type="text" name="longitude" id="longitude" value="{{ old('longitude') }}" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2 font-mono" placeholder="Contoh: 104.730894">
                    @error('longitude')
                        <p class="text-2xs text-red-650 mt-1 font-mono">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="bg-primary text-white text-xs font-bold tracking-wider uppercase px-6 py-3 rounded hover:bg-primary-dark transition-colors">
                    Simpan Lokasi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

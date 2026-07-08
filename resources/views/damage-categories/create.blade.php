@extends('layouts.app')

@section('page-title', 'Tambah Kategori Kerusakan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card overflow-hidden transition-all duration-300 hover:shadow-card-hover">
        <div class="px-6 py-4 border-b border-gray-200 bg-surface-50 flex justify-between items-center">
            <h3 class="text-sm font-bold text-yellow-400 uppercase font-mono tracking-wider">Formulir Kategori Kerusakan</h3>
            <a href="{{ route('damage-categories.index') }}" class="text-xs text-slate-400 hover:text-primary">
                &larr; Kembali
            </a>
        </div>

        <form action="{{ route('damage-categories.store') }}" method="POST" class="p-6 space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Nama Kategori</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 py-2" required>
                @error('name')
                    <p class="text-xs text-red-600 mt-1 font-mono">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Deskripsi</label>
                <textarea name="description" id="description" rows="4" class="w-full text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 py-2">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-xs text-red-600 mt-1 font-mono">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-primary text-white text-xs font-bold tracking-wider uppercase px-6 py-3 rounded hover:bg-primary-dark transition-colors">
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

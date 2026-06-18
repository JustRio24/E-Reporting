@extends('layouts.app')

@section('page-title', 'Edit Kategori Kerusakan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-gradient-to-br from-slate-900/80 via-blue-900/40 to-yellow-600/10 backdrop-blur-md rounded-lg border border-slate-700/50 shadow-sm overflow-hidden transition-all duration-500 hover:border-yellow-500/30 hover:shadow-lg hover:shadow-yellow-500/5">
        <div class="px-6 py-4 border-b border-slate-700/50 bg-slate-900/40 flex justify-between items-center">
            <h3 class="text-sm font-bold text-yellow-400 uppercase font-mono tracking-wider">Edit Kategori: {{ $category->name }}</h3>
            <a href="{{ route('damage-categories.index') }}" class="text-xs text-slate-400 hover:text-slate-200">
                &larr; Kembali
            </a>
        </div>

        <form action="{{ route('damage-categories.update', $category->id) }}" method="POST" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Nama Kategori</label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2" required>
                @error('name')
                    <p class="text-2xs text-red-650 mt-1 font-mono">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Deskripsi</label>
                <textarea name="description" id="description" rows="4" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <p class="text-2xs text-red-650 mt-1 font-mono">{{ $message }}</p>
                @enderror
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

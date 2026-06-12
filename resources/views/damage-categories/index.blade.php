@extends('layouts.app')

@section('page-title', 'Kategori Kerusakan')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-4 rounded-lg border border-slate-200 shadow-sm">
        <form action="{{ route('damage-categories.index') }}" method="GET" class="flex items-center gap-3 flex-1">
            <div class="w-full sm:w-64 relative">
                <input type="text" name="search" placeholder="Cari nama kategori..." value="{{ $filters['search'] ?? '' }}" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary pl-8 py-2">
                <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <button type="submit" class="bg-secondary text-white text-xs font-semibold px-4 py-2 rounded hover:bg-secondary-dark transition-colors">
                Filter
            </button>
            @if(!empty($filters['search']))
                <a href="{{ route('damage-categories.index') }}" class="text-xs text-slate-500 hover:text-slate-800 underline">Reset</a>
            @endif
        </form>

        <a href="{{ route('damage-categories.create') }}" class="inline-flex items-center justify-center bg-primary text-white text-xs font-bold tracking-wider uppercase px-4 py-2.5 rounded hover:bg-primary-dark transition-colors self-start md:self-auto">
            Tambah Kategori
        </a>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900 text-white font-mono text-xs uppercase">
                        <th class="py-3.5 px-4 font-semibold w-1/4">Nama Kategori</th>
                        <th class="py-3.5 px-4 font-semibold w-1/2">Deskripsi</th>
                        <th class="py-3.5 px-4 font-semibold text-right w-1/4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-xs">
                    @forelse($categories as $cat)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-3.5 px-4 font-semibold text-slate-900">{{ $cat->name }}</td>
                            <td class="py-3.5 px-4 text-slate-500 leading-normal">{{ $cat->description ?? '-' }}</td>
                            <td class="py-3.5 px-4 text-right space-x-2">
                                <a href="{{ route('damage-categories.edit', $cat->id) }}" class="inline-block text-3xs font-semibold px-2 py-1 rounded border border-slate-300 text-slate-700 hover:bg-slate-100 transition-colors">
                                    Edit
                                </a>
                                <form action="{{ route('damage-categories.destroy', $cat->id) }}" method="POST" class="inline-block delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="text-3xs font-semibold px-2 py-1 rounded border border-red-300 text-red-650 hover:bg-red-50 transition-colors delete-btn">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-8 text-center text-slate-400 font-mono">
                                Data kategori tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
            <div class="px-5 py-4 border-t border-slate-200 bg-slate-50">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForms = document.querySelectorAll('.delete-form');
        deleteForms.forEach(form => {
            const btn = form.querySelector('.delete-btn');
            btn.addEventListener('click', function() {
                Swal.fire({
                    title: 'Hapus Kategori Kerusakan?',
                    text: "Tindakan ini tidak dapat dibatalkan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ba1a1a',
                    cancelButtonColor: '#545f72',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'rounded-lg',
                        confirmButton: 'rounded px-4 py-2 font-semibold text-sm',
                        cancelButton: 'rounded px-4 py-2 font-semibold text-sm'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush

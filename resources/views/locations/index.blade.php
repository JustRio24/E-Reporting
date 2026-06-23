@extends('layouts.app')

@section('page-title', 'Lokasi & Area Pelabuhan')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 card p-4 transition-all duration-300 hover:shadow-card-hover">
        <form action="{{ route('locations.index') }}" method="GET" class="flex items-center gap-3 flex-1">
            <div class="w-full sm:w-64 relative">
                <input type="text" name="search" placeholder="Cari nama lokasi..." value="{{ $filters['search'] ?? '' }}" class="w-full text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 pl-8 py-2">
                <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <button type="submit" class="bg-secondary text-slate-800 text-xs font-semibold px-4 py-2 rounded hover:bg-secondary-dark transition-colors">
                Filter
            </button>
            @if(!empty($filters['search']))
                <a href="{{ route('locations.index') }}" class="text-xs text-slate-400 hover:text-primary underline">Reset</a>
            @endif
        </form>

        <a href="{{ route('locations.create') }}" class="inline-flex items-center justify-center bg-primary text-slate-800 text-xs font-bold tracking-wider uppercase px-4 py-2.5 rounded hover:bg-primary-dark transition-colors self-start md:self-auto">
            Tambah Lokasi
        </a>
    </div>

    <div class="card overflow-hidden transition-all duration-300 hover:shadow-card-hover">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900 text-slate-800 font-mono text-xs uppercase">
                        <th class="py-3.5 px-4 font-semibold w-1/4">Nama Area</th>
                        <th class="py-3.5 px-4 font-semibold w-1/3">Deskripsi</th>
                        <th class="py-3.5 px-4 font-semibold w-1/4">GPS (Lat, Lng)</th>
                        <th class="py-3.5 px-4 font-semibold text-right w-1/6">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50 text-xs">
                    @forelse($locations as $loc)
                        <tr class="hover:bg-slate-700/30 transition-colors">
                            <td class="py-3.5 px-4 font-semibold text-slate-800">{{ $loc->name }}</td>
                            <td class="py-3.5 px-4 text-slate-400 leading-normal">{{ $loc->description ?? '-' }}</td>
                            <td class="py-3.5 px-4 font-mono text-blue-200">
                                @if($loc->latitude && $loc->longitude)
                                    <span class="inline-flex items-center text-blue-200">
                                        <svg class="w-3.5 h-3.5 text-red-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ number_format($loc->latitude, 6) }}, {{ number_format($loc->longitude, 6) }}
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right space-x-2">
                                <a href="{{ route('locations.edit', $loc->id) }}" class="inline-block text-[11px] font-semibold px-2 py-1 rounded border border-slate-600/50 text-slate-500 hover:bg-slate-100 transition-colors">
                                    Edit
                                </a>
                                <form action="{{ route('locations.destroy', $loc->id) }}" method="POST" class="inline-block delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="text-[11px] font-semibold px-2 py-1 rounded border border-red-300 text-red-600 hover:bg-red-50 transition-colors delete-btn">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-400 font-mono">
                                Data lokasi tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($locations->hasPages())
            <div class="px-5 py-4 border-t border-gray-200 bg-surface-50">
                {{ $locations->links() }}
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
                    title: 'Hapus Lokasi?',
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

@extends('layouts.app')

@section('page-title', 'Fasilitas Pelabuhan')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-4 rounded-lg border border-slate-200 shadow-sm">
        <form action="{{ route('facilities.index') }}" method="GET" class="flex flex-wrap items-center gap-3 flex-1">
            <div class="w-full sm:w-64 relative">
                <input type="text" name="search" placeholder="Cari kode atau nama fasilitas..." value="{{ $filters['search'] ?? '' }}" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary pl-8 py-2">
                <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <select name="category_id" class="text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ (isset($filters['category_id']) && $filters['category_id'] == $cat->id) ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>

            <select name="location_id" class="text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2">
                <option value="">Semua Lokasi</option>
                @foreach($locations as $loc)
                    <option value="{{ $loc->id }}" {{ (isset($filters['location_id']) && $filters['location_id'] == $loc->id) ? 'selected' : '' }}>
                        {{ $loc->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="bg-secondary text-white text-xs font-semibold px-4 py-2 rounded hover:bg-secondary-dark transition-colors">
                Filter
            </button>
            @if(!empty($filters))
                <a href="{{ route('facilities.index') }}" class="text-xs text-slate-500 hover:text-slate-800 underline">Reset</a>
            @endif
        </form>

        <a href="{{ route('facilities.create') }}" class="inline-flex items-center justify-center bg-primary text-white text-xs font-bold tracking-wider uppercase px-4 py-2.5 rounded hover:bg-primary-dark transition-colors self-start md:self-auto">
            Tambah Fasilitas
        </a>
    </div>

    <div class="bg-white rounded-lg border border-slate-205 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900 text-white font-mono text-xs uppercase">
                        <th class="py-3.5 px-4 font-semibold w-1/6">Kode</th>
                        <th class="py-3.5 px-4 font-semibold w-1/4">Nama Fasilitas</th>
                        <th class="py-3.5 px-4 font-semibold w-1/6">Kategori</th>
                        <th class="py-3.5 px-4 font-semibold w-1/6">Lokasi</th>
                        <th class="py-3.5 px-4 font-semibold w-1/6">GPS (Lat, Lng)</th>
                        <th class="py-3.5 px-4 font-semibold text-right w-1/6">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-xs">
                    @forelse($facilities as $fac)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-3 px-4 font-mono font-bold text-primary-dark select-all">{{ $fac->facility_code }}</td>
                            <td class="py-3 px-4 font-semibold text-slate-900">{{ $fac->facility_name }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ $fac->category->name }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ $fac->location->name }}</td>
                            <td class="py-3 px-4 font-mono text-slate-500">
                                @if($fac->latitude && $fac->longitude)
                                    {{ number_format($fac->latitude, 5) }}, {{ number_format($fac->longitude, 5) }}
                                @else
                                    <span class="text-slate-350">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right space-x-2">
                                <a href="{{ route('facilities.edit', $fac->id) }}" class="inline-block text-3xs font-semibold px-2 py-1 rounded border border-slate-300 text-slate-700 hover:bg-slate-100 transition-colors">
                                    Edit
                                </a>
                                <form action="{{ route('facilities.destroy', $fac->id) }}" method="POST" class="inline-block delete-form">
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
                            <td colspan="6" class="py-8 text-center text-slate-400 font-mono">
                                Data fasilitas tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($facilities->hasPages())
            <div class="px-5 py-4 border-t border-slate-200 bg-slate-50">
                {{ $facilities->links() }}
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
                    title: 'Hapus Fasilitas?',
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

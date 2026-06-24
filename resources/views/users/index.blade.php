@extends('layouts.app')

@section('page-title', 'Manajemen User')

@section('content')
<div class="space-y-6">
    <!-- Header Controls & Search -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 card p-4 transition-all duration-300 hover:shadow-card-hover">
        <!-- Search & Filter Form -->
        <form action="{{ route('users.index') }}" method="GET" class="flex flex-wrap items-center gap-3 flex-1">
            <div class="w-full sm:w-64 relative">
                <input type="text" name="search" placeholder="Cari nama atau email..." value="{{ $filters['search'] ?? '' }}" class="w-full text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 pl-8 py-2">
                <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <select name="role" class="text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 py-2">
                <option value="">Semua Peran</option>
                @foreach($roles as $role)
                    <option value="{{ $role->value }}" {{ (isset($filters['role']) && $filters['role'] === $role->value) ? 'selected' : '' }}>
                        {{ strtoupper($role->value) }}
                    </option>
                @endforeach
            </select>

            <select name="is_active" class="text-xs rounded border-gray-200 focus:border-primary focus:ring-primary/20 py-2">
                <option value="">Semua Status</option>
                <option value="1" {{ (isset($filters['is_active']) && $filters['is_active'] == '1') ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ (isset($filters['is_active']) && $filters['is_active'] == '0') ? 'selected' : '' }}>Nonaktif</option>
            </select>

            <button type="submit" class="bg-secondary text-slate-800 text-xs font-semibold px-4 py-2 rounded hover:bg-secondary-dark transition-colors">
                Filter
            </button>
            
            @if(!empty($filters))
                <a href="{{ route('users.index') }}" class="text-xs text-slate-400 hover:text-primary underline">Reset</a>
            @endif
        </form>

        <a href="{{ route('users.create') }}" class="inline-flex items-center justify-center bg-primary text-slate-800 text-xs font-bold tracking-wider uppercase px-4 py-2.5 rounded hover:bg-primary-dark transition-colors self-start md:self-auto">
            Tambah User
        </a>
    </div>

    <!-- Data Table -->
    <div class="card overflow-hidden transition-all duration-300 hover:shadow-card-hover">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900 text-white font-mono text-xs uppercase">
                        <th class="py-3.5 px-4 font-semibold">Nama</th>
                        <th class="py-3.5 px-4 font-semibold">Email</th>
                        <th class="py-3.5 px-4 font-semibold">Peran (Role)</th>
                        <th class="py-3.5 px-4 font-semibold">No. Telepon</th>
                        <th class="py-3.5 px-4 font-semibold text-center">Status</th>
                        <th class="py-3.5 px-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50 text-xs">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-700/30 transition-colors">
                            <td class="py-3 px-4 font-semibold text-slate-800">{{ $user->name }}</td>
                            <td class="py-3 px-4 font-mono text-slate-500">{{ $user->email }}</td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-mono font-bold tracking-wider uppercase border 
                                    @if($user->isAdmin()) bg-blue-500/10 text-blue-300 border-blue-500/30
                                    @elseif($user->isSupervisor()) bg-yellow-500/10 text-yellow-300 border-yellow-500/30
                                    @elseif($user->isInspector()) bg-emerald-500/10 text-emerald-300 border-emerald-500/30
                                    @else bg-fuchsia-500/10 text-fuchsia-300 border-fuchsia-500/30 @endif">
                                    {{ $user->role->value }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-slate-400 font-mono">{{ $user->phone ?? '-' }}</td>
                            <td class="py-3 px-4 text-center">
                                @if($user->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-500/20 text-emerald-400">AKTIF</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-red-500/20 text-red-400">NONAKTIF</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right space-x-2">
                                <!-- Toggle Status Form -->
                                <form action="{{ route('users.toggle', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-[11px] font-semibold px-2 py-1 rounded border hover:bg-slate-100 transition-colors 
                                        {{ $user->is_active ? 'border-red-500/30 text-red-300' : 'border-emerald-250 text-emerald-300' }}">
                                        {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>

                                <!-- Edit Link -->
                                <a href="{{ route('users.edit', $user->id) }}" class="inline-block text-[11px] font-semibold px-2 py-1 rounded border border-slate-600/50 text-slate-500 hover:bg-slate-100 transition-colors">
                                    Edit
                                </a>

                                <!-- Delete Form -->
                                @if(auth()->id() !== $user->id)
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="text-[11px] font-semibold px-2 py-1 rounded border border-red-300 text-red-600 hover:bg-red-50 transition-colors delete-btn">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 font-mono">
                                Data user tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-5 py-4 border-t border-gray-200 bg-surface-50">
                {{ $users->links() }}
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
                    title: 'Hapus User?',
                    text: "Tindakan ini tidak dapat dibatalkan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ba1a1a', // Safety Red
                    cancelButtonColor: '#545f72', // Tertiary Gray
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

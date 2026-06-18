@extends('layouts.app')

@section('page-title', 'Edit User')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-gradient-to-br from-slate-900/80 via-blue-900/40 to-yellow-600/10 backdrop-blur-md rounded-lg border border-slate-700/50 shadow-sm overflow-hidden transition-all duration-500 hover:border-yellow-500/30 hover:shadow-lg hover:shadow-yellow-500/5">
        <!-- Form Header -->
        <div class="px-6 py-4 border-b border-slate-700/50 bg-slate-900/40 flex justify-between items-center">
            <h3 class="text-sm font-bold text-yellow-400 uppercase font-mono tracking-wider">Formulir Edit User: {{ $userModel->name }}</h3>
            <a href="{{ route('users.index') }}" class="text-xs text-slate-400 hover:text-slate-200 flex items-center">
                &larr; Kembali
            </a>
        </div>

        <!-- Form Body -->
        <form action="{{ route('users.update', $userModel->id) }}" method="POST" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name', $userModel->name) }}" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2" required>
                @error('name')
                    <p class="text-2xs text-red-650 mt-1 font-mono">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email & Phone Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Alamat Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $userModel->email) }}" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2" required>
                    @error('email')
                        <p class="text-2xs text-red-650 mt-1 font-mono">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Nomor Telepon</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $userModel->phone) }}" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2">
                    @error('phone')
                        <p class="text-2xs text-red-650 mt-1 font-mono">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Role Select -->
            <div>
                <label for="role" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Peran / Role</label>
                <select name="role" id="role" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2" required>
                    @foreach($roles as $role)
                        <option value="{{ $role->value }}" {{ old('role', $userModel->role->value) === $role->value ? 'selected' : '' }}>
                            {{ strtoupper($role->value) }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                    <p class="text-2xs text-red-650 mt-1 font-mono">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-slate-900/40 p-4 rounded border border-slate-700/50">
                <span class="block text-xs font-mono font-bold text-slate-300 mb-2">Ganti Password (Opsional)</span>
                <!-- Password Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Password Baru</label>
                        <input type="password" name="password" id="password" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2">
                        @error('password')
                            <p class="text-2xs text-red-650 mt-1 font-mono">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="w-full text-xs rounded border-slate-350 focus:border-secondary focus:ring-secondary py-2">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="bg-primary text-white text-xs font-bold tracking-wider uppercase px-6 py-3 rounded hover:bg-primary-dark transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

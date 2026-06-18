<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-blue-200 mb-1.5">Alamat Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                class="w-full text-sm rounded-xl bg-white/5 border border-white/10 text-white focus:bg-slate-900/50 focus:border-secondary focus:ring-1 focus:ring-secondary px-4 py-3 transition-all shadow-inner placeholder:text-slate-500" placeholder="admin@reporting.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-2xs font-mono text-red-500" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-blue-200">Kata Sandi</label>
                @if (Route::has('password.request'))
                    <a class="text-3xs font-mono font-bold text-slate-400 hover:text-secondary transition-colors" href="{{ route('password.request') }}">
                        Lupa Sandi?
                    </a>
                @endif
            </div>
            <input id="password" type="password" name="password" required autocomplete="current-password" 
                class="w-full text-sm rounded-xl bg-white/5 border border-white/10 text-white focus:bg-slate-900/50 focus:border-secondary focus:ring-1 focus:ring-secondary px-4 py-3 transition-all shadow-inner placeholder:text-slate-500" placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-2xs font-mono text-red-500" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="rounded border-slate-600 bg-slate-900/50 text-secondary focus:ring-secondary focus:ring-offset-slate-900" name="remember">
                <span class="ms-2 text-xs text-slate-400 group-hover:text-slate-200 transition-colors">Ingat Saya</span>
            </label>
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full flex items-center justify-center bg-gradient-to-r from-secondary to-yellow-600 text-slate-950 text-sm font-bold tracking-wider uppercase py-3.5 rounded-lg hover:from-yellow-500 hover:to-yellow-500 transition-all shadow-lg shadow-yellow-500/20 active:scale-[0.98]">
                Masuk ke Sistem
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
        </div>
    </form>
</x-guest-layout>

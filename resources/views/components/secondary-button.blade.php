<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-gradient-to-br from-slate-900/80 via-blue-900/40 to-yellow-600/10 backdrop-blur-md border border-gray-300 rounded-md font-semibold text-xs text-slate-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>

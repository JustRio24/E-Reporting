@php
    $roleBtnClasses = 'bg-slate-600 text-white hover:bg-slate-500 shadow-lg shadow-slate-500/20 focus:ring-slate-500';
    
    if (auth()->check()) {
        if (auth()->user()->isAdmin()) {
            $roleBtnClasses = 'bg-fuchsia-600 text-fuchsia-100 hover:bg-fuchsia-500 shadow-lg shadow-fuchsia-500/20 focus:ring-fuchsia-500';
        } elseif (auth()->user()->isInspector()) {
            $roleBtnClasses = 'bg-blue-600 text-blue-50 hover:bg-blue-500 shadow-lg shadow-blue-500/20 focus:ring-blue-500';
        } elseif (auth()->user()->isSupervisor()) {
            $roleBtnClasses = 'bg-teal-600 text-teal-50 hover:bg-teal-500 shadow-lg shadow-teal-500/20 focus:ring-teal-500';
        } elseif (auth()->user()->isMaintenance()) {
            $roleBtnClasses = 'bg-orange-500 text-orange-50 hover:bg-orange-400 shadow-lg shadow-orange-500/30 focus:ring-orange-500';
        }
    }
@endphp

<button {{ $attributes->merge(['type' => 'submit', 'class' => "inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-300 $roleBtnClasses"]) }}>
    {{ $slot }}
</button>

<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-outline uppercase tracking-wider text-xs cursor-pointer disabled:opacity-50']) }}>
    {{ $slot }}
</button>

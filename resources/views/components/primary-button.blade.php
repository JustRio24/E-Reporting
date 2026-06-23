<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-primary uppercase tracking-wider text-xs cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed']) }}>
    {{ $slot }}
</button>

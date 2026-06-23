<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-danger uppercase tracking-wider text-xs cursor-pointer disabled:opacity-50']) }}>
    {{ $slot }}
</button>

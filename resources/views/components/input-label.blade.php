@props(['value'])

<label {{ $attributes->merge(['class' => 'label-clean']) }}>
    {{ $value ?? $slot }}
</label>

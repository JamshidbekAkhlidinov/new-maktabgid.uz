@props(['href' => '#', 'label' => 'Orqaga'])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'backlink']) }}>
    <x-maktabgid.icon name="arrowL" :width="16" :height="16" /> {{ $label }}
</a>

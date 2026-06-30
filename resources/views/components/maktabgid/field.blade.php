@props(['label', 'icon' => null, 'hint' => null])

<label {{ $attributes->merge(['class' => 'field']) }}>
    <span class="field-label">{{ $label }}@if ($hint)<em>{{ $hint }}</em>@endif</span>
    <span class="field-control">
        @if ($icon)
            <x-maktabgid.icon :name="$icon" :width="17" :height="17" />
        @endif
        {{ $slot }}
    </span>
</label>

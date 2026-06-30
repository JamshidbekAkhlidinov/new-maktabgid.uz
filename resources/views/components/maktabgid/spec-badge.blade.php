@props(['specKey'])

@php
    use App\Support\MaktabgidData;
    $sp = MaktabgidData::specializationLabel($specKey);
@endphp

@if ($sp)
    <span {{ $attributes->merge(['class' => 'spec-badge']) }}>
        <x-maktabgid.icon :name="$sp['icon']" :width="13" :height="13" /> {{ $sp['label'] }}
    </span>
@endif

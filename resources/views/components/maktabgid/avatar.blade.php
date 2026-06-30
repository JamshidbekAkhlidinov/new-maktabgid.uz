@props(['name', 'g' => null, 'size' => 44, 'square' => false])

@php
    use App\Support\MaktabgidData;
    $grad = $g ? "linear-gradient(140deg, {$g[0]}, {$g[1]})" : 'linear-gradient(140deg, var(--primary), var(--primary-700))';
    $radius = $square ? round($size * 0.28) . 'px' : '50%';
@endphp

<span {{ $attributes->merge(['class' => 'avatar']) }} style="width:{{ $size }}px;height:{{ $size }}px;background:{{ $grad }};border-radius:{{ $radius }};font-size:{{ $size * 0.4 }}px">
    {{ MaktabgidData::monogram($name) }}
</span>

@props(['icon' => null, 'label' => null, 'gi' => 0, 'big' => false, 'badge' => null, 'url' => null])

@php
    $grads = \App\Support\MaktabgidData::tileGradients();
    $g = $grads[$gi % count($grads)];
    $bg = $url ? "url('{$url}') center/cover no-repeat" : "linear-gradient(140deg, {$g[0]}, {$g[1]})";
@endphp

<div class="ptile{{ $big ? ' big' : '' }}" style="background:{{ $bg }}">
    @unless ($url)
        <x-maktabgid.icon :name="$icon" class="ptile-wm" :width="$big ? 120 : 70" :height="$big ? 120 : 70" />
        <span class="ptile-noise"></span>
        <span class="ptile-cap">{{ $label }}</span>
    @endunless
    @if ($badge)
        <span class="ptile-badge"><x-maktabgid.icon name="camera" :width="13" :height="13" /> {{ $badge }}</span>
    @endif
</div>

@props(['title', 'dur', 'sub', 'gi' => 0])

@php
    $grads = \App\Support\MaktabgidData::tileGradients();
    $g = $grads[$gi % count($grads)];
@endphp

<article class="vcard">
    <div class="vthumb" style="background:linear-gradient(140deg, {{ $g[0] }}, {{ $g[1] }})">
        <x-maktabgid.icon name="play" class="vcard-wm" :width="64" :height="64" />
        <button type="button" class="vplay" aria-label="Ijro"><x-maktabgid.icon name="play" :width="22" :height="22" /></button>
        <span class="vdur">{{ $dur }}</span>
    </div>
    <div class="vinfo"><b>{{ $title }}</b><span>{{ $sub }}</span></div>
</article>

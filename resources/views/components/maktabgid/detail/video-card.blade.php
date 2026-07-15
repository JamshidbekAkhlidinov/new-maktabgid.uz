@props(['title', 'dur', 'sub', 'url' => null, 'gi' => 0])

@php
    $grads = \App\Support\MaktabgidData::tileGradients();
    $g = $grads[$gi % count($grads)];
    $isExternal = $url && ! str_contains(parse_url($url, PHP_URL_HOST) ?? '', request()->getHost());
@endphp

<article class="vcard">
    <div class="vthumb" style="background:linear-gradient(140deg, {{ $g[0] }}, {{ $g[1] }})">
        @if ($url && $isExternal)
            {{-- Tashqi havola (YouTube/Vimeo) — yangi oynada ochiladi --}}
            <a href="{{ $url }}" target="_blank" rel="noopener" class="vplay-link" aria-label="Videoni ochish">
                <x-maktabgid.icon name="play" class="vcard-wm" :width="64" :height="64" />
                <span class="vplay"><x-maktabgid.icon name="play" :width="22" :height="22" /></span>
            </a>
        @elseif ($url)
            {{-- Real yuklangan video fayl — to'g'ridan-to'g'ri ijro qilinadi --}}
            <video src="{{ $url }}" controls preload="metadata" style="width:100%;height:100%;object-fit:cover;border-radius:inherit"></video>
        @else
            <x-maktabgid.icon name="play" class="vcard-wm" :width="64" :height="64" />
            <button type="button" class="vplay" aria-label="Ijro"><x-maktabgid.icon name="play" :width="22" :height="22" /></button>
        @endif
        @if ($dur)<span class="vdur">{{ $dur }}</span>@endif
    </div>
    <div class="vinfo"><b>{{ $title }}</b><span>{{ $sub }}</span></div>
</article>

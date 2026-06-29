@props(['school'])

@php
    use App\Support\MaktabgidData;
    $s = $school;
    $catLabel = MaktabgidData::categoryLabel($s['cat']);
@endphp

<article
    class="scard js-scard"
    data-id="{{ $s['id'] }}"
    data-cat="{{ $s['cat'] }}"
    data-name="{{ mb_strtolower($s['name']) }}"
    data-district="{{ $s['district'] }}"
    data-dist="{{ $s['dist'] }}"
    data-price="{{ $s['price'] }}"
    data-rating="{{ $s['rating'] }}"
    data-sat="{{ $s['sat'] ? '1' : '0' }}"
>
    <div class="scard-media" style="background: linear-gradient(140deg, {{ $s['g'][0] }}, {{ $s['g'][1] }})">
        <span class="scard-mono">{{ MaktabgidData::monogram($s['name']) }}</span>
        @if (!empty($s['badge']))
            <span class="media-badge">{{ $s['badge'] }}</span>
        @endif
        <button type="button" class="fav js-fav" aria-label="Saqlash">
            <x-maktabgid.icon name="heart" :width="16" :height="16" />
        </button>
    </div>
    <div class="scard-body">
        <div class="scard-top">
            <div>
                <h3 class="scard-name">{{ $s['name'] }}</h3>
            </div>
            <span class="scard-rating"><x-maktabgid.icon name="star" class="star" :width="15" :height="15" fill="currentColor" /> {{ $s['rating'] }} <em>({{ $s['reviews'] }})</em></span>
        </div>
        <div class="scard-meta">
            <span class="m"><x-maktabgid.icon name="pin" :width="15" :height="15" /> {{ $s['district'] }}</span>
            <span class="m"><x-maktabgid.icon name="map" :width="15" :height="15" /> {{ $s['dist'] }} km</span>
            <span class="m"><x-maktabgid.icon name="users" :width="15" :height="15" /> {{ $s['grades'] }}</span>
        </div>
        <div class="scard-tags">
            <span class="tag">{{ $catLabel }}</span>
            <span class="tag lang">{{ $s['lang'] }}</span>
            @if ($s['sat'])
                <span class="tag sat">Shanba ish</span>
            @endif
        </div>
        <div class="scard-foot">
            <div class="price"><b>{{ MaktabgidData::formatPrice($s['price']) }}</b> <span>soʻm / oy</span></div>
            <button type="button" class="btn btn-ghost scard-cta">Batafsil <x-maktabgid.icon name="arrowR" :width="16" :height="16" /></button>
        </div>
    </div>
</article>

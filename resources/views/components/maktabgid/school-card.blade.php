@props(['school'])

@php
    use App\Support\MaktabgidData;
    $s = $school;
    $catLabel = MaktabgidData::categoryLabel($s['cat']);
    $photoUrl = $s['photos'][0] ?? null;
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
    data-specs="{{ implode(',', $s['specs'] ?? []) }}"
>
    <a href="{{ route('maktabgid.school', $s['slug']) }}" class="scard-link">
        <div class="scard-media" style="background: {{ $photoUrl ? "url('{$photoUrl}') center/cover no-repeat" : "linear-gradient(140deg, {$s['g'][0]}, {$s['g'][1]})" }}">
            @unless ($photoUrl)
                <span class="scard-mono">{{ MaktabgidData::monogram($s['name']) }}</span>
            @endunless
        </div>
        <div class="scard-body">
            <h3 class="scard-name">{{ $s['name'] }}</h3>
            <div class="scard-meta">{{ $s['district'] }} · {{ $s['dist'] }} km · {{ $catLabel }}</div>
        </div>
        <div class="scard-rating"><x-maktabgid.icon name="star" class="star" :width="14" :height="14" fill="currentColor" /> {{ $s['rating'] }} <em>({{ $s['reviews'] }})</em></div>
        <div class="scard-price"><b>{{ MaktabgidData::formatPrice($s['price']) }}</b><span>{{ __('home.price_per_month_compact') }}</span></div>
    </a>
    <button type="button" class="fav js-fav" aria-label="{{ __('home.save') }}">
        <x-maktabgid.icon name="heart" :width="16" :height="16" />
    </button>
</article>

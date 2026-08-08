@props([
    'ads' => [
        [
            'badge' => 'TOP OʻQUV MARKAZI',
            'tag' => 'Yangi guruhlar',
            'initials' => 'NT',
            'title' => 'Najot Taʼlim — IT yoʻnalishida №1',
            'rating' => '4.8',
            'description' => 'Frontend, backend, dasturlash. Bitiruvchilarning 87% ish bilan taʼminlangan.',
            'ctaLabel' => 'Kursni tanlash',
            'ctaHref' => '#',
        ],
        [
            'badge' => 'TOP TIL MARKAZI',
            'tag' => 'Bepul sinov darsi',
            'initials' => 'EC',
            'title' => 'English Club — IELTS 7.5+ kafolat',
            'rating' => '4.9',
            'description' => 'Native speaker guruhlari, kichik guruhlar (6 kishigacha), moslashuvchan jadval.',
            'ctaLabel' => 'Ro‘yxatdan o‘tish',
            'ctaHref' => '#',
        ],
        [
            'badge' => 'YANGI QABUL',
            'tag' => 'Joylar cheklangan',
            'initials' => 'CS',
            'title' => 'Cambridge School — 2026/27 qabul ochiq',
            'rating' => '4.7',
            'description' => 'Xalqaro dastur, ikki tilli taʼlim, 1-sinfga hujjat qabul boshlandi.',
            'ctaLabel' => 'Ariza qoldirish',
            'ctaHref' => '#',
        ],
    ],
])

<section class="section" style="padding-top:10px;padding-bottom:20px">
    <div class="wrap">
        <div class="ad-banner-carousel js-ad-carousel">
            @foreach ($ads as $ad)
                <div class="ad-banner js-ad-slide{{ $loop->first ? ' on' : '' }}">
                    <span class="ad-banner-tag">{{ $ad['tag'] }}</span>

                    <div class="ad-banner-avatar">{{ $ad['initials'] }}</div>

                    <div class="ad-banner-body">
                        <span class="ad-banner-badge">
                            <x-maktabgid.icon name="sparkle" :width="14" :height="14" />
                            {{ $ad['badge'] }}
                        </span>
                        <h2>{{ $ad['title'] }}</h2>
                        <p>
                            <span class="ad-banner-rating">
                                <x-maktabgid.icon name="star" :width="13" :height="13" fill="#f5b400" stroke="#f5b400" />
                                {{ $ad['rating'] }}
                            </span>
                            {{ $ad['description'] }}
                        </p>
                    </div>

                    <div class="ad-banner-actions">
                        <a class="btn btn-white" href="{{ $ad['ctaHref'] }}">
                            {{ $ad['ctaLabel'] }}
                            <x-maktabgid.icon name="arrowR" :width="16" :height="16" />
                        </a>
                        <span class="ad-banner-label">{{ __('home.ad_label') }}</span>
                    </div>
                </div>
            @endforeach

            @if (count($ads) > 1)
                <div class="ad-banner-dots">
                    @foreach ($ads as $ad)
                        <button type="button" class="dot js-ad-dot{{ $loop->first ? ' on' : '' }}" aria-label="{{ __('home.ad_aria', ['n' => $loop->iteration]) }}"></button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>

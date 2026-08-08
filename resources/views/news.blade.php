<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ __('news.page_title') }} — {{ config('app.name', 'MaktabGID') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,600;12..96,700;12..96,800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/maktabgid.css') }}" />
    <style>
        .news-hero  { cursor: pointer; }
        .news-card  { cursor: pointer; }
    </style>
</head>
<body>

    @php
        use App\Support\MaktabgidData;
        $hot     = collect($news)->firstWhere('hot', true) ?? $news[0];
        $hotId   = $hot['id'];
        $allCats = array_merge(['Hammasi'], array_values(array_unique(array_column($news, 'tag'))));
    @endphp

    {{-- JS data injected before any rendering so filter script can use it --}}
    <script>
        var NEWS_ROUTES = {
            @foreach ($news as $n)
                {{ $n['id'] }}: "{{ route('news.show', $n['id']) }}",
            @endforeach
        };
        var NEWS_HOT_ID = {{ $hotId }};
    </script>

    <x-maktabgid.nav :categories="MaktabgidData::categories()" />

    <x-maktabgid.page-head
        icon="news"
        :kicker="__('news.kicker')"
        :title="__('news.title')"
        :sub="__('news.sub')"
    />

    <div class="wrap section">

        {{-- Chip filter row (buttons, no URL tooltip) --}}
        <div class="forum-filters" id="news-cats" style="margin-bottom:22px">
            @foreach ($allCats as $c)
                <button type="button"
                        class="chip{{ $loop->first ? ' on' : '' }}"
                        data-news-cat="{{ $c }}">{{ $c === 'Hammasi' ? __('news.all_categories') : $c }}</button>
            @endforeach
        </div>

        {{-- Hero: always rendered, hidden by JS when filter is active --}}
        <article class="news-hero"
                 id="news-hero"
                 data-id="{{ $hot['id'] }}"
                 style="background:linear-gradient(135deg,{{ $hot['g'][0] }},{{ $hot['g'][1] }})">
            <span class="news-hero-tag">{{ $hot['tag'] }}</span>
            <h2>{{ $hot['title'] }}</h2>
            <p>{{ $hot['excerpt'] }}</p>
            <span class="news-hero-meta">{{ $hot['source'] }} · {{ $hot['date'] }}</span>
        </article>

        {{-- Grid: hot card hidden server-side initially (Hammasi = default) --}}
        <div class="news-grid" id="news-grid">
            @foreach ($news as $n)
                <article class="news-card"
                         data-tag="{{ $n['tag'] }}"
                         data-id="{{ $n['id'] }}"
                         @if ($n['id'] === $hotId) style="display:none" @endif>
                    <div class="news-card-top">
                        <span class="news-tag">{{ $n['tag'] }}</span>
                        <time>{{ $n['date'] }}</time>
                    </div>
                    <h3>{{ $n['title'] }}</h3>
                    <p>{{ $n['excerpt'] }}</p>
                    <span class="news-source">
                        <x-maktabgid.icon name="news" :width="14" :height="14" />
                        {{ $n['source'] }}
                    </span>
                </article>
            @endforeach
        </div>

    </div>

    <x-maktabgid.trust-strip />
    <x-maktabgid.cta-band />
    <x-maktabgid.footer />

    <script src="{{ asset('js/maktabgid.js') }}"></script>
    <script>
    (function () {
        var cats  = document.getElementById('news-cats');
        var hero  = document.getElementById('news-hero');
        var cards = Array.prototype.slice.call(
                        document.querySelectorAll('#news-grid .news-card'));

        if (!cats) return;

        /* click-to-navigate on hero and cards */
        hero.addEventListener('click', function () {
            window.location = NEWS_ROUTES[NEWS_HOT_ID];
        });
        cards.forEach(function (c) {
            c.addEventListener('click', function () {
                window.location = NEWS_ROUTES[c.dataset.id];
            });
        });

        /* chip filtering — mirrors React useState logic exactly */
        cats.querySelectorAll('button[data-news-cat]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var cat = btn.dataset.newsCat;

                /* active chip */
                cats.querySelectorAll('button').forEach(function (b) {
                    b.classList.toggle('on', b === btn);
                });

                /* hero: visible only on "Hammasi" */
                hero.style.display = cat === 'Hammasi' ? '' : 'none';

                /* cards: mirror React filter logic
                   Hammasi → all except hot
                   else    → matching tag (including hot if same tag) */
                cards.forEach(function (c) {
                    if (cat === 'Hammasi') {
                        c.style.display = (parseInt(c.dataset.id) === NEWS_HOT_ID) ? 'none' : '';
                    } else {
                        c.style.display = c.dataset.tag === cat ? '' : 'none';
                    }
                });
            });
        });
    })();
    </script>
</body>
</html>

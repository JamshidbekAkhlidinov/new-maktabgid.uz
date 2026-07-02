<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Forum — {{ config('app.name', 'MaktabGID') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,600;12..96,700;12..96,800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/maktabgid.css') }}" />
</head>
<body>

    @php
        use App\Support\MaktabgidData;
        $cats = MaktabgidData::forumCategories();
        $filter = request('cat', 'Hammasi');
        $list = $filter === 'Hammasi' ? $threads : array_values(array_filter($threads, fn ($t) => $t['cat'] === $filter));
    @endphp

    <x-maktabgid.nav :categories="MaktabgidData::categories()" />

    <div class="forum">
        <x-maktabgid.page-head icon="forum" kicker="Ota-onalar forumi" title="Savol bering, tajriba ulashing" sub="Roʻyxatdan oʻtgan ota-onalar mavzu ochadi, boshqalar javob va maslahat beradi." />

        <div class="wrap forum-body">
            <div class="forum-filters">
                @foreach ($cats as $c)
                    <a href="{{ route('forum.index', $c === 'Hammasi' ? [] : ['cat' => $c]) }}" class="chip{{ $filter === $c ? ' on' : '' }}">{{ $c }}</a>
                @endforeach
            </div>

            <div class="thread-list">
                @foreach ($list as $t)
                    <a href="{{ route('forum.show', $t['id']) }}" class="thread-li">
                        <div class="thread-li-main">
                            <span class="thread-cat">{{ $t['cat'] }}</span>
                            <h3>{{ $t['title'] }}</h3>
                            <div class="thread-li-meta"><x-maktabgid.avatar :name="$t['author']" :size="24" /> {{ $t['author'] }} · {{ $t['ago'] }}</div>
                        </div>
                        <div class="thread-li-stats">
                            <span><x-maktabgid.icon name="forum" :width="15" :height="15" /> {{ $t['replies'] }}</span>
                            <span><x-maktabgid.icon name="eye" :width="15" :height="15" /> {{ $t['views'] }}</span>
                            <span><x-maktabgid.icon name="like" :width="15" :height="15" /> {{ $t['likes'] }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <x-maktabgid.trust-strip />
    <x-maktabgid.cta-band />
    <x-maktabgid.footer />

    <script src="{{ asset('js/maktabgid.js') }}"></script>
</body>
</html>

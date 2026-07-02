<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Blog — {{ config('app.name', 'MaktabGID') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,600;12..96,700;12..96,800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/maktabgid.css') }}" />
</head>
<body>

    @php
        use App\Support\MaktabgidData;
        $feat = collect($articles)->firstWhere('feat', true) ?? $articles[0];
        $rest = array_values(array_filter($articles, fn ($a) => $a['id'] !== $feat['id']));
    @endphp

    <x-maktabgid.nav :categories="MaktabgidData::categories()" />

    <x-maktabgid.page-head icon="book" kicker="Ota-onalar uchun blog" title="Foydali maqolalar va maslahatlar" sub="Taʼlim, tarbiya, psixologiya va sogʻliq boʻyicha mutaxassis maqolalari." />

    <div class="wrap section">
        <a href="{{ route('blog.show', $feat['id']) }}" class="feat-article">
            <div class="feat-media" style="background:linear-gradient(135deg, {{ $feat['g'][0] }}, {{ $feat['g'][1] }})"><span class="blog-tag">{{ $feat['tag'] }}</span></div>
            <div class="feat-body">
                <span class="feat-kicker">Tavsiya etilgan maqola</span>
                <h2>{{ $feat['title'] }}</h2>
                <p>{{ $feat['excerpt'] }}</p>
                <div class="article-by"><x-maktabgid.avatar :name="$feat['author']" :size="36" /><div><b>{{ $feat['author'] }}</b><span>{{ $feat['date'] }} · {{ $feat['read'] }} oʻqish</span></div></div>
            </div>
        </a>

        <div class="blog-grid" style="margin-top:28px">
            @foreach ($rest as $b)
                <a href="{{ route('blog.show', $b['id']) }}" class="blog-card">
                    <div class="blog-media" style="background:linear-gradient(140deg, {{ $b['g'][0] }}, {{ $b['g'][1] }})"><span class="blog-tag">{{ $b['tag'] }}</span></div>
                    <div class="blog-body">
                        <h3>{{ $b['title'] }}</h3>
                        <p>{{ $b['excerpt'] }}</p>
                        <span class="blog-date">{{ $b['author'] }} · {{ $b['read'] }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <x-maktabgid.trust-strip />
    <x-maktabgid.cta-band />
    <x-maktabgid.footer />

    <script src="{{ asset('js/maktabgid.js') }}"></script>
</body>
</html>

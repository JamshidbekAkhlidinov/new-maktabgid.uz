<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $article['title'] }} — Blog — {{ config('app.name', 'MaktabGID') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,600;12..96,700;12..96,800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/maktabgid.css') }}" />
</head>
<body>

    @php
        use App\Support\MaktabgidData;
        $paras = MaktabgidData::articleBody($article);
        $more  = array_slice(array_values(array_filter($related, fn ($a) => $a['id'] !== $article['id'])), 0, 3);
    @endphp

    <x-maktabgid.nav :categories="MaktabgidData::categories()" />

    <div class="wrap" style="padding-top:20px">
        <x-maktabgid.back-link href="{{ route('blog.index') }}" label="Blogga qaytish" />
    </div>

    <div class="wrap detail-grid">

        {{-- ===== MAIN ===== --}}
        <div class="detail-main">

            {{-- Gradient banner --}}
            <div class="blog-media"
                 style="height:280px;border-radius:var(--r-lg);background:linear-gradient(135deg,{{ $article['g'][0] }},{{ $article['g'][1] }})">
                <span class="blog-tag" style="font-size:13px;padding:6px 14px">{{ $article['tag'] }}</span>
            </div>

            {{-- Title + author --}}
            <article class="card-block" style="padding:28px 30px">
                <h1 style="font-size:clamp(22px,3vw,32px);line-height:1.2;margin-bottom:16px">{{ $article['title'] }}</h1>
                <div class="article-by">
                    <x-maktabgid.avatar :name="$article['author']" :size="44" />
                    <div>
                        <b>{{ $article['author'] }}</b>
                        <span>{{ $article['date'] }} · {{ $article['read'] }} oʻqish</span>
                    </div>
                </div>
            </article>

            {{-- Body --}}
            <article class="card-block" style="padding:28px 30px">
                @foreach ($paras as $p)
                    <p style="margin-bottom:16px;line-height:1.75;font-size:16px">{{ $p }}</p>
                @endforeach
            </article>

            {{-- Related articles --}}
            @if (count($more))
                <h3 class="reply-head" style="margin:0 0 14px">Oʻxshash maqolalar</h3>
                <div class="blog-grid">
                    @foreach ($more as $b)
                        <a href="{{ route('blog.show', $b['id']) }}" class="blog-card">
                            <div class="blog-media"
                                 style="background:linear-gradient(140deg,{{ $b['g'][0] }},{{ $b['g'][1] }})">
                                <span class="blog-tag">{{ $b['tag'] }}</span>
                            </div>
                            <div class="blog-body">
                                <h3>{{ $b['title'] }}</h3>
                                <p>{{ $b['excerpt'] }}</p>
                                <span class="blog-date">{{ $b['author'] }} · {{ $b['read'] }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ===== SIDEBAR ===== --}}
        <aside class="detail-side">

            {{-- Author card --}}
            <div class="side-card" style="text-align:center">
                <x-maktabgid.avatar :name="$article['author']" :size="64" />
                <p style="font-weight:800;font-size:16px;margin-top:12px">{{ $article['author'] }}</p>
                <p style="font-size:13px;color:var(--ink-3);margin-top:4px">Mutaxassis muallif</p>
                <ul class="side-facts" style="margin-top:16px;text-align:left">
                    <li>
                        <x-maktabgid.icon name="book" :width="17" :height="17" />
                        {{ $article['read'] }} oʻqish
                    </li>
                    <li>
                        <x-maktabgid.icon name="cal" :width="17" :height="17" />
                        {{ $article['date'] }}
                    </li>
                    <li>
                        <x-maktabgid.icon name="news" :width="17" :height="17" />
                        <span class="blog-tag" style="position:static;padding:4px 10px;font-size:11.5px">{{ $article['tag'] }}</span>
                    </li>
                </ul>
            </div>

            {{-- Other posts --}}
            @if (count($more))
                <div class="side-card" style="padding:18px 20px">
                    <p style="font-weight:800;font-size:13px;text-transform:uppercase;letter-spacing:.05em;color:var(--ink-3);margin-bottom:14px">Boshqa maqolalar</p>
                    <div style="display:flex;flex-direction:column;gap:14px">
                        @foreach ($more as $b)
                            <a href="{{ route('blog.show', $b['id']) }}"
                               style="display:flex;gap:12px;align-items:flex-start;text-decoration:none;color:inherit">
                                <div style="flex:none;width:48px;height:48px;border-radius:10px;background:linear-gradient(140deg,{{ $b['g'][0] }},{{ $b['g'][1] }})"></div>
                                <div>
                                    <p style="font-size:13.5px;font-weight:700;line-height:1.3">{{ $b['title'] }}</p>
                                    <span style="font-size:12px;color:var(--ink-3);font-weight:600">{{ $b['read'] }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </aside>
    </div>

    <x-maktabgid.trust-strip />
    <x-maktabgid.cta-band />
    <x-maktabgid.footer />

    <script src="{{ asset('js/maktabgid.js') }}"></script>
</body>
</html>

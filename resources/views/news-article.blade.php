<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $item['title'] }} — {{ __('news.page_title') }} — {{ config('app.name', 'MaktabGID') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,600;12..96,700;12..96,800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/maktabgid.css') }}" />
</head>
<body>

    @php
        use App\Support\MaktabgidData;
        $paras = MaktabgidData::articleBody($item);
        $more  = array_slice(array_values(array_filter($related, fn ($n) => $n['id'] !== $item['id'])), 0, 3);
    @endphp

    <x-maktabgid.nav :categories="MaktabgidData::categories()" />

    <div class="wrap" style="padding-top:20px">
        <x-maktabgid.back-link href="{{ route('news.index') }}" label="{{ __('news.back_to_news') }}" />
    </div>

    <div class="wrap detail-grid">

        {{-- ===== MAIN ===== --}}
        <div class="detail-main">

            {{-- Hero banner --}}
            <div class="news-hero"
                 style="background:linear-gradient(135deg,{{ $item['g'][0] }},{{ $item['g'][1] }});margin-bottom:0">
                <span class="news-hero-tag">{{ $item['tag'] }}</span>
                <h2>{{ $item['title'] }}</h2>
                <span class="news-hero-meta">{{ $item['source'] }} · {{ $item['date'] }}</span>
            </div>

            {{-- Body --}}
            <article class="card-block" style="padding:28px 30px">
                @foreach ($paras as $p)
                    <p style="margin-bottom:16px;line-height:1.75;font-size:16px">{{ $p }}</p>
                @endforeach
            </article>

            {{-- Related --}}
            @if (count($more))
                <h3 class="reply-head" style="margin:0 0 14px">{{ __('news.other_news') }}</h3>
                <div class="news-grid" style="grid-template-columns:repeat(3,1fr)">
                    @foreach ($more as $n)
                        <a href="{{ route('news.show', $n['id']) }}" class="news-card">
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
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ===== SIDEBAR CARD ===== --}}
        <aside class="detail-side">
            <div class="side-card">
                <span class="news-tag" style="display:inline-block;margin-bottom:14px">{{ $item['tag'] }}</span>
                <p style="font-size:13.5px;color:var(--ink-2);line-height:1.55;margin-bottom:18px">{{ $item['excerpt'] }}</p>

                <ul class="side-facts">
                    <li>
                        <x-maktabgid.icon name="news" :width="17" :height="17" />
                        {{ $item['source'] }}
                    </li>
                    <li>
                        <x-maktabgid.icon name="cal" :width="17" :height="17" />
                        {{ $item['date'] }}
                    </li>
                </ul>

                <div style="margin-top:18px;padding-top:16px;border-top:1px solid var(--line-2)">
                    <a href="{{ route('news.index') }}" class="btn btn-ghost" style="width:100%;justify-content:center">
                        <x-maktabgid.icon name="arrowL" :width="15" :height="15" /> {{ __('news.all_news_link') }}
                    </a>
                </div>
            </div>

            {{-- More news in sidebar --}}
            @if (count($more))
                <div class="side-card" style="padding:18px 20px">
                    <p style="font-weight:800;font-size:13px;text-transform:uppercase;letter-spacing:.05em;color:var(--ink-3);margin-bottom:14px">{{ __('news.other_news') }}</p>
                    <div style="display:flex;flex-direction:column;gap:12px">
                        @foreach ($more as $n)
                            <a href="{{ route('news.show', $n['id']) }}"
                               style="display:block;text-decoration:none;color:inherit">
                                <span class="news-tag" style="font-size:11px;padding:3px 9px">{{ $n['tag'] }}</span>
                                <p style="font-size:14px;font-weight:600;line-height:1.3;margin-top:6px">{{ $n['title'] }}</p>
                                <span style="font-size:12px;color:var(--ink-3);font-weight:600">{{ $n['date'] }}</span>
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

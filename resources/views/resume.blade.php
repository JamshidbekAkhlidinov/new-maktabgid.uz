<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $resume['name'] }} — {{ __('careers.page_title') }} — {{ config('app.name', 'MaktabGID') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,600;12..96,700;12..96,800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/maktabgid.css') }}" />
</head>
<body>

    @php use App\Support\MaktabgidData; @endphp

    <x-maktabgid.nav :categories="MaktabgidData::categories()" />

    <div class="wrap" style="padding-top:91px">
        <x-maktabgid.back-link :href="route('careers.index', ['tab' => 'res'])" :label="__('careers.back_to_resumes')" />
    </div>

    <div class="wrap detail-grid">

        {{-- ===== MAIN ===== --}}
        <div class="detail-main">
            <article class="card-block" style="padding:28px 30px">
                <div class="vac-org" style="gap:14px">
                    <x-maktabgid.avatar :name="$resume['name']" :size="48" />
                    <div>
                        <h1 style="font-size:clamp(20px,3vw,26px)">{{ $resume['name'] }}</h1>
                        <span style="display:block;margin-top:4px;color:var(--ink-2);font-weight:600;font-size:14.5px">{{ $resume['role'] }}</span>
                    </div>
                </div>
            </article>

            @if ($resume['description'])
                <article class="card-block" style="padding:28px 30px">
                    <h3 style="margin-bottom:14px">{{ __('careers.description_label') }}</h3>
                    <p style="line-height:1.75;white-space:pre-line">{{ $resume['description'] }}</p>
                </article>
            @endif

            @if ($resume['education'] || $resume['skills'])
                <article class="card-block" style="padding:28px 30px">
                    @if ($resume['education'])
                        <div style="{{ $resume['skills'] ? 'margin-bottom:18px' : '' }}">
                            <h3 style="margin-bottom:10px"><x-maktabgid.icon name="book" :width="18" :height="18" /> {{ __('careers.education_label') }}</h3>
                            <p style="line-height:1.7">{{ $resume['education'] }}</p>
                        </div>
                    @endif
                    @if ($resume['skills'])
                        <div>
                            <h3 style="margin-bottom:10px"><x-maktabgid.icon name="sparkle" :width="18" :height="18" /> {{ __('careers.skills_label') }}</h3>
                            <p style="line-height:1.7">{{ $resume['skills'] }}</p>
                        </div>
                    @endif
                </article>
            @endif
        </div>

        {{-- ===== SIDEBAR ===== --}}
        <aside class="detail-side">
            <div class="side-card">
                <div class="side-price">
                    <b>{{ $resume['salary'] }}</b>
                    <span>{{ __('careers.currency_per_month') }}</span>
                </div>

                @if ($resume['phone'])
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $resume['phone']) }}" class="btn btn-primary side-cta">
                        <x-maktabgid.icon name="phone" :width="16" :height="16" /> {{ $resume['phone'] }}
                    </a>
                @endif

                <ul class="side-facts">
                    <li>
                        <x-maktabgid.icon name="award" :width="17" :height="17" />
                        {{ $resume['exp'] }}
                    </li>
                    @if ($resume['district'])
                        <li>
                            <x-maktabgid.icon name="pin" :width="17" :height="17" />
                            {{ $resume['district'] }}
                        </li>
                    @endif
                    @if ($resume['langs'])
                        <li>
                            <x-maktabgid.icon name="globe" :width="17" :height="17" />
                            {{ $resume['langs'] }}
                        </li>
                    @endif
                </ul>
            </div>
        </aside>

    </div>

    <x-maktabgid.trust-strip />
    <x-maktabgid.cta-band />
    <x-maktabgid.footer />

    <script src="{{ asset('js/maktabgid.js') }}"></script>
</body>
</html>

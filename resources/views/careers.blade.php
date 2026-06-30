<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Vakansiyalar — {{ config('app.name', 'MaktabGID') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,600;12..96,700;12..96,800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/maktabgid.css') }}" />
</head>
<body>

    @php use App\Support\MaktabgidData; @endphp

    <x-maktabgid.nav :categories="MaktabgidData::categories()" />

    <x-maktabgid.page-head icon="briefcase" kicker="Taʼlim sohasida ish" title="Vakansiyalar va rezyumelar" sub="Maktab, bogʻcha va oʻquv markazlari uchun oʻqituvchi va xodimlar bazasi." />

    <div class="wrap section">
        <x-maktabgid.segmented
            :tabs="[
                ['key' => 'vac', 'label' => 'Vakansiyalar (' . count($vacancies) . ')', 'href' => route('careers.index', ['tab' => 'vac'])],
                ['key' => 'res', 'label' => 'Rezyumelar (' . count($resumes) . ')', 'href' => route('careers.index', ['tab' => 'res'])],
            ]"
            :active="$tab"
        />

        @if ($tab === 'res')
            <div class="res-grid" style="margin-top:24px">
                @foreach ($resumes as $r)
                    <div class="res-card">
                        <div class="res-top">
                            <x-maktabgid.avatar :name="$r['name']" :size="44" />
                            <div><b>{{ $r['name'] }}</b><span>{{ $r['role'] }}</span></div>
                        </div>
                        <div class="res-meta">
                            <span><x-maktabgid.icon name="bag" :width="15" :height="15" /> {{ $r['exp'] }}</span>
                            <span><x-maktabgid.icon name="pin" :width="15" :height="15" /> {{ $r['district'] }}</span>
                            <span><x-maktabgid.icon name="globe" :width="15" :height="15" /> {{ $r['langs'] }}</span>
                        </div>
                        <div class="res-foot">
                            <span class="vac-salary">{{ $r['salary'] }} <span>soʻm</span></span>
                            <span class="vac-until">{{ $r['ago'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="vac-grid" style="margin-top:24px">
                @foreach ($vacancies as $v)
                    <a href="{{ route('careers.show', $v['id']) }}" class="vac-card">
                        <span class="vac-type">{{ $v['type'] }}</span>
                        <h3>{{ $v['title'] }}</h3>
                        <div class="vac-org"><span class="av">{{ mb_substr($v['org'], 0, 1) }}</span> {{ $v['org'] }}</div>
                        <div class="vac-foot">
                            <span class="vac-salary">{{ $v['salary'] }} <span>soʻm</span></span>
                            <span class="vac-until"><x-maktabgid.icon name="clock" :width="14" :height="14" /> {{ $v['until'] }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    <x-maktabgid.trust-strip />
    <x-maktabgid.cta-band />
    <x-maktabgid.footer />

    <script src="{{ asset('js/maktabgid.js') }}"></script>
</body>
</html>

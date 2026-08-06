<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ __('careers.page_title') }} — {{ config('app.name', 'MaktabGID') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,600;12..96,700;12..96,800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/maktabgid.css') }}" />
</head>
<body>

    @php
        $moreVacancies = [
            ['id' => 101, 'title' => 'Matematika oʻqituvchisi',  'org' => 'Diplomat International', 'type' => 'Toʻliq stavka', 'salary' => '9 – 14 mln', 'until' => '20 Iyun 2026'],
            ['id' => 102, 'title' => 'Bogʻcha tarbiyachisi',      'org' => 'Maple Bear',             'type' => 'Toʻliq stavka', 'salary' => '5 – 8 mln',  'until' => '25 Iyun 2026'],
            ['id' => 103, 'title' => 'IT / Robototexnika ustozi', 'org' => 'IT Park School',         'type' => 'Yarim stavka',  'salary' => '8 – 12 mln', 'until' => '30 Iyun 2026'],
            ['id' => 104, 'title' => 'IELTS instruktori',         'org' => 'Bright Kids',            'type' => 'Toʻliq stavka', 'salary' => '10 – 16 mln','until' => '18 Iyun 2026'],
        ];
    @endphp

    <x-maktabgid.nav />

    {{-- ===== PAGE HEAD =====
         Vakansiya/rezyume joylash tugmalari bu yerdan olib tashlandi — bu amallar
         faqat tegishli rolning o'z kabinetida bo'ladi (muassasa: institution-cabinet
         "Vakansiyalar", oʻqituvchi: teacher-cabinet "Rezyumelarim"). Ilgari bu yerda
         har qanday login qilgan foydalanuvchi (rolidan qat'i nazar) vakansiya/rezyume
         joylay olardi — bu xato edi. --}}
    <x-maktabgid.page-head
        icon="bag"
        kicker="{{ __('careers.kicker') }}"
        title="{{ __('careers.title') }}"
        sub="{{ __('careers.sub') }}"
    />

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="wrap section">
        <x-maktabgid.segmented
            :tabs="[
                ['key' => 'vac', 'label' => __('careers.vacancies_tab', ['count' => count($vacancies) + count($moreVacancies)]), 'href' => route('careers.index', ['tab' => 'vac'])],
                ['key' => 'res', 'label' => __('careers.resumes_tab', ['count' => count($resumes)]), 'href' => route('careers.index', ['tab' => 'res'])],
            ]"
            :active="$tab"
        />

        @if ($tab === 'res')
            {{-- ===== RESUMES TAB ===== --}}
            <div class="res-grid" style="margin-top:24px">
                @foreach ($resumes as $r)
                    <article class="res-card">
                        <div class="res-top">
                            <x-maktabgid.avatar :name="$r['name']" :size="48" />
                            <div><b>{{ $r['name'] }}</b><span>{{ $r['role'] }}</span></div>
                        </div>
                        <div class="res-meta">
                            <span><x-maktabgid.icon name="award" :width="15" :height="15" /> {{ $r['exp'] }}</span>
                            <span><x-maktabgid.icon name="pin"   :width="15" :height="15" /> {{ $r['district'] }}</span>
                            <span><x-maktabgid.icon name="globe" :width="15" :height="15" /> {{ $r['langs'] }}</span>
                        </div>
                        <div class="res-foot">
                            <div class="vac-salary">{{ $r['salary'] }} <span>UZS</span></div>
                            <button class="btn btn-ghost sm" type="button">
                                <x-maktabgid.icon name="phone" :width="14" :height="14" /> {{ __('careers.contact') }}
                            </button>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            {{-- ===== VACANCIES TAB ===== --}}
            <div class="vac-grid" style="margin-top:24px">
                @foreach ($vacancies as $v)
                    <a href="{{ route('careers.show', $v['id']) }}" class="vac-card">
                        <span class="vac-type">{{ $v['type'] }}</span>
                        <h3>{{ $v['title'] }}</h3>
                        <div class="vac-org"><span class="av">{{ mb_substr($v['org'], 0, 1) }}</span> {{ $v['org'] }}</div>
                        <div class="vac-foot">
                            <div class="vac-salary">{{ $v['salary'] }} <span>UZS</span></div>
                            <span class="vac-until"><x-maktabgid.icon name="cal" :width="14" :height="14" /> {{ $v['until'] }}</span>
                        </div>
                    </a>
                @endforeach
                @foreach ($moreVacancies as $v)
                    <article class="vac-card">
                        <span class="vac-type">{{ $v['type'] }}</span>
                        <h3>{{ $v['title'] }}</h3>
                        <div class="vac-org"><span class="av">{{ mb_substr($v['org'], 0, 1) }}</span> {{ $v['org'] }}</div>
                        <div class="vac-foot">
                            <div class="vac-salary">{{ $v['salary'] }} <span>UZS</span></div>
                            <span class="vac-until"><x-maktabgid.icon name="cal" :width="14" :height="14" /> {{ $v['until'] }}</span>
                        </div>
                    </article>
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

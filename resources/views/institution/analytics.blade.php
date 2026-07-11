@php
    // Mock: ko'rishlar/profilga kirish/saqlash/konversiya dinamikasi va trafik-manba
    // hisoblagichlari hali ulanmagan (StatsController'da "profileViews" => null izohiga
    // qarang). Quyidagi barcha raqamlar demo/namuna ko'rinish uchun — real bo'lganda shu
    // sahifa aynan shu tuzilishda haqiqiy qiymatlar bilan to'ldiriladi.
    $days = ['Du', 'Se', 'Ch', 'Pa', 'Ju', 'Sh', 'Ya'];
    $chart = [
        'cur'  => [520, 610, 480, 705, 640, 900, 865],
        'prev' => [430, 500, 460, 560, 520, 640, 610],
    ];
    $maxVal = max(array_merge($chart['cur'], $chart['prev']));

    $stats = [
        ['icon' => 'eye', 'bg' => 'var(--primary-soft)', 'fg' => 'var(--primary)', 'val' => '4 820', 'label' => "Jami ko'rishlar", 'delta' => '+24%'],
        ['icon' => 'users', 'bg' => '#ece9fc', 'fg' => '#5145d8', 'val' => '2 024', 'label' => 'Profilga kirgan', 'delta' => '+18%'],
        ['icon' => 'heart', 'bg' => '#fde7f3', 'fg' => '#c2247a', 'val' => '530', 'label' => "Saqlovga qo'shildi", 'delta' => '+6%'],
        ['icon' => 'target', 'bg' => 'var(--accent-soft)', 'fg' => 'var(--accent)', 'val' => '9.8%', 'label' => 'Lidga aylanish', 'delta' => '+1.2%'],
    ];

    $sources = [
        ['label' => 'Katalog qidiruvi', 'pct' => 46, 'color' => 'var(--primary)'],
        ['label' => 'Telegram bot', 'pct' => 23, 'color' => '#5145d8'],
        ['label' => 'Tavsiyalar', 'pct' => 16, 'color' => 'var(--accent)'],
        ['label' => 'Instagram', 'pct' => 9, 'color' => '#c2247a'],
        ['label' => 'Boshqa', 'pct' => 6, 'color' => '#c7cdd4'],
    ];
    $donutTotal = '4 820';
    $cum = 0;
    $stops = [];
    foreach ($sources as $s) {
        $stops[] = $s['color'].' '.$cum.'% '.($cum + $s['pct']).'%';
        $cum += $s['pct'];
    }
    $donutGradient = 'conic-gradient('.implode(', ', $stops).')';

    $ageGroups = [
        ['label' => '6-8 yosh', 'pct' => 38],
        ['label' => '9-11 yosh', 'pct' => 34],
        ['label' => '12-14 yosh', 'pct' => 21],
    ];
@endphp

<x-institution.shell
    active="analytics"
    title="Analitika"
    sub="Qiziqish va ko'rishlar tahlili"
    :institution="$institution"
    :organizations="$organizations"
    :counts="$counts"
    :user="$user"
>
    @if ($institution)

    <div class="idash-badge-soft">
        <x-maktabgid.icon name="sparkle" :width="14" :height="14" /> Chuqurroq analitika (haftalik hisobot, PDF eksport) tez orada qo'shiladi — hozircha demo ko'rinish
    </div>

    <div class="idash-stats">
        @foreach ($stats as $s)
            <div class="idash-stat">
                <div class="idash-stat-top">
                    <span class="idash-stat-ico" style="background:{{ $s['bg'] }};color:{{ $s['fg'] }}"><x-maktabgid.icon :name="$s['icon']" :width="18" :height="18" /></span>
                    <span class="idash-stat-delta"><x-maktabgid.icon name="trending" :width="12" :height="12" /> {{ $s['delta'] }}</span>
                </div>
                <div><b>{{ $s['val'] }}</b><span>{{ $s['label'] }}</span></div>
            </div>
        @endforeach
    </div>

    <div class="panel">
        <div class="idash-chart-head">
            <h3><x-maktabgid.icon name="eye" :width="17" :height="17" /> Ko'rishlar dinamikasi</h3>
            <div class="idash-seg">
                <button type="button" class="js-seg-btn on">Hafta</button>
                <button type="button" class="js-seg-btn">Oy</button>
                <button type="button" class="js-seg-btn">Yil</button>
            </div>
        </div>
        <div class="idash-legend">
            <span><i style="background:var(--primary)"></i> Bu hafta</span>
            <span><i style="background:var(--line)"></i> Oldingi hafta</span>
        </div>
        <div class="idash-bars2">
            @foreach ($days as $idx => $d)
                <div class="idash-bcol">
                    <div class="idash-bpair" style="height:100%">
                        <i class="prev" style="height:{{ round($chart['prev'][$idx] / $maxVal * 100) }}%"></i>
                        <i class="cur" style="height:{{ round($chart['cur'][$idx] / $maxVal * 100) }}%"></i>
                    </div>
                    <em>{{ $d }}</em>
                </div>
            @endforeach
        </div>
    </div>

    <div class="idash-split2">
        <div class="panel">
            <div class="panel-head"><h3 style="font-size:16.5px"><x-maktabgid.icon name="target" :width="17" :height="17" /> Qaysi kanaldan kelishdi</h3></div>
            <div class="idash-donut-row">
                <div class="idash-donut" style="background:{{ $donutGradient }}">
                    <div class="idash-donut-center">
                        <b>{{ $donutTotal }}</b>
                        <span>ko'rish</span>
                    </div>
                </div>
                <div class="idash-donut-legend">
                    @foreach ($sources as $s)
                        <div class="idash-donut-legend-row">
                            <i style="background:{{ $s['color'] }}"></i> {{ $s['label'] }}
                            <b>{{ $s['pct'] }}%</b>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-head"><h3 style="font-size:16.5px"><x-maktabgid.icon name="users" :width="17" :height="17" /> Bola yoshi bo'yicha qiziqish</h3></div>
            <div class="idash-agebars">
                @foreach ($ageGroups as $g)
                    <div class="idash-agebar-row">
                        <b>{{ $g['label'] }} <em>{{ $g['pct'] }}%</em></b>
                        <div class="idash-agebar-track"><i style="width:{{ $g['pct'] }}%"></i></div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @endif
</x-institution.shell>

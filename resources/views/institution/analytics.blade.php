@php
    // Real: "Jami ko'rishlar" (InstitutionView), "Saqlovga qo'shildi" (Favorite) va
    // "Lidga aylanish" (Application konversiyasi) — ADR-0002, Faza 2. Trafik-manba
    // (donut) va bola yoshi bo'yicha taqsimot hali mock — hech qanday real hodisada
    // "qayerdan kelgani"/"bola yoshi" yozib olinmaydi, shuning uchun bu ikkisi hozircha
    // namunaviy ko'rinishda qoladi (pastda alohida belgilangan).
    $days = $weekDays;
    $chart = $weekChart;
    $maxVal = $weekMax;

    $prevTotal = array_sum($chart['prev']);
    $curTotal = array_sum($chart['cur']);
    $viewsDelta = $prevTotal > 0 ? round(($curTotal - $prevTotal) / $prevTotal * 100, 1) : ($curTotal > 0 ? 100 : 0);

    $stats = [
        ['icon' => 'eye', 'bg' => 'var(--primary-soft)', 'fg' => 'var(--primary)', 'val' => number_format($totalViews, 0, '.', ' '), 'label' => "Jami ko'rishlar", 'delta' => ($viewsDelta >= 0 ? '+' : '').$viewsDelta.'%'],
        ['icon' => 'heart', 'bg' => '#fde7f3', 'fg' => '#c2247a', 'val' => number_format($totalFavorites, 0, '.', ' '), 'label' => "Saqlovga qo'shildi"],
        ['icon' => 'target', 'bg' => 'var(--accent-soft)', 'fg' => 'var(--accent)', 'val' => $conversionRate.'%', 'label' => 'Lidga aylanish'],
    ];

    // Mock: trafik-manba (qayerdan kelgani hech qayerda yozib olinmaydi).
    $sources = [
        ['label' => 'Katalog qidiruvi', 'pct' => 46, 'color' => 'var(--primary)'],
        ['label' => 'Telegram bot', 'pct' => 23, 'color' => '#5145d8'],
        ['label' => 'Tavsiyalar', 'pct' => 16, 'color' => 'var(--accent)'],
        ['label' => 'Instagram', 'pct' => 9, 'color' => '#c2247a'],
        ['label' => 'Boshqa', 'pct' => 6, 'color' => '#c7cdd4'],
    ];
    $donutTotal = 'Namuna';
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
        <x-maktabgid.icon name="sparkle" :width="14" :height="14" /> Chuqurroq analitika (haftalik hisobot, PDF eksport) tez orada qo'shiladi
    </div>

    <div class="idash-stats">
        @foreach ($stats as $s)
            <div class="idash-stat">
                <div class="idash-stat-top">
                    <span class="idash-stat-ico" style="background:{{ $s['bg'] }};color:{{ $s['fg'] }}"><x-maktabgid.icon :name="$s['icon']" :width="18" :height="18" /></span>
                    @if (isset($s['delta']))
                        <span class="idash-stat-delta"><x-maktabgid.icon name="trending" :width="12" :height="12" /> {{ $s['delta'] }}</span>
                    @endif
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
            <div class="panel-head"><h3 style="font-size:16.5px"><x-maktabgid.icon name="target" :width="17" :height="17" /> Qaysi kanaldan kelishdi <span style="font-weight:600;color:var(--ink-3);font-size:11.5px">(namuna)</span></h3></div>
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
            <div class="panel-head"><h3 style="font-size:16.5px"><x-maktabgid.icon name="users" :width="17" :height="17" /> Bola yoshi bo'yicha qiziqish <span style="font-weight:600;color:var(--ink-3);font-size:11.5px">(namuna)</span></h3></div>
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

@php
    // Mock: profil ko'rishlar/qidiruvda chiqish/reyting dinamikasi hisoblagichlari hali yo'q
    // (StatsController'da "profileViews" => null izohiga qarang). Namuna grafiklar shu bo'lim
    // qanday ko'rinishini ko'rsatadi.
    $days = ['Du', 'Se', 'Ch', 'Pa', 'Ju', 'Sh', 'Ya'];
    $viewsSeries = [58, 64, 49, 72, 80, 96, 88];
    $maxViews = max($viewsSeries);

    $sources = [
        ['label' => "Qidiruv (katalog)", 'pct' => 46],
        ['label' => "To'g'ridan-to'g'ri", 'pct' => 27],
        ['label' => "Telegram bot", 'pct' => 18],
        ['label' => "Boshqa", 'pct' => 9],
    ];
@endphp

<x-institution.shell
    active="analytics"
    title="Analitika"
    sub="Profilingiz statistikasi va dinamikasi"
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
        <div class="idash-stat">
            <div class="idash-stat-top">
                <span class="idash-stat-ico" style="background:var(--primary-soft);color:var(--primary)"><x-maktabgid.icon name="eye" :width="18" :height="18" /></span>
            </div>
            <div><b>{{ array_sum($viewsSeries) }}</b><span>Jami ko'rishlar (7 kun)</span></div>
        </div>
        <div class="idash-stat">
            <div class="idash-stat-top">
                <span class="idash-stat-ico" style="background:var(--accent-soft);color:#b45309"><x-maktabgid.icon name="star" :width="18" :height="18" /></span>
            </div>
            <div><b>{{ $institution->rating > 0 ? $institution->rating : 'Yangi' }}</b><span>O'rtacha reyting</span></div>
        </div>
        <div class="idash-stat">
            <div class="idash-stat-top">
                <span class="idash-stat-ico" style="background:#ece9fc;color:#5145d8"><x-maktabgid.icon name="heart" :width="18" :height="18" /></span>
            </div>
            <div><b>{{ $institution->favorites()->count() }}</b><span>Sevimlilarga qo'shildi</span></div>
        </div>
        <div class="idash-stat">
            <div class="idash-stat-top">
                <span class="idash-stat-ico" style="background:#fde7f3;color:#c2247a"><x-maktabgid.icon name="target" :width="18" :height="18" /></span>
            </div>
            <div><b>{{ $institution->review_count }}</b><span>Sharhlar soni</span></div>
        </div>
    </div>

    <div class="panel">
        <div class="idash-chart-head">
            <h3><x-maktabgid.icon name="trending" :width="17" :height="17" /> Ko'rishlar dinamikasi</h3>
            <span class="idash-chart-meta">Oxirgi 7 kun</span>
        </div>
        <div class="bars">
            @foreach ($days as $idx => $d)
                <div class="bar-col">
                    <span class="bar" style="height:{{ round($viewsSeries[$idx] / $maxViews * 100) }}%"></span>
                    <em>{{ $d }}</em>
                </div>
            @endforeach
        </div>
    </div>

    <div class="panel">
        <div class="panel-head"><h3 style="font-size:16.5px">Trafik manbalari</h3></div>
        <div class="idash-funnel">
            @foreach ($sources as $s)
                <div class="idash-funnel-row">
                    <span class="idash-funnel-label">{{ $s['label'] }}</span>
                    <span class="idash-funnel-track"><i style="width:{{ $s['pct'] }}%"></i></span>
                    <span class="idash-funnel-val">{{ $s['pct'] }}%</span>
                </div>
            @endforeach
        </div>
    </div>

    @endif
</x-institution.shell>

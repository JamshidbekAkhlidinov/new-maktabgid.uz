@php
    // Mock: kabinet ichidagi vakansiya boshqaruvi (nomzodlar, holat) — real Vacancy modeli
    // mavjud (careers sahifasida ishlatiladi), lekin bu boshqaruv paneli hali ulanmagan,
    // shuning uchun namunaviy ro'yxat bilan ko'rsatiladi.
    $mockVacancies = [
        ['title' => 'Ingliz tili o\'qituvchisi', 'type' => 'To\'liq stavka', 'status' => 'active', 'stLabel' => 'Faol', 'applicants' => 12, 'until' => '30-avgustgacha', 'salary' => '6 000 000'],
        ['title' => 'Boshlang\'ich sinf o\'qituvchisi', 'type' => 'To\'liq stavka', 'status' => 'active', 'stLabel' => 'Faol', 'applicants' => 8, 'until' => '15-avgustgacha', 'salary' => '5 500 000'],
        ['title' => 'IT / dasturlash to\'garak rahbari', 'type' => 'Yarim stavka', 'status' => 'review', 'stLabel' => 'Ko\'rib chiqilmoqda', 'applicants' => 5, 'until' => '10-sentabrgacha', 'salary' => '4 000 000'],
        ['title' => 'Psixolog', 'type' => 'To\'liq stavka', 'status' => 'closed', 'stLabel' => 'Yopilgan', 'applicants' => 3, 'until' => 'Yakunlangan', 'salary' => '4 500 000'],
    ];
    $statusStyle = [
        'active' => ['bg' => 'var(--primary-soft)', 'color' => 'var(--primary-ink)'],
        'review' => ['bg' => 'var(--accent-soft)', 'color' => '#b45309'],
        'closed' => ['bg' => 'var(--surface-2)', 'color' => 'var(--ink-3)'],
    ];
    $totalApplicants = collect($mockVacancies)->sum('applicants');
@endphp

<x-institution.shell
    active="vacancies"
    title="Vakansiyalar"
    sub="Muassasangiz uchun xodim qidiring"
    :institution="$institution"
    :organizations="$organizations"
    :counts="$counts"
    :user="$user"
>
    @if ($institution)

    <div class="idash-toolbar">
        <span class="idash-chart-meta">{{ count($mockVacancies) }} ta e'lon · {{ $totalApplicants }} nomzod · e'lon narxi 100 000 so'm</span>
        <a href="{{ route('careers.index') }}" class="btn btn-primary sm">
            <x-maktabgid.icon name="plus" :width="15" :height="15" /> Vakansiya ochish
        </a>
    </div>

    <div class="idash-vac-grid">
        @foreach ($mockVacancies as $v)
            <div class="idash-vac-card">
                <div class="idash-vac-top">
                    <span class="idash-pill-neutral" style="background:var(--primary-soft);color:var(--primary-ink)">{{ $v['type'] }}</span>
                    <span class="idash-status-pill" style="background:{{ $statusStyle[$v['status']]['bg'] }};color:{{ $statusStyle[$v['status']]['color'] }}">{{ $v['stLabel'] }}</span>
                </div>
                <h3>{{ $v['title'] }}</h3>
                <div class="idash-vac-meta">
                    <span><x-maktabgid.icon name="users" :width="15" :height="15" /> {{ $v['applicants'] }} nomzod</span>
                    <span><x-maktabgid.icon name="cal" :width="15" :height="15" /> {{ $v['until'] }}</span>
                </div>
                <div class="idash-vac-foot">
                    <span class="idash-vac-price">{{ $v['salary'] }} <span>so'm</span></span>
                    <div class="idash-vac-actions">
                        <button type="button" class="idash-lead-iconbtn" title="Tahrirlash"><x-maktabgid.icon name="edit" :width="14" :height="14" /></button>
                        <button type="button" class="idash-lead-iconbtn danger" title="O'chirish"><x-maktabgid.icon name="close" :width="14" :height="14" /></button>
                        <button type="button" class="idash-vac-cand">Nomzodlar ({{ $v['applicants'] }})</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="idash-badge-soft">
        <x-maktabgid.icon name="sparkle" :width="14" :height="14" /> Bu bo'lim demo ma'lumot bilan ko'rsatilmoqda — nomzodlar boshqaruvi tez orada ulanadi
    </div>

    @endif
</x-institution.shell>

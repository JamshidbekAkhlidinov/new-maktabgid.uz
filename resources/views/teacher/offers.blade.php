@php
    // Mock: muassasalardan kelgan takliflar — real Vacancy/Resume o'rtasidagi bog'lanish
    // hali modellashtirilmagan, shuning uchun namunaviy ro'yxat.
    $offers = [
        ['role' => 'Ingliz tili o\'qituvchisi', 'org' => 'Bilim Ziyo maktabi', 'salary' => '6 000 000', 'status' => 'new', 'stLabel' => 'Yangi', 'ago' => '2 soat oldin', 'grad' => 'linear-gradient(140deg,#0e8a86,#0a625e)'],
        ['role' => 'IELTS mentor', 'org' => 'Cambridge School', 'salary' => '7 500 000', 'status' => 'new', 'stLabel' => 'Yangi', 'ago' => 'Kecha', 'grad' => 'linear-gradient(140deg,#2f6fed,#1c4fc2)'],
        ['role' => 'Ingliz tili to\'garak rahbari', 'org' => 'IT Park School', 'salary' => '5 000 000', 'status' => 'seen', 'stLabel' => 'Ko\'rildi', 'ago' => '3 kun oldin', 'grad' => 'linear-gradient(140deg,#6d5cf6,#4535c9)'],
        ['role' => 'Ingliz tili o\'qituvchisi', 'org' => 'Yangi Avlod maktabi', 'salary' => '5 800 000', 'status' => 'declined', 'stLabel' => 'Rad etilgan', 'ago' => '5 kun oldin', 'grad' => 'linear-gradient(140deg,#79828f,#4a5360)'],
    ];
    $offerStatusStyle = [
        'new' => ['bg' => 'var(--primary-soft)', 'color' => 'var(--primary-ink)'],
        'seen' => ['bg' => 'var(--accent-soft)', 'color' => '#b45309'],
        'declined' => ['bg' => 'var(--surface-2)', 'color' => 'var(--ink-3)'],
    ];
@endphp

<x-teacher.shell active="offers" title="Takliflar" sub="Muassasalardan kelgan takliflar" :teacher="$teacher" :counts="$counts">

    <div class="panel">
        <h3 style="font-size:18px;margin-bottom:16px">Muassasalardan takliflar</h3>
        <div style="display:flex;flex-direction:column;gap:10px">
            @foreach ($offers as $o)
                <div class="idash-offer-row" style="grid-template-columns:auto 1fr auto auto">
                    <span class="idash-offer-ico" style="background:{{ $o['grad'] }}">{{ \App\Support\MaktabgidData::monogram($o['org']) }}</span>
                    <div class="idash-offer-main">
                        <b>{{ $o['role'] }}</b>
                        <span>{{ $o['org'] }} · {{ $o['salary'] }} so'm · {{ $o['ago'] }}</span>
                    </div>
                    <span class="idash-status-pill" style="background:{{ $offerStatusStyle[$o['status']]['bg'] }};color:{{ $offerStatusStyle[$o['status']]['color'] }}">{{ $o['stLabel'] }}</span>
                    <button type="button" class="btn btn-primary sm">Javob berish</button>
                </div>
            @endforeach
        </div>
    </div>

    <div class="idash-badge-soft">
        <x-maktabgid.icon name="sparkle" :width="14" :height="14" /> Bu bo'lim demo ma'lumot bilan ko'rsatilmoqda
    </div>

</x-teacher.shell>

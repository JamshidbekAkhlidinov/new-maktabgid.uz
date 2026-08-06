@php
    // Mock: ustoz kabineti hali real ma'lumotga ulanmagan (TeacherCabinetController'ga qarang) —
    // shu sahifadagi barcha statistika va ro'yxatlar namunaviy.
    $stats = [
        ['value' => '340', 'label' => __('cabinet_teacher.stat_resume_views')],
        ['value' => '18', 'label' => __('cabinet_teacher.stat_new_offers')],
        ['value' => '12', 'label' => __('cabinet_teacher.stat_matching_vacancies')],
        ['value' => '4.9', 'label' => __('cabinet_teacher.stat_institution_rating')],
    ];
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
    $offerStatusLabel = [
        'new' => __('cabinet_teacher.offer_status_new'),
        'seen' => __('cabinet_teacher.offer_status_seen'),
        'declined' => __('cabinet_teacher.offer_status_declined'),
    ];
@endphp

<x-teacher.shell active="dashboard" title="{{ __('cabinet_teacher.nav_dashboard') }}" sub="{{ __('cabinet_teacher.dashboard_sub') }}" :teacher="$teacher" :counts="$counts">

    {{-- Diqqat: $teacher null bo'lishi mumkin (mehmon /teacher-cabinet ga kirsa) — shell
         o'zining @unless($teacher) bilan "kirish kerak" ekranini ko'rsatadi, lekin bu slot
         baribir kompilyatsiya vaqtida bajariladi, shuning uchun $teacher['...'] ni @if bilan
         qo'riqlash shart (parent/dashboard.blade.php dagi bir xil xato — ErrorException). --}}
    @if ($teacher)
    <div class="idash-t-hero">
        <div class="idash-t-hero-card">
            <span class="idash-t-hero-ava">{{ \App\Support\MaktabgidData::monogram($teacher['name']) }}</span>
            <div class="idash-t-hero-main">
                <b>{{ $teacher['name'] }}</b>
                <p>{{ $teacher['role'] }} · {{ $teacher['exp'] }}</p>
                <div style="margin-top:14px">
                    <div class="idash-t-hero-bar-row"><span>{{ __('cabinet_teacher.profile_completeness') }}</span><span>{{ $teacher['completeness'] }}%</span></div>
                    <div class="idash-t-hero-track"><i style="width:{{ $teacher['completeness'] }}%"></i></div>
                </div>
            </div>
        </div>
        <div class="idash-t-hero-stats">
            @foreach ($stats as $s)
                <div class="idash-mini-stat"><b>{{ $s['value'] }}</b><span>{{ $s['label'] }}</span></div>
            @endforeach
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <h3 style="font-size:18px">{{ __('cabinet_teacher.recent_offers') }}</h3>
            <a href="{{ route('teacher.cabinet.offers') }}" class="btn btn-ghost sm">{{ __('cabinet_teacher.see_all') }} <x-maktabgid.icon name="arrowR" :width="15" :height="15" /></a>
        </div>
        <div style="display:flex;flex-direction:column;gap:10px">
            @foreach ($offers as $o)
                <div class="idash-offer-row">
                    <span class="idash-offer-ico" style="background:{{ $o['grad'] }}">{{ \App\Support\MaktabgidData::monogram($o['org']) }}</span>
                    <div class="idash-offer-main">
                        <b>{{ $o['role'] }}</b>
                        <span>{{ $o['org'] }} · {{ $o['salary'] }} {{ __('cabinet_teacher.currency_sum') }}</span>
                    </div>
                    <span class="idash-status-pill" style="background:{{ $offerStatusStyle[$o['status']]['bg'] }};color:{{ $offerStatusStyle[$o['status']]['color'] }}">{{ $offerStatusLabel[$o['status']] ?? $o['stLabel'] }}</span>
                    <span style="font-size:11.5px;color:var(--ink-3);font-weight:700;white-space:nowrap">{{ $o['ago'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="idash-badge-soft">
        <x-maktabgid.icon name="sparkle" :width="14" :height="14" /> {{ __('cabinet_teacher.demo_notice') }}
    </div>
    @endif

</x-teacher.shell>

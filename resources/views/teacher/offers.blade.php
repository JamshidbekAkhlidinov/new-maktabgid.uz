@php
    // Real ro'yxat — App\Models\VacancyApplication (teacher_user_id = joriy ustoz).
    // "Takliflar" nomi saqlanib qolgan (sidebar/route nomi), lekin mazmuni — ustoz
    // yuborgan arizalar va muassasaning javobi (kutilmoqda/qabul/rad) — ADR-0002, Faza 2.
    $offerStatusStyle = [
        'pending' => ['bg' => 'var(--accent-soft)', 'color' => '#b45309', 'label' => __('cabinet_teacher.offer_status_pending')],
        'accepted' => ['bg' => 'var(--primary-soft)', 'color' => 'var(--primary-ink)', 'label' => __('cabinet_teacher.offer_status_accepted')],
        'rejected' => ['bg' => 'var(--surface-2)', 'color' => 'var(--ink-3)', 'label' => __('cabinet_teacher.offer_status_rejected')],
    ];
@endphp

<x-teacher.shell active="offers" title="{{ __('cabinet_teacher.nav_offers') }}" sub="{{ __('cabinet_teacher.offers_sub') }}" :teacher="$teacher" :counts="$counts">

    <div class="panel">
        <h3 style="font-size:18px;margin-bottom:16px">{{ __('cabinet_teacher.offers_heading') }}</h3>

        @if ($offers->isEmpty())
            <div class="idash-badge-soft">
                <x-maktabgid.icon name="sparkle" :width="14" :height="14" /> {{ __('cabinet_teacher.offers_empty_prefix') }} <a href="{{ route('teacher.cabinet.vacancies') }}">{{ __('cabinet_teacher.nav_vacancies') }}</a> {{ __('cabinet_teacher.offers_empty_suffix') }}
            </div>
        @else
            <div style="display:flex;flex-direction:column;gap:10px">
                @foreach ($offers as $o)
                    @php($st = $offerStatusStyle[$o->status] ?? $offerStatusStyle['pending'])
                    <div class="idash-offer-row" style="grid-template-columns:auto 1fr auto">
                        <span class="idash-offer-ico" style="background:linear-gradient(140deg,#2f6fed,#1c4fc2)">{{ \App\Support\MaktabgidData::monogram($o->vacancy?->org_name ?? '?') }}</span>
                        <div class="idash-offer-main">
                            <b>{{ $o->vacancy?->title ?? __('cabinet_teacher.deleted_vacancy') }}</b>
                            <span>{{ $o->vacancy?->org_name }} · {{ $o->created_at->diffForHumans() }}</span>
                        </div>
                        <span class="idash-status-pill" style="background:{{ $st['bg'] }};color:{{ $st['color'] }}">{{ $st['label'] }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</x-teacher.shell>

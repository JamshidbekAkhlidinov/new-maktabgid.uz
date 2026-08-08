@php
    // Ko'rishlar/lidlar/konversiya (2026-08-08) — endi real (InstitutionCabinetController::
    // dashboard(), InstitutionView/Application asosida, Analitika sahifasi bilan bir xil manba).
    // Faqat quyidagi banner ($freeReachMock) hali mock — bepul rejadagi ko'rinish cheklovi
    // billing tizimi ulanmaguncha real hisoblanmaydi.
    $days = __('cabinet_institution.week_days');
    $maxVal = $viewsChartMax;
    $chartTotal = $viewsChartTotal;

    $freeReachMock = 5; // mock — bepul rejadagi ko'rinish cheklovi (billing ulanganda realdan olinadi)

    // "So'nggi harakatlar" — har bir hodisa turi uchun belgi/rang.
    $activityStyles = [
        'lead' => ['icon' => 'users', 'bg' => 'var(--primary-soft)', 'fg' => 'var(--primary)'],
        'conversation' => ['icon' => 'chat', 'bg' => '#ece9fc', 'fg' => '#5145d8'],
        'excursion' => ['icon' => 'bus', 'bg' => 'var(--accent-soft)', 'fg' => '#b45309'],
        'review' => ['icon' => 'star', 'bg' => 'var(--accent-soft)', 'fg' => '#b45309'],
        'views' => ['icon' => 'eye', 'bg' => 'var(--primary-soft)', 'fg' => 'var(--primary)'],
    ];
@endphp

<x-institution.shell
    active="dashboard"
    :title="__('cabinet_institution.nav_dashboard')"
    :sub="__('cabinet_institution.dashboard_sub')"
    :institution="$institution"
    :organizations="$organizations"
    :counts="$counts"
    :user="$user"
>

    <div class="idash-banner">
        <div>
            <h2>{{ __('cabinet_institution.banner_title', ['count' => $freeReachMock]) }}</h2>
            <p>{{ __('cabinet_institution.banner_text') }}</p>
        </div>
        <a href="{{ route('institution.cabinet.plans') }}" class="btn btn-white">
            <x-maktabgid.icon name="sparkle" :width="16" :height="16" /> {{ __('cabinet_institution.get_package') }}
        </a>
    </div>

    <div class="idash-stats">
        <div class="idash-stat">
            <div class="idash-stat-top">
                <span class="idash-stat-ico" style="background:var(--primary-soft);color:var(--primary)"><x-maktabgid.icon name="eye" :width="18" :height="18" /></span>
                <span class="idash-stat-delta"><x-maktabgid.icon name="trending" :width="12" :height="12" /> +{{ $viewsDelta }}%</span>
            </div>
            <div>
                <b>{{ $views7d }}</b>
                <span>{{ __('cabinet_institution.views_7d') }}</span>
            </div>
        </div>

        <div class="idash-stat">
            <div class="idash-stat-top">
                <span class="idash-stat-ico" style="background:#ece9fc;color:#5145d8"><x-maktabgid.icon name="users" :width="18" :height="18" /></span>
                <span class="idash-stat-delta"><x-maktabgid.icon name="trending" :width="12" :height="12" /> +{{ $newLeadsDelta }}</span>
            </div>
            <div>
                <b>{{ $newLeads7d }}</b>
                <span>{{ __('cabinet_institution.new_leads_7d') }}</span>
            </div>
        </div>

        <div class="idash-stat">
            <div class="idash-stat-top">
                <span class="idash-stat-ico" style="background:var(--accent-soft);color:#b45309"><x-maktabgid.icon name="ticket" :width="18" :height="18" /></span>
                <span class="idash-stat-delta"><x-maktabgid.icon name="trending" :width="12" :height="12" /> +{{ $counts['excursions'] }}</span>
            </div>
            <div>
                <b>{{ $excursionsTotal }}</b>
                <span>{{ __('cabinet_institution.excursion_applications') }}</span>
            </div>
        </div>

        <div class="idash-stat">
            <div class="idash-stat-top">
                <span class="idash-stat-ico" style="background:#fde7f3;color:#c2247a"><x-maktabgid.icon name="target" :width="18" :height="18" /></span>
                <span class="idash-stat-delta"><x-maktabgid.icon name="trending" :width="12" :height="12" /> +{{ $conversionDelta }}%</span>
            </div>
            <div>
                <b>{{ $conversionRate }}%</b>
                <span>{{ __('cabinet_institution.lead_conversion') }}</span>
            </div>
        </div>
    </div>

    @php
        // Konversiya yo'li — bosqichlar, barchasi real, oxirgi 7 kun (2026-08-08).
        // Diqqat: "Ko'rishlar" va "Profilga kirdi" bir xil songa teng — chunki
        // InstitutionView aynan profil sahifasi ochilganda yoziladi (routes/web.php,
        // /{slug}), ya'ni "ko'rish" va "profilga kirish" bu ma'lumot modelida bitta
        // hodisa (qidiruv natijasidagi kartochka ko'rinishi alohida kuzatilmaydi).
        $funnelViews = max($views7d, 1);
        $funnelProfile = $views7d;
        $funnelLeads = $newLeads7d;
        $funnelExcChat = $funnelExcursionChat7d;
        $funnelConfirmed = $funnelConfirmed7d;

        $funnelStages = [
            ['label' => __('cabinet_institution.funnel_views'), 'val' => $funnelViews, 'color' => 'var(--primary)'],
            ['label' => __('cabinet_institution.funnel_profile'), 'val' => $funnelProfile, 'color' => '#2f6fed'],
            ['label' => __('cabinet_institution.funnel_leads'), 'val' => $funnelLeads, 'color' => '#6d5cf6'],
            ['label' => __('cabinet_institution.funnel_excursion_chat'), 'val' => $funnelExcChat, 'color' => 'var(--accent)'],
            ['label' => __('cabinet_institution.funnel_enrolled'), 'val' => $funnelConfirmed, 'color' => 'var(--ok)'],
        ];
        $funnelMaxBar = 74; // eng uzun panelning konteynerga nisbatan maksimal eni (%)
    @endphp

    <div class="idash-row">
        <div class="idash-col-left">
            <div class="panel">
                <div class="idash-chart-head">
                    <h3><x-maktabgid.icon name="eye" :width="17" :height="17" /> {{ __('cabinet_institution.views_dynamics') }}</h3>
                    <span class="idash-chart-meta">{{ __('cabinet_institution.last_7_days_total', ['total' => $chartTotal]) }}</span>
                </div>
                <div class="idash-legend">
                    <span><i style="background:var(--primary)"></i> {{ __('cabinet_institution.this_week') }}</span>
                    <span><i style="background:var(--line)"></i> {{ __('cabinet_institution.previous_week') }}</span>
                </div>
                <div class="idash-bars2">
                    @foreach ($days as $idx => $d)
                        <div class="idash-bcol">
                            <div class="idash-bpair" style="height:100%">
                                <i class="prev" style="height:{{ round($viewsChart['prev'][$idx] / $maxVal * 100) }}%"></i>
                                <i class="cur" style="height:{{ round($viewsChart['cur'][$idx] / $maxVal * 100) }}%"></i>
                            </div>
                            <em>{{ $d }}</em>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="panel">
                <div class="panel-head"><h3 style="font-size:16.5px">{{ __('cabinet_institution.conversion_path') }}</h3><span class="idash-chart-meta">{{ __('cabinet_institution.view_to_enroll') }}</span></div>
                <div class="idash-funnel2">
                    @foreach ($funnelStages as $stage)
                        @php $pct = $funnelViews > 0 ? min(100, round($stage['val'] / $funnelViews * 100)) : 0; @endphp
                        <div class="idash-funnel2-row">
                            <div class="idash-funnel2-bar" style="width:{{ max($pct / 100 * $funnelMaxBar, 7) }}%;background:{{ $stage['color'] }}">
                                {{ $stage['val'] }}
                            </div>
                            <div class="idash-funnel2-meta">
                                <b>{{ $stage['label'] }}</b>
                                <span>{{ __('cabinet_institution.pct_of_views', ['pct' => $pct]) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="idash-col-right">
            <div class="panel">
                <div class="panel-head"><h3 style="font-size:16.5px">{{ __('cabinet_institution.recent_activity') }}</h3></div>
                @if ($activity->isEmpty())
                    <div class="empty" style="padding:20px 10px">
                        <span class="empty-ico"><x-maktabgid.icon name="sparkle" :width="22" :height="22" /></span>
                        <p>{{ __('cabinet_institution.no_activity_yet') }}</p>
                    </div>
                @else
                    <div class="idash-activity">
                        @foreach ($activity as $a)
                            @php $style = $activityStyles[$a['type']] ?? $activityStyles['lead']; @endphp
                            <div class="idash-act-row">
                                <span class="idash-act-ico" style="background:{{ $style['bg'] }};color:{{ $style['fg'] }}">
                                    <x-maktabgid.icon :name="$style['icon']" :width="16" :height="16" />
                                </span>
                                <div class="idash-act-main">
                                    <b>{{ $a['text'] }}</b>
                                    <span>{{ $a['subtitle'] ?? \Illuminate\Support\Carbon::parse($a['time'])->diffForHumans() }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="panel">
                <div class="panel-head"><h3 style="font-size:16.5px">{{ __('cabinet_institution.quick_actions') }}</h3></div>
                <div class="idash-quick">
                    <a class="idash-quick-link" href="{{ route('institution.cabinet.leads') }}">
                        <span class="idash-quick-ico" style="background:#ece9fc;color:#5145d8"><x-maktabgid.icon name="users" :width="16" :height="16" /></span>
                        {{ __('cabinet_institution.view_leads') }}
                        <x-maktabgid.icon name="chevronR" :width="16" :height="16" />
                    </a>
                    <a class="idash-quick-link" href="{{ route('institution.cabinet.conversations') }}">
                        <span class="idash-quick-ico" style="background:var(--primary-soft);color:var(--primary)"><x-maktabgid.icon name="chat" :width="16" :height="16" /></span>
                        {{ __('cabinet_institution.reply_to_parents') }}
                        <x-maktabgid.icon name="chevronR" :width="16" :height="16" />
                    </a>
                    <a class="idash-quick-link" href="{{ route('institution.cabinet.profile') }}">
                        <span class="idash-quick-ico" style="background:var(--accent-soft);color:#b45309"><x-maktabgid.icon name="image" :width="16" :height="16" /></span>
                        {{ __('cabinet_institution.add_photos_portfolio') }}
                        <x-maktabgid.icon name="chevronR" :width="16" :height="16" />
                    </a>
                    <a class="idash-quick-link idash-quick-cta" href="{{ route('institution.cabinet.plans') }}">
                        <x-maktabgid.icon name="sparkle" :width="16" :height="16" /> {{ __('cabinet_institution.activate_package') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

</x-institution.shell>

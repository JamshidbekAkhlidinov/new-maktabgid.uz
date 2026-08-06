@php
    $tabs = [
        ['key' => 'all', 'label' => __('cabinet_institution.tab_all'), 'count' => $excursionStats['total']],
        ['key' => 'pending', 'label' => __('cabinet_institution.tab_pending'), 'count' => $excursionStats['pending']],
        ['key' => 'confirmed', 'label' => __('cabinet_institution.tab_confirmed'), 'count' => $excursionStats['confirmed']],
        ['key' => 'completed', 'label' => __('cabinet_institution.tab_completed'), 'count' => $excursionStats['completed']],
    ];
@endphp

<x-institution.shell
    active="excursions"
    title="{{ __('cabinet_institution.nav_excursions') }}"
    sub="{{ __('cabinet_institution.excursions_sub') }}"
    :institution="$institution"
    :organizations="$organizations"
    :counts="$counts"
    :user="$user"
>
    @if ($institution)

    <div class="idash-stats">
        <div class="idash-stat">
            <div class="idash-stat-top">
                <span class="idash-stat-ico" style="background:var(--primary-soft);color:var(--primary)"><x-maktabgid.icon name="bus" :width="18" :height="18" /></span>
            </div>
            <div><b>{{ $excursionStats['total'] }}</b><span>{{ __('cabinet_institution.total_applications') }}</span></div>
        </div>
        <div class="idash-stat">
            <div class="idash-stat-top">
                <span class="idash-stat-ico" style="background:var(--accent-soft);color:#b45309"><x-maktabgid.icon name="cal" :width="18" :height="18" /></span>
            </div>
            <div><b>{{ $excursionStats['pending'] }}</b><span>{{ __('cabinet_institution.awaiting_confirmation') }}</span></div>
        </div>
        <div class="idash-stat">
            <div class="idash-stat-top">
                <span class="idash-stat-ico" style="background:#ece9fc;color:#5145d8"><x-maktabgid.icon name="check" :width="18" :height="18" /></span>
            </div>
            <div><b>{{ $excursionStats['confirmed'] }}</b><span>{{ __('cabinet_institution.tab_confirmed') }}</span></div>
        </div>
        <div class="idash-stat">
            <div class="idash-stat-top">
                <span class="idash-stat-ico" style="background:#fde7f3;color:#c2247a"><x-maktabgid.icon name="badge" :width="18" :height="18" /></span>
            </div>
            <div><b>{{ $excursionStats['completed'] }}</b><span>{{ __('cabinet_institution.completed_past') }}</span></div>
        </div>
    </div>

    <div class="idash-lead-toolbar">
        <div class="idash-lead-tabs">
            @foreach ($tabs as $t)
                <button type="button" class="idash-lead-tab js-filter-tab{{ $t['key'] === 'all' ? ' on' : '' }}" data-status="{{ $t['key'] }}">
                    {{ $t['label'] }} <em>{{ $t['count'] }}</em>
                </button>
            @endforeach
        </div>
    </div>

    <div class="panel">
        @if ($applications->isEmpty())
            <div class="empty">
                <span class="empty-ico"><x-maktabgid.icon name="ticket" :width="26" :height="26" /></span>
                <p>{{ __('cabinet_institution.no_applications_yet') }}</p>
            </div>
        @else
            <div class="idash-lead-table">
                <div class="idash-lead-head">
                    <span>{{ __('cabinet_institution.col_parent') }}</span>
                    <span>{{ __('cabinet_institution.col_child') }}</span>
                    <span>{{ __('cabinet_institution.col_phone') }}</span>
                    <span>{{ __('cabinet_institution.col_datetime') }}</span>
                    <span>{{ __('cabinet_institution.col_status') }}</span>
                    <span>{{ __('cabinet_institution.col_action') }}</span>
                </div>

                @foreach ($applications as $app)
                    @php
                        $childBits = array_filter([$app->child_name, $app->child_age ? __('cabinet_institution.age_years', ['age' => $app->child_age]) : ($app->target_grade ?: $app->current_grade)]);
                    @endphp
                    <div class="idash-lead-row js-filter-row" data-app-id="{{ $app->id }}" data-status="{{ $app->status }}">
                        <div class="idash-lead-parent">
                            <x-maktabgid.avatar :name="$app->parent_name" :size="38" />
                            <div>
                                <b>{{ $app->parent_name }}</b>
                                <span>{{ $app->note ? \Illuminate\Support\Str::limit($app->note, 34) : __('cabinet_institution.no_note') }}</span>
                            </div>
                        </div>
                        <div class="idash-lead-child">
                            <b>{{ $childBits ? implode(', ', $childBits) : '—' }}</b>
                        </div>
                        <div class="idash-lead-phone">
                            <x-maktabgid.icon name="phone" :width="14" :height="14" /> {{ $app->parent_phone }}
                        </div>
                        <div class="idash-datetime">
                            <x-maktabgid.icon name="cal" :width="14" :height="14" />
                            {{ $app->scheduled_at?->format('j-M, H:i') ?? $app->created_at->format('j.m, H:i') }}
                        </div>
                        <div>
                            <span class="idash-status-dot {{ $app->status }}">{{ $statusLabels[$app->status] ?? $app->status }}</span>
                        </div>
                        <div class="idash-lead-actions">
                            @if ($app->status === 'pending')
                                <button type="button" class="btn btn-primary sm" data-app-status="confirmed">{{ __('cabinet_institution.confirm') }}</button>
                                <button type="button" class="idash-lead-iconbtn danger" data-app-status="rejected" title="{{ __('cabinet_institution.reject') }}">
                                    <x-maktabgid.icon name="close" :width="16" :height="16" />
                                </button>
                            @elseif ($app->status === 'confirmed')
                                <button type="button" class="btn btn-ghost sm" data-app-status="completed">{{ __('cabinet_institution.finish') }}</button>
                            @else
                                <a class="idash-lead-iconbtn" href="tel:{{ $app->parent_phone }}" title="{{ __('cabinet_institution.call') }}">
                                    <x-maktabgid.icon name="phone" :width="16" :height="16" />
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @endif
</x-institution.shell>

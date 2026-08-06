@php
    // Real ro'yxat — App\Models\Application (type=enrollment). Alohida "lead" jadvali
    // qurish shart emas edi: sayt/kabinetdagi "Ariza yuborish" formasi enrollment turini
    // shu maqsadda ishlatadi (excursion turi "Ekskursiyalar" sahifasida) — ADR-0002, Faza 2.
    // Holat o'zgartirish tugmalari mavjud [data-app-status]/[data-app-id] umumiy JS
    // mexanizmi orqali ishlaydi (excursions.blade.php bilan bir xil, InboxController@updateStatus).
    $tabs = [
        ['key' => 'all', 'label' => __('cabinet_institution.tab_all'), 'count' => $leadStats['total']],
        ['key' => 'pending', 'label' => __('cabinet_institution.tab_new'), 'count' => $leadStats['pending']],
        ['key' => 'confirmed', 'label' => __('cabinet_institution.tab_confirmed'), 'count' => $leadStats['confirmed']],
        ['key' => 'rejected', 'label' => __('cabinet_institution.tab_rejected'), 'count' => $leadStats['rejected']],
    ];
@endphp

<x-institution.shell
    active="leads"
    title="{{ __('cabinet_institution.nav_leads') }}"
    sub="{{ __('cabinet_institution.leads_sub') }}"
    :institution="$institution"
    :organizations="$organizations"
    :counts="$counts"
    :user="$user"
>
    @if ($institution)

    <div class="idash-stats">
        <div class="idash-stat">
            <div class="idash-stat-top">
                <span class="idash-stat-ico" style="background:var(--primary-soft);color:var(--primary)"><x-maktabgid.icon name="users" :width="18" :height="18" /></span>
            </div>
            <div><b>{{ $leadStats['total'] }}</b><span>{{ __('cabinet_institution.total_leads') }}</span></div>
        </div>
        <div class="idash-stat">
            <div class="idash-stat-top">
                <span class="idash-stat-ico" style="background:#ece9fc;color:#5145d8"><x-maktabgid.icon name="sparkle" :width="18" :height="18" /></span>
            </div>
            <div><b>{{ $leadStats['pending'] }}</b><span>{{ __('cabinet_institution.new_awaiting_reply') }}</span></div>
        </div>
        <div class="idash-stat">
            <div class="idash-stat-top">
                <span class="idash-stat-ico" style="background:#fde7f3;color:#c2247a"><x-maktabgid.icon name="check" :width="18" :height="18" /></span>
            </div>
            <div><b>{{ $leadStats['confirmed'] }}</b><span>{{ __('cabinet_institution.tab_confirmed') }}</span></div>
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
        <label class="idash-lead-search">
            <x-maktabgid.icon name="search" :width="16" :height="16" />
            <input type="text" class="js-filter-search" placeholder="{{ __('cabinet_institution.search_by_name_note') }}" />
        </label>
    </div>

    <div class="panel">
        @if ($leads->isEmpty())
            <div class="empty">
                <span class="empty-ico"><x-maktabgid.icon name="users" :width="26" :height="26" /></span>
                <p>{{ __('cabinet_institution.no_leads_yet') }}</p>
            </div>
        @else
            <div class="idash-lead-table">
                <div class="idash-lead-head">
                    <span>{{ __('cabinet_institution.col_parent') }}</span>
                    <span>{{ __('cabinet_institution.col_child_interest') }}</span>
                    <span>{{ __('cabinet_institution.col_phone') }}</span>
                    <span>{{ __('cabinet_institution.col_status') }}</span>
                    <span>{{ __('cabinet_institution.col_action') }}</span>
                </div>

                @foreach ($leads as $lead)
                    @php
                        $childBits = array_filter([$lead->child_name, $lead->child_age ? __('cabinet_institution.age_years', ['age' => $lead->child_age]) : ($lead->target_grade ?: $lead->current_grade)]);
                    @endphp
                    <div class="idash-lead-row js-filter-row" data-app-id="{{ $lead->id }}" data-status="{{ $lead->status }}" data-search="{{ strtolower($lead->parent_name.' '.($lead->note ?? '')) }}">
                        <div class="idash-lead-parent">
                            <x-maktabgid.avatar :name="$lead->parent_name" :size="38" />
                            <div>
                                <b>{{ $lead->parent_name }}</b>
                                <span>{{ $lead->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="idash-lead-child">
                            <b>{{ $childBits ? implode(', ', $childBits) : '—' }}</b>
                            @if ($lead->note)
                                <span>{{ \Illuminate\Support\Str::limit($lead->note, 40) }}</span>
                            @endif
                        </div>
                        <div class="idash-lead-phone">
                            <x-maktabgid.icon name="phone" :width="14" :height="14" /> {{ $lead->parent_phone }}
                        </div>
                        <div><span class="idash-status-dot {{ $lead->status }}">{{ $statusLabels[$lead->status] ?? $lead->status }}</span></div>
                        <div class="idash-lead-actions">
                            @if ($lead->status === 'pending')
                                <button type="button" class="btn btn-primary sm" data-app-status="confirmed">{{ __('cabinet_institution.confirm') }}</button>
                                <button type="button" class="idash-lead-iconbtn danger" data-app-status="rejected" title="{{ __('cabinet_institution.reject') }}">
                                    <x-maktabgid.icon name="close" :width="16" :height="16" />
                                </button>
                            @else
                                <a class="idash-lead-iconbtn" href="tel:{{ $lead->parent_phone }}" title="{{ __('cabinet_institution.call') }}">
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

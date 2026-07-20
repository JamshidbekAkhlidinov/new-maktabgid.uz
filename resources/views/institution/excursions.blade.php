@php
    $tabs = [
        ['key' => 'all', 'label' => 'Hammasi', 'count' => $excursionStats['total']],
        ['key' => 'pending', 'label' => 'Kutilmoqda', 'count' => $excursionStats['pending']],
        ['key' => 'confirmed', 'label' => 'Tasdiqlangan', 'count' => $excursionStats['confirmed']],
        ['key' => 'completed', 'label' => "Bo'lib o'tdi", 'count' => $excursionStats['completed']],
    ];
@endphp

<x-institution.shell
    active="excursions"
    title="Ekskursiya arizalari"
    sub="Tashrif buyurmoqchi bo'lganlar"
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
            <div><b>{{ $excursionStats['total'] }}</b><span>Jami arizalar</span></div>
        </div>
        <div class="idash-stat">
            <div class="idash-stat-top">
                <span class="idash-stat-ico" style="background:var(--accent-soft);color:#b45309"><x-maktabgid.icon name="cal" :width="18" :height="18" /></span>
            </div>
            <div><b>{{ $excursionStats['pending'] }}</b><span>Tasdiq kutmoqda</span></div>
        </div>
        <div class="idash-stat">
            <div class="idash-stat-top">
                <span class="idash-stat-ico" style="background:#ece9fc;color:#5145d8"><x-maktabgid.icon name="check" :width="18" :height="18" /></span>
            </div>
            <div><b>{{ $excursionStats['confirmed'] }}</b><span>Tasdiqlangan</span></div>
        </div>
        <div class="idash-stat">
            <div class="idash-stat-top">
                <span class="idash-stat-ico" style="background:#fde7f3;color:#c2247a"><x-maktabgid.icon name="badge" :width="18" :height="18" /></span>
            </div>
            <div><b>{{ $excursionStats['completed'] }}</b><span>Bo'lib o'tgan</span></div>
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
                <p>Hali ariza tushmagan.</p>
            </div>
        @else
            <div class="idash-lead-table">
                <div class="idash-lead-head">
                    <span>Ota-ona</span>
                    <span>Farzand</span>
                    <span>Telefon</span>
                    <span>Sana / vaqt</span>
                    <span>Holat</span>
                    <span>Amal</span>
                </div>

                @foreach ($applications as $app)
                    @php
                        $childBits = array_filter([$app->child_name, $app->child_age ? "{$app->child_age} yosh" : ($app->target_grade ?: $app->current_grade)]);
                    @endphp
                    <div class="idash-lead-row js-filter-row" data-app-id="{{ $app->id }}" data-status="{{ $app->status }}">
                        <div class="idash-lead-parent">
                            <x-maktabgid.avatar :name="$app->parent_name" :size="38" />
                            <div>
                                <b>{{ $app->parent_name }}</b>
                                <span>{{ $app->note ? \Illuminate\Support\Str::limit($app->note, 34) : "Izoh yo'q" }}</span>
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
                                <button type="button" class="btn btn-primary sm" data-app-status="confirmed">Tasdiqlash</button>
                                <button type="button" class="idash-lead-iconbtn danger" data-app-status="rejected" title="Rad etish">
                                    <x-maktabgid.icon name="close" :width="16" :height="16" />
                                </button>
                            @elseif ($app->status === 'confirmed')
                                <button type="button" class="btn btn-ghost sm" data-app-status="completed">Yakunlash</button>
                            @else
                                <a class="idash-lead-iconbtn" href="tel:{{ $app->parent_phone }}" title="Qo'ng'iroq qilish">
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

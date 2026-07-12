<x-parent.shell active="applications" title="Arizalarim" sub="Ekskursiya va joylashtirish arizalari" :user="$user" :stats="$stats">

    <div class="panel">
        <div class="panel-head"><h3>Mening arizalarim</h3></div>
        @if ($applications->isEmpty())
            <div class="empty">
                <span class="empty-ico"><x-maktabgid.icon name="ticket" :width="26" :height="26" /></span>
                <p>Hali ariza yubormadingiz.</p>
            </div>
        @else
            <div class="cab-list">
                @foreach ($applications as $app)
                    <div class="cab-item static">
                        <span class="appl-ico"><x-maktabgid.icon name="ticket" :width="20" :height="20" /></span>
                        <div class="cab-item-main">
                            <b>{{ $app->institution?->name ?? '—' }}</b>
                            <span>{{ $app->child_name }} · {{ $app->target_grade ?? $app->current_grade ?? '—' }} · {{ $app->created_at->format('Y-m-d') }}</span>
                        </div>
                        <span class="appl-status {{ $statusClass[$app->status] ?? 'pending' }}">{{ $statusLabels[$app->status] ?? $app->status }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</x-parent.shell>

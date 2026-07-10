<x-institution.shell
    active="conversations"
    title="Suhbatlar"
    sub="Ota-onalar bilan yozishmalar"
    :institution="$institution"
    :organizations="$organizations"
    :counts="$counts"
    :user="$user"
>

    <div class="panel">
        <div class="panel-head">
            <h3>Barcha suhbatlar</h3>
            <span class="results-count"><span>{{ $conversations->count() }} ta suhbat</span></span>
        </div>

        @if ($conversations->isEmpty())
            <div class="empty">
                <span class="empty-ico"><x-maktabgid.icon name="chat" :width="26" :height="26" /></span>
                <p>Hali suhbat boshlanmagan. Ota-onalar profilingizdan yozishni boshlaganda shu yerda chiqadi.</p>
            </div>
        @else
            <div class="cab-list">
                @foreach ($conversations as $c)
                    @php $lastMsg = $c->messages->first(); @endphp
                    <a href="{{ route('chat.index') }}" class="cab-item">
                        <x-maktabgid.avatar :name="$c->parent?->name ?? 'Foydalanuvchi'" :size="42" />
                        <div class="cab-item-main">
                            <b>{{ $c->parent?->name ?? "O'chirilgan foydalanuvchi" }}</b>
                            <span>{{ $lastMsg?->body ? \Illuminate\Support\Str::limit($lastMsg->body, 60) : "Hali xabar yo'q" }}</span>
                        </div>
                        <span class="idash-chart-meta">{{ ($c->last_message_at ?? $c->created_at)->diffForHumans() }}</span>
                        <x-maktabgid.icon name="chevronR" :width="18" :height="18" />
                    </a>
                @endforeach
            </div>
        @endif
    </div>

</x-institution.shell>

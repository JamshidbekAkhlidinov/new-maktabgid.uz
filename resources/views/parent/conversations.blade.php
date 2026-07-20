<x-parent.shell active="conversations" title="Suhbatlar" sub="Muassasalar bilan yozishma" :user="$user" :stats="$stats">

    <div class="chat-shell">
        {{-- ===== Suhbatlar ro'yxati ===== --}}
        <aside class="chat-list">
            <div class="chat-list-head">
                <h3>Suhbatlar</h3>
                <label class="chat-search">
                    <x-maktabgid.icon name="search" :width="15" :height="15" />
                    <input type="text" class="js-chat-search" placeholder="Muassasa nomi…" />
                </label>
            </div>

            @forelse ($conversations as $c)
                @php $lastMsg = $c->messages->first(); @endphp
                <a href="{{ route('cabinet.conversations') }}?c={{ $c->id }}"
                   class="chat-li js-chat-li{{ $active && $active->id === $c->id ? ' on' : '' }}"
                   data-search="{{ strtolower($c->institution?->name ?? '') }}">
                    <x-maktabgid.avatar :name="$c->institution?->name ?? 'Muassasa'" :size="42" />
                    <div class="chat-li-main">
                        <b>{{ $c->institution?->name ?? "O'chirilgan muassasa" }}</b>
                        <span>{{ $lastMsg?->body ? \Illuminate\Support\Str::limit($lastMsg->body, 32) : "Hali xabar yo'q" }}</span>
                    </div>
                    <div class="chat-li-meta">
                        <time>{{ ($c->last_message_at ?? $c->created_at)->diffForHumans() }}</time>
                        @if ($c->unread_count > 0)
                            <span class="unread-dot">{{ $c->unread_count }}</span>
                        @endif
                    </div>
                </a>
            @empty
                <div class="empty" style="padding:34px 18px">
                    <span class="empty-ico"><x-maktabgid.icon name="chat" :width="24" :height="24" /></span>
                    <p>Hali suhbat yo'q. Muassasa profilidan "Suhbat boshlash" tugmasini bosing.</p>
                </div>
            @endforelse
        </aside>

        {{-- ===== Tanlangan suhbat ===== --}}
        <div class="chat-thread">
            @if ($active)
                <div class="chat-thead">
                    <x-maktabgid.avatar :name="$active->institution?->name ?? 'Muassasa'" :size="42" />
                    <div>
                        <b>{{ $active->institution?->name ?? 'Muassasa' }}</b>
                        <span>{{ $active->institution?->district?->name }}</span>
                    </div>
                    @if ($active->institution)
                        <a class="iconbtn" href="{{ route('maktabgid.school', $active->institution->id) }}" title="Profilni ko'rish"><x-maktabgid.icon name="arrowR" :width="17" :height="17" /></a>
                    @endif
                </div>

                <div class="chat-msgs">
                    @if ($activeMessages->isEmpty())
                        <div class="chat-empty">
                            <p style="color:var(--ink-3);font-weight:600;font-size:14px">Hali xabar yo'q. Birinchi bo'lib yozing!</p>
                        </div>
                    @else
                        @foreach ($activeMessages as $row)
                            @if ($row['showDivider'])
                                <div class="chat-date-divider"><span>{{ $row['dayLabel'] }}</span></div>
                            @endif
                            <div class="bubble-row {{ $row['model']->sender_type === 'parent' ? 'me' : 'them' }}">
                                <div class="msg-bubble">
                                    {{ $row['model']->body }}
                                    <time>{{ $row['model']->created_at->format('H:i') }}</time>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <form class="chat-input js-chat-send-form" data-conversation-id="{{ $active->id }}"
                      data-send-url="/ajax/conversations/{{ $active->id }}/messages">
                    <span class="chat-attach"><x-maktabgid.icon name="paperclip" :width="19" :height="19" /></span>
                    <input type="text" id="js-chat-input" placeholder="Xabar yozing…" autocomplete="off" />
                    <button type="submit" class="chat-send"><x-maktabgid.icon name="send" :width="18" :height="18" /></button>
                </form>
            @else
                <div class="chat-empty">
                    <div class="empty">
                        <span class="empty-ico"><x-maktabgid.icon name="chat" :width="24" :height="24" /></span>
                        <p>Hali suhbat yo'q.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

</x-parent.shell>

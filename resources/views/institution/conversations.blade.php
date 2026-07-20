<x-institution.shell
    active="conversations"
    title="Suhbatlar"
    sub="Ota-onalar bilan yozishmalar"
    :institution="$institution"
    :organizations="$organizations"
    :counts="$counts"
    :user="$user"
>
    @if ($institution)

    <div class="chat-shell">
        {{-- ===== Suhbatlar ro'yxati ===== --}}
        <aside class="chat-list">
            <div class="chat-list-head">
                <h3>Suhbatlar</h3>
                <label class="chat-search">
                    <x-maktabgid.icon name="search" :width="15" :height="15" />
                    <input type="text" class="js-chat-search" placeholder="Ota-ona ismi…" />
                </label>
            </div>

            @forelse ($conversations as $c)
                @php $lastMsg = $c->messages->first(); @endphp
                <a href="{{ route('institution.cabinet.conversations') }}?c={{ $c->id }}"
                   class="chat-li js-chat-li{{ $active && $active->id === $c->id ? ' on' : '' }}"
                   data-search="{{ strtolower($c->parent?->name ?? '') }}">
                    <x-maktabgid.avatar :name="$c->parent?->name ?? 'Foydalanuvchi'" :size="42" />
                    <div class="chat-li-main">
                        <b>{{ $c->parent?->name ?? "O'chirilgan foydalanuvchi" }}</b>
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
                    <p>Hali suhbat yo'q. Ota-onalar profilingizdan yozganda shu yerda chiqadi.</p>
                </div>
            @endforelse
        </aside>

        {{-- ===== Tanlangan suhbat ===== --}}
        <div class="chat-thread">
            @if ($active)
                <div class="chat-thead">
                    <x-maktabgid.avatar :name="$active->parent?->name ?? 'Foydalanuvchi'" :size="42" />
                    <div>
                        <b>{{ $active->parent?->name ?? "Foydalanuvchi" }}</b>
                        <span>
                            @if ($activeChild)
                                {{ $activeChild->child_name }}{{ $activeChild->child_age ? ', '.$activeChild->child_age.' yosh' : '' }}
                            @else
                                {{ $active->parent?->phone ?? '' }}
                            @endif
                        </span>
                    </div>
                    @if ($active->parent?->phone)
                        <a class="iconbtn" href="tel:{{ $active->parent->phone }}" title="Qo'ng'iroq qilish"><x-maktabgid.icon name="phone" :width="17" :height="17" /></a>
                    @endif
                    <a class="iconbtn" href="{{ route('institution.cabinet.excursions') }}" title="Arizalar"><x-maktabgid.icon name="ticket" :width="17" :height="17" /></a>
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
                            <div class="bubble-row {{ $row['model']->sender_type === 'institution' ? 'me' : 'them' }}">
                                <div class="msg-bubble">
                                    {{ $row['model']->body }}
                                    <time>{{ $row['model']->created_at->format('H:i') }}</time>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="chat-suggest">
                    <button type="button" class="js-chat-suggest" data-text="Assalomu alaykum! Qanday yordam bera olamiz?">Assalomu alaykum! Qanday yordam bera olamiz?</button>
                    <button type="button" class="js-chat-suggest" data-text="Ekskursiyaga taklif qilamiz 🙂">Ekskursiyaga taklif qilamiz 🙂</button>
                    <button type="button" class="js-chat-suggest" data-text="Oylik to'lov haqida ma'lumot yuboraman">Oylik to'lov haqida ma'lumot yuboraman</button>
                </div>

                <form class="chat-input js-chat-send-form" data-conversation-id="{{ $active->id }}"
                      data-send-url="/ajax/institution/me/conversations/{{ $active->id }}/messages">
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

    @endif
</x-institution.shell>

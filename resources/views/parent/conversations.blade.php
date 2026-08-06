<x-parent.shell active="conversations" title="{{ __('cabinet_parent.nav_conversations') }}" sub="{{ __('cabinet_parent.conversations_sub') }}" :user="$user" :stats="$stats">

    <div class="chat-shell">
        {{-- ===== Suhbatlar ro'yxati ===== --}}
        <aside class="chat-list">
            <div class="chat-list-head">
                <h3>{{ __('cabinet_parent.nav_conversations') }}</h3>
                <label class="chat-search">
                    <x-maktabgid.icon name="search" :width="15" :height="15" />
                    <input type="text" class="js-chat-search" placeholder="{{ __('cabinet_parent.chat_search_placeholder') }}" />
                </label>
            </div>

            @forelse ($conversations as $c)
                @php $lastMsg = $c->messages->first(); @endphp
                <a href="{{ route('cabinet.conversations') }}?c={{ $c->id }}"
                   class="chat-li js-chat-li{{ $active && $active->id === $c->id ? ' on' : '' }}"
                   data-search="{{ strtolower($c->institution?->name ?? '') }}">
                    <x-maktabgid.avatar :name="$c->institution?->name ?? 'Muassasa'" :size="42" />
                    <div class="chat-li-main">
                        <b>{{ $c->institution?->name ?? __('cabinet_parent.deleted_institution') }}</b>
                        <span>{{ $lastMsg?->body ? \Illuminate\Support\Str::limit($lastMsg->body, 32) : __('cabinet_parent.no_messages_yet') }}</span>
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
                    <p>{{ __('cabinet_parent.conversations_empty') }}</p>
                </div>
            @endforelse
        </aside>

        {{-- ===== Tanlangan suhbat ===== --}}
        <div class="chat-thread">
            @if ($active)
                <div class="chat-thead">
                    <x-maktabgid.avatar :name="$active->institution?->name ?? 'Muassasa'" :size="42" />
                    <div>
                        <b>{{ $active->institution?->name ?? __('cabinet_parent.institution_fallback') }}</b>
                        <span>{{ $active->institution?->district?->name }}</span>
                    </div>
                    @if ($active->institution)
                        <a class="iconbtn" href="{{ route('maktabgid.school', $active->institution) }}" title="{{ __('cabinet_parent.view_profile') }}"><x-maktabgid.icon name="arrowR" :width="17" :height="17" /></a>
                    @endif
                </div>

                <div class="chat-msgs" id="js-chat-msgs" data-conversation-id="{{ $active->id }}" data-last-id="{{ $activeMessages->last()['model']->id ?? 0 }}" data-my-sender-type="parent">
                    @if ($activeMessages->isEmpty())
                        <div class="chat-empty">
                            <p style="color:var(--ink-3);font-weight:600;font-size:14px">{{ __('cabinet_parent.chat_be_first') }}</p>
                        </div>
                    @else
                        @foreach ($activeMessages as $row)
                            @if ($row['showDivider'])
                                <div class="chat-date-divider"><span>{{ $row['dayLabel'] }}</span></div>
                            @endif
                            <div class="bubble-row {{ $row['model']->sender_type === 'parent' ? 'me' : 'them' }}" data-message-id="{{ $row['model']->id }}">
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
                    <input type="text" id="js-chat-input" placeholder="{{ __('cabinet_parent.chat_input_placeholder') }}" autocomplete="off" />
                    <button type="submit" class="chat-send"><x-maktabgid.icon name="send" :width="18" :height="18" /></button>
                </form>
            @else
                <div class="chat-empty">
                    <div class="empty">
                        <span class="empty-ico"><x-maktabgid.icon name="chat" :width="24" :height="24" /></span>
                        <p>{{ __('cabinet_parent.no_conversations_short') }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

</x-parent.shell>

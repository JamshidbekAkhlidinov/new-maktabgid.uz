<x-institution.shell
    active="conversations"
    title="{{ __('cabinet_institution.nav_conversations') }}"
    sub="{{ __('cabinet_institution.institution_conversations_sub') }}"
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
                <h3>{{ __('cabinet_institution.nav_conversations') }}</h3>
                <label class="chat-search">
                    <x-maktabgid.icon name="search" :width="15" :height="15" />
                    <input type="text" class="js-chat-search" placeholder="{{ __('cabinet_institution.name_placeholder') }}" />
                </label>
            </div>

            @forelse ($conversations as $c)
                @php $lastMsg = $c->messages->first(); @endphp
                <a href="{{ route('institution.cabinet.conversations') }}?c={{ $c->id }}"
                   class="chat-li js-chat-li{{ $active && $active->id === $c->id ? ' on' : '' }}"
                   data-search="{{ strtolower($c->participant?->name ?? '') }}">
                    <x-maktabgid.avatar :name="$c->participant?->name ?? 'Foydalanuvchi'" :size="42" />
                    <div class="chat-li-main">
                        <b>{{ $c->participant?->name ?? __('cabinet_institution.deleted_user') }} <span class="idash-lead-source" style="padding:2px 9px;font-size:10.5px">{{ $c->participant_role === 'teacher' ? __('cabinet_institution.role_teacher') : __('cabinet_institution.role_parent') }}</span></b>
                        <span>{{ $lastMsg?->body ? \Illuminate\Support\Str::limit($lastMsg->body, 32) : __('cabinet_institution.no_messages_yet') }}</span>
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
                    <p>{{ __('cabinet_institution.conversations_empty_institution') }}</p>
                </div>
            @endforelse
        </aside>

        {{-- ===== Tanlangan suhbat ===== --}}
        <div class="chat-thread">
            @if ($active)
                <div class="chat-thead">
                    <x-maktabgid.avatar :name="$active->participant?->name ?? 'Foydalanuvchi'" :size="42" />
                    <div>
                        <b>{{ $active->participant?->name ?? __('cabinet_institution.user_fallback') }}</b>
                        <span>
                            @if ($activeChild)
                                {{ $activeChild->child_name }}{{ $activeChild->child_age ? ', '.__('cabinet_institution.age_years', ['age' => $activeChild->child_age]) : '' }}
                            @else
                                {{ $active->participant?->phone ?? ($active->participant_role === 'teacher' ? __('cabinet_institution.role_teacher') : '') }}
                            @endif
                        </span>
                    </div>
                    @if ($active->participant?->phone)
                        <a class="iconbtn" href="tel:{{ $active->participant->phone }}" title="{{ __('cabinet_institution.call') }}"><x-maktabgid.icon name="phone" :width="17" :height="17" /></a>
                    @endif
                    @if ($active->parent_user_id)
                        <a class="iconbtn" href="{{ route('institution.cabinet.excursions') }}" title="{{ __('cabinet_institution.nav_excursions') }}"><x-maktabgid.icon name="ticket" :width="17" :height="17" /></a>
                    @endif
                </div>

                <div class="chat-msgs" id="js-chat-msgs" data-conversation-id="{{ $active->id }}" data-last-id="{{ $activeMessages->last()['model']->id ?? 0 }}" data-my-sender-type="institution">
                    @if ($activeMessages->isEmpty())
                        <div class="chat-empty">
                            <p style="color:var(--ink-3);font-weight:600;font-size:14px">{{ __('cabinet_institution.chat_be_first') }}</p>
                        </div>
                    @else
                        @foreach ($activeMessages as $row)
                            @if ($row['showDivider'])
                                <div class="chat-date-divider"><span>{{ $row['dayLabel'] }}</span></div>
                            @endif
                            <div class="bubble-row {{ $row['model']->sender_type === 'institution' ? 'me' : 'them' }}" data-message-id="{{ $row['model']->id }}">
                                <div class="msg-bubble">
                                    {{ $row['model']->body }}
                                    <time>{{ $row['model']->created_at->format('H:i') }}</time>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="chat-suggest">
                    <button type="button" class="js-chat-suggest" data-text="{{ __('cabinet_institution.suggest_greeting') }}">{{ __('cabinet_institution.suggest_greeting') }}</button>
                    <button type="button" class="js-chat-suggest" data-text="{{ __('cabinet_institution.suggest_excursion') }}">{{ __('cabinet_institution.suggest_excursion') }}</button>
                    <button type="button" class="js-chat-suggest" data-text="{{ __('cabinet_institution.suggest_payment_info') }}">{{ __('cabinet_institution.suggest_payment_info') }}</button>
                </div>

                <form class="chat-input js-chat-send-form" data-conversation-id="{{ $active->id }}"
                      data-send-url="/ajax/institution/me/conversations/{{ $active->id }}/messages">
                    <span class="chat-attach"><x-maktabgid.icon name="paperclip" :width="19" :height="19" /></span>
                    <input type="text" id="js-chat-input" placeholder="{{ __('cabinet_institution.chat_input_placeholder') }}" autocomplete="off" />
                    <button type="submit" class="chat-send"><x-maktabgid.icon name="send" :width="18" :height="18" /></button>
                </form>
            @else
                <div class="chat-empty">
                    <div class="empty">
                        <span class="empty-ico"><x-maktabgid.icon name="chat" :width="24" :height="24" /></span>
                        <p>{{ __('cabinet_institution.no_conversations_short') }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @endif
</x-institution.shell>

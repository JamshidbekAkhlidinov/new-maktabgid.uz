@php
    // Mock: ustoz-muassasa yozishmalari real Conversation modeliga hali ulanmagan (Conversation
    // hozircha faqat parent<->institution uchun ishlaydi — App\Models\Conversation'ga qarang).
    // Shu sahifa faqat vizual namoyish — yozish maydoni funksional emas.
    $threads = [
        ['name' => 'Bilim Ziyo maktabi', 'last' => 'Suhbat uchun rahmat, ertaga javob beramiz', 'time' => '14:20', 'unread' => 2, 'active' => true],
        ['name' => 'Cambridge School', 'last' => 'Intervyu vaqtini kelishib olaylik', 'time' => 'Kecha', 'unread' => 0, 'active' => false],
    ];
    $messages = [
        ['from' => 'them', 'text' => 'Assalomu alaykum! Rezyumengizni ko\'rdik, yoqdi.', 'time' => '14:02'],
        ['from' => 'me', 'text' => 'Vaalaykum assalom! Rahmat, tafsilotlarni eshitsam bo\'ladimi?', 'time' => '14:05'],
        ['from' => 'them', 'text' => 'Albatta — ish haqi va jadval bo\'yicha taklifni yubordik. Ko\'rib chiqing.', 'time' => '14:18'],
        ['from' => 'them', 'text' => 'Suhbat uchun rahmat, ertaga javob beramiz', 'time' => '14:20'],
    ];
@endphp

<x-teacher.shell active="conversations" title="Suhbatlar" sub="Muassasalar bilan yozishma" :teacher="$teacher" :counts="$counts">

    <div class="chat-shell">
        <aside class="chat-list">
            <div class="chat-list-head">
                <h3>Suhbatlar</h3>
                <label class="chat-search">
                    <x-maktabgid.icon name="search" :width="15" :height="15" />
                    <input type="text" placeholder="Muassasa nomi…" />
                </label>
            </div>

            @foreach ($threads as $t)
                <div class="chat-li{{ $t['active'] ? ' on' : '' }}">
                    <x-maktabgid.avatar :name="$t['name']" :size="42" />
                    <div class="chat-li-main">
                        <b>{{ $t['name'] }}</b>
                        <span>{{ $t['last'] }}</span>
                    </div>
                    <div class="chat-li-meta">
                        <time>{{ $t['time'] }}</time>
                        @if ($t['unread'] > 0)
                            <span class="unread-dot">{{ $t['unread'] }}</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </aside>

        <div class="chat-thread">
            <div class="chat-thead">
                <x-maktabgid.avatar :name="$threads[0]['name']" :size="42" />
                <div>
                    <b>{{ $threads[0]['name'] }}</b>
                    <span>Muassasa</span>
                </div>
            </div>

            <div class="chat-msgs">
                @foreach ($messages as $m)
                    <div class="bubble-row {{ $m['from'] === 'me' ? 'me' : 'them' }}">
                        <div class="msg-bubble">
                            {{ $m['text'] }}
                            <time>{{ $m['time'] }}</time>
                        </div>
                    </div>
                @endforeach
            </div>

            <form class="chat-input" onsubmit="return false">
                <span class="chat-attach"><x-maktabgid.icon name="paperclip" :width="19" :height="19" /></span>
                <input type="text" placeholder="Xabar yozing…" autocomplete="off" />
                <button type="submit" class="chat-send"><x-maktabgid.icon name="send" :width="18" :height="18" /></button>
            </form>
        </div>
    </div>

    <div class="idash-badge-soft">
        <x-maktabgid.icon name="sparkle" :width="14" :height="14" /> Bu bo'lim demo ko'rinishda — real yozishma tez orada ulanadi
    </div>

</x-teacher.shell>

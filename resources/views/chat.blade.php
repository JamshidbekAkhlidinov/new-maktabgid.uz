<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Suhbatlar — {{ config('app.name', 'MaktabGID') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,600;12..96,700;12..96,800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/maktabgid.css') }}" />
</head>
<body>

    @php
        $chats = [
            [
                'id'        => 1,
                'schoolId'  => 3,
                'name'      => 'Diplomat International School',
                'unread'    => 2,
                'grad'      => ['#0EA5A0', '#0B7E8C'],
                'online'    => true,
                'messages'  => [
                    ['from' => 'school', 'text' => "Assalomu alaykum! MaktabGID orqali murojaatingiz uchun rahmat. Sizga qanday yordam bera olamiz?", 'time' => '09:12'],
                    ['from' => 'me',     'text' => "Salom! 1-sinf uchun joy bormi va oylik toʻlov qancha?",                                     'time' => '09:15'],
                    ['from' => 'school', 'text' => "Ha, 2026–2027 oʻquv yiliga qabul davom etmoqda. Oylik toʻlov 6 900 000 soʻm, transport va ovqat alohida.", 'time' => '09:17'],
                    ['from' => 'school', 'text' => "Istasangiz ekskursiyaga yozilib, maktabni oʻz koʻzingiz bilan koʻishingiz mumkin.",             'time' => '09:17'],
                ],
            ],
            [
                'id'        => 2,
                'schoolId'  => 9,
                'name'      => 'Maple Bear Canadian School',
                'unread'    => 0,
                'grad'      => ['#10B981', '#047857'],
                'online'    => false,
                'messages'  => [
                    ['from' => 'me',     'text' => "Salom, 4 yoshli bola uchun guruh bormi?",                                           'time' => 'Kecha'],
                    ['from' => 'school', 'text' => "Assalomu alaykum! Ha, 3–4 yosh guruhida 2 ta joy bor. Bemalol tashrif buyuring.", 'time' => 'Kecha'],
                ],
            ],
            [
                'id'        => 3,
                'schoolId'  => 6,
                'name'      => 'Cambridge School',
                'unread'    => 0,
                'grad'      => ['#3B82F6', '#1D4ED8'],
                'online'    => true,
                'messages'  => [
                    ['from' => 'school', 'text' => "Hujjatlar roʻxatini yubordim, koʻing chiqing.", 'time' => '2 kun oldin'],
                ],
            ],
        ];
    @endphp

    <x-maktabgid.nav />

    <div class="chatpage">

        <x-maktabgid.page-head
            icon="chat"
            kicker="Suhbatlar"
            title="Maktablar bilan toʻgʻridan-toʻgʻri yozishmalar"
            sub="Muassasalarga savolingizni bevosita yuboring — vositachisiz, tezkor."
        />

        {{-- ===== GUEST STATE ===== --}}
        <div id="js-chat-guest" style="display:none">
            <div class="wrap" style="padding:60px 0;text-align:center">
                <div class="empty">
                    <span class="empty-ico"><x-maktabgid.icon name="chat" :width="28" :height="28" /></span>
                    <p style="font-size:16px;font-weight:700;color:var(--ink)">Kabinetga kirish kerak</p>
                    <p>Maktablar bilan yozishish uchun ota-ona sifatida tizimga kiring.</p>
                    <button class="btn btn-primary" data-modal-open="auth-modal">
                        <x-maktabgid.icon name="user" :width="17" :height="17" /> Kirish
                    </button>
                </div>
            </div>
        </div>

        {{-- ===== CHAT BODY ===== --}}
        <div id="js-chat-body" style="display:none">
            <div class="wrap">
                <div class="chat-shell">

                    {{-- Left: conversation list --}}
                    <aside class="chat-list" id="js-chat-list">
                        @foreach ($chats as $c)
                            @php
                                $mono  = mb_strtoupper(mb_substr($c['name'], 0, 1));
                                $words = explode(' ', $c['name']);
                                if (count($words) >= 2) {
                                    $mono = mb_strtoupper(mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1));
                                }
                                $grad     = 'linear-gradient(140deg,' . $c['grad'][0] . ',' . $c['grad'][1] . ')';
                                $lastText = $c['messages'][count($c['messages']) - 1]['text'] ?? '';
                                $preview  = mb_substr($lastText, 0, 38) . '…';
                            @endphp
                            <button class="chat-li{{ $loop->first ? ' on' : '' }}" type="button"
                                    data-chat-id="{{ $c['id'] }}">
                                <span class="avatar" style="width:46px;height:46px;border-radius:10px;font-size:18px;flex:none;background:{{ $grad }}">
                                    {{ $mono }}
                                </span>
                                <div class="chat-li-main">
                                    <b>
                                        {{ $c['name'] }}
                                        @if ($c['online'])
                                            <span class="ondot"></span>
                                        @endif
                                    </b>
                                    <span>{{ $preview }}</span>
                                </div>
                                @if ($c['unread'] > 0)
                                    <span class="unread-dot" data-unread="{{ $c['id'] }}">{{ $c['unread'] }}</span>
                                @endif
                            </button>
                        @endforeach
                    </aside>

                    {{-- Right: active thread (JS-managed) --}}
                    <section class="chat-thread" id="js-chat-thread">
                        {{-- populated by JS --}}
                    </section>

                </div>
            </div>
        </div>

    </div>{{-- .chatpage --}}

    <x-maktabgid.trust-strip />
    <x-maktabgid.cta-band />
    <x-maktabgid.footer />

    {{-- Seed chat data for JS --}}
    <script>
    var MG_CHATS = @json($chats);
    </script>
    <script src="{{ asset('js/maktabgid.js') }}"></script>

    <script>
    (function () {
        var AUTO_REPLIES = [
            "Rahmat! Soʻrovingizni qabul qildik, administrator tez orada javob beradi.",
            "Albatta, bemalol tashrif buyurishingiz mumkin. Ekskursiyaga yozilib qoʻysangiz, vaqtini kelishamiz.",
            "Hujjatlar: tugʻilganlik guvohnomasi, 2 ta surat va tibbiy maʻlumotnnoma. Yana savol boʻlsa, yozing.",
        ];

        /* ---- auth check ---- */
        var user = null;
        try { user = JSON.parse(localStorage.getItem("mg_user") || "null"); } catch (e) {}

        var guest = document.getElementById("js-chat-guest");
        var body  = document.getElementById("js-chat-body");

        if (!user || user.kind !== "parent") {
            guest.style.display = "block";
            return;
        }
        body.style.display = "block";

        /* ---- state ---- */
        var chats    = MG_CHATS.map(function (c) { return Object.assign({}, c, { messages: c.messages.slice() }); });
        var activeId = chats[0] ? chats[0].id : null;

        // mark first chat read on open
        chats = chats.map(function (c, i) { return i === 0 ? Object.assign({}, c, { unread: 0 }) : c; });

        /* ---- helpers ---- */
        function monogram(name) {
            var w = name.trim().split(/\s+/);
            return w.length >= 2
                ? (w[0][0] + w[1][0]).toUpperCase()
                : name.slice(0, 2).toUpperCase();
        }

        function sendIcon() {
            return '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2L11 13"/><path d="M22 2L15 22l-4-9-9-4z"/></svg>';
        }
        function arrowRIcon() {
            return '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>';
        }
        function paperclipIcon() {
            return '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66L9.64 17.2a2 2 0 0 1-2.83-2.83l8.49-8.49"/></svg>';
        }

        /* ---- render list ---- */
        function renderList() {
            var list = document.getElementById("js-chat-list");
            if (!list) return;
            list.innerHTML = chats.map(function (c) {
                var lastMsg = c.messages[c.messages.length - 1];
                var preview = lastMsg ? lastMsg.text.slice(0, 38) + "…" : "";
                var grad    = "linear-gradient(140deg," + c.grad[0] + "," + c.grad[1] + ")";
                return '<button class="chat-li' + (c.id === activeId ? " on" : "") + '" type="button" data-chat-id="' + c.id + '">' +
                    '<span class="avatar" style="width:46px;height:46px;border-radius:10px;font-size:18px;flex:none;background:' + grad + '">' + monogram(c.name) + '</span>' +
                    '<div class="chat-li-main">' +
                        '<b>' + c.name + (c.online ? '<span class="ondot"></span>' : '') + '</b>' +
                        '<span>' + preview + '</span>' +
                    '</div>' +
                    (c.unread > 0 ? '<span class="unread-dot">' + c.unread + '</span>' : '') +
                '</button>';
            }).join("");
        }

        /* ---- render thread ---- */
        function renderThread() {
            var thread = document.getElementById("js-chat-thread");
            if (!thread) return;
            var c = null;
            for (var i = 0; i < chats.length; i++) { if (chats[i].id === activeId) { c = chats[i]; break; } }
            if (!c) {
                thread.innerHTML = '<div class="empty"><p>Suhbat tanlang</p></div>';
                return;
            }
            var grad = "linear-gradient(140deg," + c.grad[0] + "," + c.grad[1] + ")";
            var msgs = c.messages.map(function (m) {
                return '<div class="bubble-row ' + (m.from === "me" ? "me" : "them") + '">' +
                    '<div class="msg-bubble">' + m.text + '<time>' + m.time + '</time></div>' +
                '</div>';
            }).join("");

            thread.innerHTML =
                '<header class="chat-thead">' +
                    '<span class="avatar" style="width:42px;height:42px;border-radius:10px;font-size:17px;flex:none;background:' + grad + '">' + monogram(c.name) + '</span>' +
                    '<div><b>' + c.name + '</b><span>' + (c.online ? "Onlayn" : "Oxirgi: yaqinda") + '</span></div>' +
                    '<a href="/maktab/' + c.schoolId + '" class="btn btn-ghost sm">Profil ' + arrowRIcon() + '</a>' +
                '</header>' +
                '<div class="chat-msgs" id="js-chat-msgs">' + msgs + '</div>' +
                '<form class="chat-input" id="js-chat-form">' +
                    '<button type="button" class="chat-attach">' + paperclipIcon() + '</button>' +
                    '<input id="js-chat-input" placeholder="Xabar yozing…" autocomplete="off" />' +
                    '<button class="chat-send" type="submit">' + sendIcon() + '</button>' +
                '</form>';

            // scroll to bottom
            var msgsEl = document.getElementById("js-chat-msgs");
            if (msgsEl) msgsEl.scrollTop = msgsEl.scrollHeight;

            // form submit
            document.getElementById("js-chat-form").addEventListener("submit", function (e) {
                e.preventDefault();
                var inp = document.getElementById("js-chat-input");
                var txt = inp ? inp.value.trim() : "";
                if (!txt) return;
                inp.value = "";
                var now = activeId; // capture for closure
                chats = chats.map(function (chat) {
                    return chat.id !== now ? chat
                        : Object.assign({}, chat, { messages: chat.messages.concat([{ from: "me", text: txt, time: "Hozir" }]) });
                });
                renderThread();
                renderList();
                setTimeout(function () {
                    var reply = AUTO_REPLIES[Math.floor(Math.random() * AUTO_REPLIES.length)];
                    chats = chats.map(function (chat) {
                        return chat.id !== now ? chat
                            : Object.assign({}, chat, { messages: chat.messages.concat([{ from: "school", text: reply, time: "Hozir" }]) });
                    });
                    renderThread();
                    renderList();
                }, 1100);
            });
        }

        /* ---- list click delegation ---- */
        document.getElementById("js-chat-list").addEventListener("click", function (e) {
            var btn = e.target.closest("[data-chat-id]");
            if (!btn) return;
            var id = parseInt(btn.getAttribute("data-chat-id"), 10);
            if (id === activeId) return;
            activeId = id;
            chats = chats.map(function (c) {
                return c.id === id ? Object.assign({}, c, { unread: 0 }) : c;
            });
            renderList();
            renderThread();
        });

        renderThread();
    }());
    </script>

</body>
</html>

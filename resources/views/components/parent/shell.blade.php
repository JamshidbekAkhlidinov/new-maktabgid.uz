@props([
    'active' => 'dashboard',
    'title' => 'Profilim',
    'sub' => "Shaxsiy ma'lumotlaringiz",
    'user' => null,
    'stats' => ['favorites' => 0, 'applications' => 0, 'conversations' => 0],
])

{{--
    Ota-ona kabineti qobig'i — x-institution.shell / x-teacher.shell bilan bir xil
    vizual tildan (.idash-* klasslar, institution-dashboard.css) foydalanadi, shu
    bilan barcha kabinet turlari bir xil ko'rinishga ega bo'ladi.
--}}

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $title }} — Ota-ona kabineti — {{ config('app.name', 'MaktabGID') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,600;12..96,700;12..96,800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/maktabgid.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/institution-dashboard.css') }}" />
</head>
<body>

@unless ($user)
    {{-- ===== NOT LOGGED IN / WRONG ROLE ===== --}}
    <div class="wrap" style="padding:80px 0;text-align:center">
        <div class="empty">
            <span class="empty-ico"><x-maktabgid.icon name="user" :width="28" :height="28" /></span>
            <p style="font-size:16px;font-weight:700;color:var(--ink)">Kabinetga kirish kerak</p>
            <p>Profilingizni ko'rish uchun tizimga kiring yoki ro'yxatdan o'ting.</p>
            <button class="btn btn-primary" data-modal-open="auth-modal">
                <x-maktabgid.icon name="user" :width="17" :height="17" /> Kirish
            </button>
        </div>
    </div>

    <x-maktabgid.auth-modal />
@else
    @php
        $navItems = [
            ['key' => 'dashboard', 'route' => 'cabinet.index', 'icon' => 'user', 'label' => 'Profilim'],
            ['key' => 'children', 'route' => 'cabinet.children', 'icon' => 'teddy', 'label' => 'Farzandlarim'],
            ['key' => 'favorites', 'route' => 'cabinet.favorites', 'icon' => 'heart', 'label' => 'Saqlanganlar', 'count' => $stats['favorites']],
            ['key' => 'applications', 'route' => 'cabinet.applications', 'icon' => 'ticket', 'label' => 'Arizalarim', 'count' => $stats['applications']],
            ['key' => 'conversations', 'route' => 'cabinet.conversations', 'icon' => 'chat', 'label' => 'Suhbatlar', 'count' => $stats['conversations']],
        ];
    @endphp

    <div class="idash-shell">
        {{-- ===== SIDEBAR ===== --}}
        <aside class="idash-sidebar">
            <a class="idash-logo" href="{{ route('welcome') }}">
                <span class="logo-mark"><x-maktabgid.icon name="school" :width="18" :height="18" /></span>
                Maktab<b>GID</b>
            </a>

            <div class="idash-org-wrap">
                <div class="idash-org-btn" style="cursor:default">
                    <span class="idash-org-mono">{{ \App\Support\MaktabgidData::monogram($user->name) }}</span>
                    <span class="idash-org-info">
                        <b>{{ $user->name }}</b>
                        <span>{{ $user->phone }}</span>
                    </span>
                </div>
            </div>

            <nav class="idash-navgroup">
                <span class="idash-navlabel">Kabinet</span>
                @foreach ($navItems as $item)
                    <a href="{{ route($item['route']) }}" class="idash-navlink{{ $active === $item['key'] ? ' on' : '' }}">
                        <x-maktabgid.icon :name="$item['icon']" :width="18" :height="18" />
                        {{ $item['label'] }}
                        @if (! empty($item['count']))
                            <em>{{ $item['count'] }}</em>
                        @endif
                    </a>
                @endforeach
            </nav>

            <nav class="idash-navgroup">
                <span class="idash-navlabel">Hisob</span>
                <a href="{{ route('cabinet.subscription') }}" class="idash-navlink{{ $active === 'subscription' ? ' on' : '' }}">
                    <x-maktabgid.icon name="shield" :width="18" :height="18" />
                    Obuna
                </a>
                <button type="button" class="idash-navlink danger" id="js-logout-btn">
                    <x-maktabgid.icon name="logout" :width="18" :height="18" />
                    Chiqish
                </button>
            </nav>

            <button type="button" class="cab-ai" id="js-cab-ai-btn" style="margin-top:auto">
                <x-maktabgid.icon name="robot" :width="20" :height="20" />
                <div>
                    <b>AI konsultant</b>
                    <span>Maktab tanlashda yordam</span>
                </div>
            </button>
        </aside>

        {{-- ===== MAIN ===== --}}
        <div class="idash-main">
            <header class="idash-topbar">
                <div>
                    <h1>{{ $title }}</h1>
                    <div class="idash-topbar-sub">{{ $sub }}</div>
                </div>

                <div class="idash-user-wrap">
                    <div class="idash-user-btn" style="cursor:default">
                        <span class="idash-user-ava">{{ \App\Support\MaktabgidData::monogram($user->name) }}</span>
                        <span style="text-align:left">
                            <b>{{ explode(' ', trim($user->name))[0] }}</b>
                            <span>Ota-ona</span>
                        </span>
                    </div>
                </div>
            </header>

            <div class="idash-content">
                {{ $slot }}
            </div>
        </div>
    </div>

    {{-- ===== AI KONSULTANT (barcha ota-ona kabineti sahifalarida ishlaydi) ===== --}}
    <div class="ai-panel" id="js-ai-panel" style="display:none">
        <header class="ai-head">
            <span class="ai-ava">
                <x-maktabgid.icon name="robot" :width="22" :height="22" />
            </span>
            <div class="ai-head-main">
                <b>AI konsultant</b>
                <span><span class="ondot"></span> Platforma maʼlumotlari asosida</span>
            </div>
            <button class="ai-close" type="button" id="js-ai-close">
                <x-maktabgid.icon name="close" :width="20" :height="20" />
            </button>
        </header>
        <div class="ai-msgs" id="js-ai-msgs"></div>
        <form class="ai-input" id="js-ai-form">
            <input id="js-ai-input" placeholder="Savolingizni yozing…" autocomplete="off" />
            <button class="chat-send" type="submit">
                <x-maktabgid.icon name="send" :width="18" :height="18" />
            </button>
        </form>
    </div>

    <script>
    var MG_SCHOOLS = @json(\App\Support\MaktabgidData::schools());
    </script>
@endunless

<script src="{{ asset('js/maktabgid.js') }}"></script>

@if ($user)
<script>
(function () {
    var panel  = document.getElementById("js-ai-panel");
    var msgs   = document.getElementById("js-ai-msgs");
    var form   = document.getElementById("js-ai-form");
    var input  = document.getElementById("js-ai-input");
    var openBtn = document.getElementById("js-cab-ai-btn");
    var closeBtn = document.getElementById("js-ai-close");
    if (!panel || !openBtn) return;

    /* ---------- open / close ---------- */
    openBtn.addEventListener("click", function () {
        panel.style.display = "flex";
        if (msgs.children.length === 0) renderWelcome();
        if (input) input.focus();
    });
    if (closeBtn) {
        closeBtn.addEventListener("click", function () {
            panel.style.display = "none";
        });
    }

    /* ---------- message rendering ---------- */
    function addMsg(from, text, isTyping) {
        var row = document.createElement("div");
        row.className = "ai-row " + from;
        if (from === "ai") {
            var mini = document.createElement("span");
            mini.className = "ai-mini";
            mini.innerHTML = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/><circle cx="9" cy="14" r="1" fill="currentColor"/><circle cx="15" cy="14" r="1" fill="currentColor"/></svg>';
            row.appendChild(mini);
        }
        var bubble = document.createElement("div");
        if (isTyping) {
            bubble.className = "ai-bubble typing";
            bubble.innerHTML = "<i></i><i></i><i></i>";
        } else {
            bubble.className = "ai-bubble";
            bubble.textContent = text;
        }
        row.appendChild(bubble);
        msgs.appendChild(row);
        msgs.scrollTop = msgs.scrollHeight;
        return row;
    }

    function renderWelcome() {
        addMsg("ai", "Assalomu alaykum! Men MaktabGID AI konsultantiman 🤖 Platformadagi 100+ muassasa maʼlumoti asosida sizga mos maktab, bogʻcha yoki oʻquv markazini tanlashda yordam beraman. Nima qidiryapsiz?");
        var suggestWrap = document.createElement("div");
        suggestWrap.className = "ai-suggest";
        var suggestions = [
            "Yunusobodda ingliz tili kuchli maktab",
            "6 mln gacha arzon variantlar",
            "IELTS ga tayyorlaydigan markazlar",
        ];
        suggestions.forEach(function (s) {
            var btn = document.createElement("button");
            btn.type = "button";
            btn.textContent = s;
            btn.addEventListener("click", function () {
                suggestWrap.remove();
                handleSend(s);
            });
            suggestWrap.appendChild(btn);
        });
        msgs.appendChild(suggestWrap);
        msgs.scrollTop = msgs.scrollHeight;
    }

    /* ---------- AI response logic ---------- */
    function fmtPrice(p) { return Math.round(p / 1000000) + " mln"; }
    function fmtSchool(s) {
        var price = s.price ? fmtPrice(s.price) + " soʻm/oy" : "narx kelishilgan";
        return "• " + s.name + " — " + s.district + " tumani, " + price;
    }

    function aiAnswer(q) {
        var s = MG_SCHOOLS;
        var ql = q.toLowerCase();

        var districtMap = {
            "yunusobod": "Yunusobod", "mirzo ulug": "Mirzo Ulugʻbek",
            "chilonzor": "Chilonzor", "mirobod": "Mirobod",
            "yakkasaroy": "Yakkasaroy", "olmazor": "Olmazor",
            "sergeli": "Sergeli", "yashnobod": "Yashnobod",
            "shayxontohur": "Shayxontohur", "uchtepa": "Uchtepa",
        };
        for (var key in districtMap) {
            if (ql.indexOf(key) !== -1) {
                var d = districtMap[key];
                var found = s.filter(function (x) { return x.district === d; }).slice(0, 3);
                if (found.length) {
                    return d + " tumanidagi muassasalar:\n" + found.map(fmtSchool).join("\n") +
                           "\n\nBatafsil ma\'lumot uchun katalogda qidiring yoki maktab sahifasiga o\'ting.";
                }
            }
        }

        if (/arzon|budget|kam narx|kam pul|5 mln|6 mln|7 mln|qancha|narx|tolov/.test(ql)) {
            var maxPrice = 7000000;
            if (/3 mln|4 mln/.test(ql)) maxPrice = 4000000;
            else if (/5 mln/.test(ql)) maxPrice = 5000000;
            else if (/6 mln/.test(ql)) maxPrice = 6000000;
            var cheap = s.filter(function (x) { return x.price && x.price <= maxPrice; }).slice(0, 3);
            if (cheap.length) {
                return fmtPrice(maxPrice) + " soʻm gacha muassasalar:\n" + cheap.map(fmtSchool).join("\n");
            }
            return "Barcha maktablarning narxi 1.5 mln dan 20 mln soʻm orasida. Katalogda narx filtri orqali qidiring.";
        }

        if (/ingliz|english|xorijiy til|til kuchli/.test(ql)) {
            var eng = s.filter(function (x) { return x.lang && x.lang.toLowerCase().indexOf("ingliz") !== -1; }).slice(0, 4);
            return "Ingliz tili kuchli muassasalar:\n" + eng.map(fmtSchool).join("\n") +
                   "\n\nBu maktablarning barchasi ingliz tilida dars beradi.";
        }

        if (/ielts|sat|cefr|xalqaro imtihon/.test(ql)) {
            return "IELTS / SAT tayyorlovchi markazlar:\n• Bright Kids Markazi — Mirobod tumani, 1.5 mln soʻm/oy\n• Cambridge School — Mirobod tumani, 5.8 mln soʻm/oy\n• IT Park School — Mirzo Ulugʻbek tumani, 4.1 mln soʻm/oy\n\nBu muassasalar xalqaro imtihonlarga intensiv tayyorlaydi.";
        }

        if (/bogcha|bog.cha|kichkintoy|3 yosh|4 yosh|5 yosh|6 yosh|erta|maktabgacha/.test(ql)) {
            var bogcha = s.filter(function (x) { return x.cat === "bogcha"; });
            return "Bogʻchalar:\n" + bogcha.map(fmtSchool).join("\n") +
                   "\n\nIkkalasi ham yuqori reytingga ega va ingliz tilida ishlaydi.";
        }

        if (/markaz|kurs|repetitor|to.garak|qo.shimcha/.test(ql)) {
            var markaz = s.filter(function (x) { return x.cat === "markaz"; });
            return "Oʻquv markazlari:\n" + markaz.map(fmtSchool).join("\n") +
                   "\n\nQoʻshimcha ta\'lim va repetitor xizmatlar uchun ideal.";
        }

        if (/top|yaxshi|eng yaxshi|premium|sifatli|reyting/.test(ql)) {
            var top = s.slice().sort(function (a, b) { return b.rating - a.rating; }).slice(0, 4);
            return "Eng yuqori reytingli muassasalar:\n" + top.map(function (x) {
                return "• " + x.name + " — " + x.rating + "★, " + x.district + " tumani";
            }).join("\n");
        }

        if (/shanba|6 kun|hafta/.test(ql)) {
            var sat = s.filter(function (x) { return x.sat; }).slice(0, 4);
            return "Shanba kuni ishlaydigan muassasalar:\n" + sat.map(fmtSchool).join("\n");
        }

        return "Aniqroq qidiruv uchun menga quydagilarni aytishingiz mumkin:\n• Tuman (masalan, Yunusobod)\n• Narx oraligʻi (masalan, 6 mln gacha)\n• Til (ingliz tili kuchli)\n• Tur (bogʻcha, maktab, markaz)\n\nKatalogda 100+ muassasa mavjud — birga topamiz! 🎯";
    }

    /* ---------- send ---------- */
    function handleSend(text) {
        addMsg("me", text);
        if (input) input.value = "";
        var typingRow = addMsg("ai", "", true);
        setTimeout(function () {
            typingRow.remove();
            addMsg("ai", aiAnswer(text));
        }, 900);
    }

    form.addEventListener("submit", function (e) {
        e.preventDefault();
        var text = input ? input.value.trim() : "";
        if (!text) return;
        handleSend(text);
    });
}());
</script>
@endif
</body>
</html>

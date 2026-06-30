<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kabinet — {{ config('app.name', 'MaktabGID') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,600;12..96,700;12..96,800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/maktabgid.css') }}" />
</head>
<body>

    @php
        use App\Support\MaktabgidData;
    @endphp

    <x-maktabgid.nav />

    {{-- ===== PAGE HEAD ===== --}}
    <x-maktabgid.page-head
        icon="user"
        kicker="Ota-ona kabineti"
        title="Assalomu alaykum!"
        sub="Profilingiz, saqlangan muassasalar va arizalaringizni shu yerda boshqaring."
    />

    {{-- ===== NOT LOGGED IN STATE ===== --}}
    <div id="js-cab-guest" style="display:none">
        <div class="wrap" style="padding:60px 0;text-align:center">
            <div class="empty">
                <span class="empty-ico"><x-maktabgid.icon name="user" :width="28" :height="28" /></span>
                <p style="font-size:16px;font-weight:700;color:var(--ink)">Kabinetga kirish kerak</p>
                <p>Profilingizni ko'rish uchun tizimga kiring yoki ro'yxatdan o'ting.</p>
                <button class="btn btn-primary" data-modal-open="auth-modal">
                    <x-maktabgid.icon name="user" :width="17" :height="17" /> Kirish
                </button>
            </div>
        </div>
    </div>

    {{-- ===== CABINET BODY (shown when logged in) ===== --}}
    <div id="js-cab-body" class="wrap cab-body" style="display:none">

        {{-- ===== RAIL (sidebar) ===== --}}
        <aside class="cab-rail">

            {{-- User identity card --}}
            <div class="cab-id">
                <span id="js-cab-avatar" class="avatar" style="width:56px;height:56px;font-size:22px;background:linear-gradient(140deg,var(--primary),var(--primary-700))"></span>
                <div>
                    <b id="js-cab-name">—</b>
                    <span id="js-cab-phone">—</span>
                </div>
            </div>

            {{-- Tab navigation --}}
            <nav class="cab-nav">
                <button class="cab-navlink on js-cab-tab" data-tab="profil" type="button">
                    <x-maktabgid.icon name="user" :width="18" :height="18" />
                    Profil
                </button>
                <button class="cab-navlink js-cab-tab" data-tab="saved" type="button">
                    <x-maktabgid.icon name="heart" :width="18" :height="18" />
                    Saqlanganlar
                </button>
                <button class="cab-navlink js-cab-tab" data-tab="ariza" type="button">
                    <x-maktabgid.icon name="ticket" :width="18" :height="18" />
                    Arizalarim
                    <em>2</em>
                </button>
                <button class="cab-navlink js-cab-tab" data-tab="suhbat" type="button">
                    <x-maktabgid.icon name="chat" :width="18" :height="18" />
                    Suhbatlar
                    <em>1</em>
                </button>
                <button class="cab-navlink danger" type="button" id="js-logout-btn">
                    <x-maktabgid.icon name="logout" :width="18" :height="18" />
                    Chiqish
                </button>
            </nav>

            {{-- AI consultant CTA --}}
            <button class="cab-ai" type="button">
                <x-maktabgid.icon name="robot" :width="20" :height="20" />
                <div>
                    <b>AI konsultant</b>
                    <span>Maktab tanlashda yordam</span>
                </div>
            </button>

        </aside>

        {{-- ===== MAIN CONTENT ===== --}}
        <section class="cab-main">

            {{-- PROFIL tab --}}
            <div class="js-cab-panel" data-panel="profil">
                <div class="panel">
                    <div class="panel-head">
                        <h3>Shaxsiy ma'lumotlar</h3>
                        <button class="btn btn-ghost" type="button">
                            <x-maktabgid.icon name="edit" :width="16" :height="16" /> Tahrirlash
                        </button>
                    </div>
                    <div class="kv-grid">
                        <div class="kv">
                            <span>Ism Familiya</span>
                            <b id="js-kv-name">—</b>
                        </div>
                        <div class="kv">
                            <span>Telefon raqami</span>
                            <b id="js-kv-phone">—</b>
                        </div>
                        <div class="kv">
                            <span>Yosh</span>
                            <b id="js-kv-age">—</b>
                        </div>
                        <div class="kv">
                            <span>Yashash tumani</span>
                            <b id="js-kv-district">—</b>
                        </div>
                    </div>
                    <div class="cab-stats">
                        <div class="cstat"><b>0</b><span>saqlangan muassasa</span></div>
                        <div class="cstat"><b>2</b><span>yuborilgan ariza</span></div>
                        <div class="cstat"><b>1</b><span>faol suhbat</span></div>
                    </div>
                </div>
            </div>

            {{-- SAVED tab --}}
            <div class="js-cab-panel" data-panel="saved" style="display:none">
                <div class="panel">
                    <div class="panel-head"><h3>Saqlangan muassasalar</h3></div>
                    <div class="empty">
                        <span class="empty-ico"><x-maktabgid.icon name="heart" :width="26" :height="26" /></span>
                        <p>Hali muassasa saqlamadingiz.</p>
                        <a href="{{ route('welcome') }}" class="btn btn-primary">Katalogga o'tish</a>
                    </div>
                </div>
            </div>

            {{-- ARIZA tab --}}
            <div class="js-cab-panel" data-panel="ariza" style="display:none">
                <div class="panel">
                    <div class="panel-head"><h3>Mening arizalarim</h3></div>
                    <div class="cab-list">
                        <div class="cab-item static">
                            <span class="appl-ico"><x-maktabgid.icon name="ticket" :width="20" :height="20" /></span>
                            <div class="cab-item-main">
                                <b>Sodiq International School</b>
                                <span>Alisher Karimov · 1-sinf · 2025-09-01</span>
                            </div>
                            <span class="appl-status done">Tasdiqlandi</span>
                        </div>
                        <div class="cab-item static">
                            <span class="appl-ico"><x-maktabgid.icon name="ticket" :width="20" :height="20" /></span>
                            <div class="cab-item-main">
                                <b>Mirzo Ulug'bek Litseyi</b>
                                <span>Alisher Karimov · 5-sinf · 2025-09-05</span>
                            </div>
                            <span class="appl-status pending">Ko'rib chiqilmoqda</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SUHBAT tab --}}
            <div class="js-cab-panel" data-panel="suhbat" style="display:none">
                <div class="panel">
                    <div class="panel-head">
                        <h3>Suhbatlar</h3>
                        <button class="btn btn-ghost" type="button">
                            Hammasini ochish <x-maktabgid.icon name="arrowR" :width="15" :height="15" />
                        </button>
                    </div>
                    <div class="cab-list">
                        <button class="cab-item" type="button">
                            <span class="avatar" style="width:46px;height:46px;border-radius:10px;font-size:18px;background:linear-gradient(140deg,#6c63ff,#3b82f6)">S</span>
                            <div class="cab-item-main">
                                <b>Sodiq International School</b>
                                <span>Ariza haqida ma'lumot berishimiz mumkin...</span>
                            </div>
                            <span class="unread-dot">1</span>
                            <x-maktabgid.icon name="chevronR" :width="18" :height="18" />
                        </button>
                    </div>
                </div>
            </div>

        </section>
    </div>

    <x-maktabgid.trust-strip />
    <x-maktabgid.cta-band />
    <x-maktabgid.footer />

    <script src="{{ asset('js/maktabgid.js') }}"></script>
</body>
</html>

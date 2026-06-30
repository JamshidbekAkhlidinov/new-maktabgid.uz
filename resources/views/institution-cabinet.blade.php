<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Muassasa kabineti — {{ config('app.name', 'MaktabGID') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,600;12..96,700;12..96,800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/maktabgid.css') }}" />
</head>
<body>

    @php
        use App\Support\MaktabgidData;
        $specializations = MaktabgidData::specializations();
        $districts       = MaktabgidData::districts();
        $requests = [
            ['name' => 'Dilnoza Murodova',  'child' => 'Asadbek, 7 yosh', 'grade' => '1-sinf',     'phone' => '+998 90 123 45 67', 'ago' => '2 soat oldin', 'status' => 'new'],
            ['name' => 'Sardor Tursunov',   'child' => 'Madina, 6 yosh',  'grade' => 'Tayyorlov',  'phone' => '+998 91 234 56 78', 'ago' => 'Kecha',        'status' => 'new'],
            ['name' => 'Gulnora Aliyeva',   'child' => 'Jasur, 10 yosh',  'grade' => '4-sinf',     'phone' => '+998 93 345 67 89', 'ago' => '2 kun oldin',  'status' => 'done'],
        ];
    @endphp

    <x-maktabgid.nav />

    {{-- ===== PAGE HEAD ===== --}}
    <x-maktabgid.page-head
        icon="building"
        kicker="Muassasa kabineti"
        title="Muassasa kabineti"
        sub="Maktab, bog'cha yoki o'quv markazingiz ma'lumotlarini to'ldiring — minglab ota-ona ko'radi."
    />

    {{-- ===== NOT LOGGED IN / WRONG KIND ===== --}}
    <div id="js-inst-guest" style="display:none">
        <div class="wrap" style="padding:60px 0;text-align:center">
            <div class="empty">
                <span class="empty-ico"><x-maktabgid.icon name="building" :width="28" :height="28" /></span>
                <p style="font-size:16px;font-weight:700;color:var(--ink)">Muassasa kabinetiga kirish kerak</p>
                <p>Muassasa sifatida ro'yxatdan o'ting yoki tizimga kiring.</p>
                <button class="btn btn-primary" data-modal-open="auth-modal">
                    <x-maktabgid.icon name="building" :width="17" :height="17" /> Muassasa kabinetini ochish
                </button>
            </div>
        </div>
    </div>

    {{-- ===== INSTITUTION CABINET BODY ===== --}}
    <div id="js-inst-body" class="wrap cab-body" style="display:none">

        {{-- ===== RAIL ===== --}}
        <aside class="cab-rail">
            <div class="cab-id">
                <span id="js-inst-avatar" class="avatar" style="width:56px;height:56px;border-radius:12px;font-size:22px;background:linear-gradient(140deg,var(--primary),var(--primary-700))"></span>
                <div>
                    <b id="js-inst-name">—</b>
                    <span id="js-inst-kind">—</span>
                </div>
            </div>

            <nav class="cab-nav">
                <button class="cab-navlink on js-inst-tab" data-tab="profil" type="button">
                    <x-maktabgid.icon name="building" :width="18" :height="18" />
                    Muassasa ma'lumoti
                </button>
                <button class="cab-navlink js-inst-tab" data-tab="ariza" type="button">
                    <x-maktabgid.icon name="ticket" :width="18" :height="18" />
                    Ekskursiya arizalari
                    <em>2</em>
                </button>
                <button class="cab-navlink js-inst-tab" data-tab="stat" type="button">
                    <x-maktabgid.icon name="grid" :width="18" :height="18" />
                    Statistika
                </button>
                <button class="cab-navlink danger" type="button" id="js-inst-logout">
                    <x-maktabgid.icon name="logout" :width="18" :height="18" />
                    Chiqish
                </button>
            </nav>

            {{-- Accept state toggle --}}
            <div class="accept-card on" id="js-accept-card">
                <div>
                    <b>Qabul holati</b>
                    <span id="js-accept-text">Arizalar qabul qilinmoqda</span>
                </div>
                <button class="switch on" type="button" id="js-accept-toggle"></button>
            </div>
        </aside>

        {{-- ===== MAIN CONTENT ===== --}}
        <section class="cab-main">

            {{-- PROFIL TAB --}}
            <div class="js-inst-panel" data-panel="profil">
                <div class="inst-grid">

                    {{-- ===== FORM ===== --}}
                    <div class="panel">
                        <div class="panel-head">
                            <h3>Ma'lumotlarni kiritish</h3>
                            <span class="saved-pill" id="js-saved-pill" style="display:none">
                                <x-maktabgid.icon name="check" :width="14" :height="14" /> Saqlandi
                            </span>
                        </div>

                        <div class="form-section">Asosiy ma'lumot</div>
                        <label class="field">
                            <span class="field-label"><x-maktabgid.icon name="building" :width="14" :height="14" /> Muassasa nomi</span>
                            <span class="field-control">
                                <x-maktabgid.icon name="building" :width="17" :height="17" />
                                <input type="text" id="js-f-name" placeholder="Masalan, Sodiq School" />
                            </span>
                        </label>
                        <div class="form-row2">
                            <label class="field">
                                <span class="field-label"><x-maktabgid.icon name="school" :width="14" :height="14" /> Turi</span>
                                <span class="field-control">
                                    <x-maktabgid.icon name="school" :width="17" :height="17" />
                                    <select id="js-f-kind">
                                        <option value="maktab">Xususiy maktab</option>
                                        <option value="bogcha">Xususiy bog'cha</option>
                                        <option value="markaz">O'quv markazi</option>
                                    </select>
                                </span>
                            </label>
                            <label class="field">
                                <span class="field-label"><x-maktabgid.icon name="globe" :width="14" :height="14" /> Ta'lim tili</span>
                                <span class="field-control">
                                    <x-maktabgid.icon name="globe" :width="17" :height="17" />
                                    <select id="js-f-lang">
                                        <option>Ingliz</option>
                                        <option>O'zbek</option>
                                        <option>Rus</option>
                                        <option>O'zbek / Ingliz</option>
                                        <option>Rus / Ingliz</option>
                                    </select>
                                </span>
                            </label>
                        </div>
                        <label class="field">
                            <span class="field-label">Qisqacha tavsif <em style="font-style:normal;font-size:11.5px;color:var(--ink-3);font-weight:600">ota-onalar ko'radi</em></span>
                            <span class="field-control">
                                <textarea id="js-f-about" rows="3" placeholder="Muassasangiz haqida 1–2 jumla: yondashuv, ustunliklar, dasturlar…" style="width:100%;resize:vertical"></textarea>
                            </span>
                        </label>

                        <div class="form-section">Joylashuv</div>
                        <div class="form-row2">
                            <label class="field">
                                <span class="field-label"><x-maktabgid.icon name="pin" :width="14" :height="14" /> Tuman</span>
                                <span class="field-control">
                                    <x-maktabgid.icon name="pin" :width="17" :height="17" />
                                    <select id="js-f-district">
                                        <option value="">Tanlang</option>
                                        @foreach ($districts as $d)
                                            <option value="{{ $d }}">{{ $d }}</option>
                                        @endforeach
                                    </select>
                                </span>
                            </label>
                            <label class="field">
                                <span class="field-label"><x-maktabgid.icon name="pin" :width="14" :height="14" /> Manzil</span>
                                <span class="field-control">
                                    <x-maktabgid.icon name="pin" :width="17" :height="17" />
                                    <input type="text" id="js-f-address" placeholder="Ko'cha, uy" />
                                </span>
                            </label>
                        </div>

                        <div class="form-section">Narx va dastur</div>
                        <div class="form-row2">
                            <label class="field">
                                <span class="field-label">Oylik narx (so'm) <em style="font-style:normal;font-size:11.5px;color:var(--ink-3);font-weight:600">ixtiyoriy</em></span>
                                <span class="field-control">
                                    <input type="number" id="js-f-price" placeholder="6500000" />
                                </span>
                            </label>
                            <label class="field">
                                <span class="field-label" id="js-f-grades-label">Sinflar</span>
                                <span class="field-control">
                                    <input type="text" id="js-f-grades" placeholder="1–11" />
                                </span>
                            </label>
                        </div>
                        <div class="form-row2">
                            <label class="field">
                                <span class="field-label"><x-maktabgid.icon name="clock" :width="14" :height="14" /> Ish vaqti</span>
                                <span class="field-control">
                                    <x-maktabgid.icon name="clock" :width="17" :height="17" />
                                    <input type="text" id="js-f-hours" value="08:00 – 18:00" />
                                </span>
                            </label>
                            <div class="field">
                                <span class="field-label">Shanba kuni</span>
                                <div class="switch-inline">
                                    <button class="switch on" type="button" id="js-sat-toggle"></button>
                                    <span id="js-sat-label">Ishlaydi</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">Ixtisosliklar <em style="font-style:normal;font-size:12px;font-weight:600;color:var(--ink-3)">(qidiruvda chiqadi)</em></div>
                        <div class="chip-row" id="js-spec-chips">
                            @foreach ($specializations as $sp)
                                <button type="button"
                                        class="chip{{ $sp['key'] === 'english' ? ' on' : '' }}"
                                        data-spec="{{ $sp['key'] }}">
                                    <x-maktabgid.icon :name="$sp['icon']" :width="13" :height="13" />
                                    {{ $sp['label'] }}
                                </button>
                            @endforeach
                        </div>

                        <div class="form-section">Rasmlar</div>
                        <div class="upload-row">
                            <button class="upload-slot" type="button">
                                <x-maktabgid.icon name="upload" :width="20" :height="20" />
                                <span>Asosiy rasm</span>
                            </button>
                            <button class="upload-slot" type="button">
                                <x-maktabgid.icon name="upload" :width="20" :height="20" />
                                <span>Rasm qo'shish</span>
                            </button>
                            <button class="upload-slot" type="button">
                                <x-maktabgid.icon name="upload" :width="20" :height="20" />
                                <span>Rasm qo'shish</span>
                            </button>
                        </div>

                        <button class="btn btn-primary form-submit" type="button" id="js-inst-save">
                            <x-maktabgid.icon name="check" :width="17" :height="17" /> Ma'lumotlarni saqlash
                        </button>
                    </div>

                    {{-- ===== LIVE PREVIEW ===== --}}
                    <div class="inst-preview">
                        <div class="preview-label">Ota-onalar ko'radigan kartochka</div>
                        <article class="scard preview-card">
                            <div class="scard-media" style="background:linear-gradient(140deg,var(--primary),var(--primary-700))">
                                <span class="scard-mono" id="js-prev-mono">?</span>
                                <span class="media-badge" id="js-prev-badge">Qabul ochiq</span>
                            </div>
                            <div class="scard-body">
                                <div class="scard-top">
                                    <h3 class="scard-name" id="js-prev-name">Muassasa nomi</h3>
                                    <span class="scard-rating">
                                        <x-maktabgid.icon name="star" class="star" :width="15" :height="15" fill="currentColor" />
                                        Yangi
                                    </span>
                                </div>
                                <div class="scard-meta">
                                    <span class="m"><x-maktabgid.icon name="pin" :width="15" :height="15" /> <span id="js-prev-district">Tuman</span></span>
                                    <span class="m"><x-maktabgid.icon name="users" :width="15" :height="15" /> <span id="js-prev-grades">—</span></span>
                                    <span class="m"><x-maktabgid.icon name="clock" :width="15" :height="15" /> <span id="js-prev-hours">08:00 – 18:00</span></span>
                                </div>
                                <div class="scard-tags">
                                    <span class="tag" id="js-prev-kind">Maktab</span>
                                    <span class="tag lang" id="js-prev-lang">Ingliz</span>
                                    <span class="tag sat" id="js-prev-sat">Shanba ish</span>
                                </div>
                                <div class="scard-foot">
                                    <div class="price" id="js-prev-price"><span>Narx kelishilgan</span></div>
                                </div>
                            </div>
                        </article>
                        <p class="preview-hint">O'zgartirishlar real vaqtda ko'rinadi. Saqlangach katalogda e'lon qilinadi.</p>
                    </div>
                </div>
            </div>

            {{-- ARIZA TAB --}}
            <div class="js-inst-panel" data-panel="ariza" style="display:none">
                <div class="panel">
                    <div class="panel-head">
                        <h3>Ekskursiya arizalari</h3>
                        <span class="results-count"><span>{{ count($requests) }} ta ariza</span></span>
                    </div>
                    <div class="req-table">
                        @foreach ($requests as $r)
                            <div class="req-row{{ $r['status'] === 'new' ? ' new' : '' }}">
                                <x-maktabgid.avatar :name="$r['name']" :size="42" />
                                <div class="req-main">
                                    <b>{{ $r['name'] }}</b>
                                    <span>{{ $r['child'] }} · {{ $r['grade'] }}</span>
                                </div>
                                <div class="req-contact">
                                    <a href="tel:{{ $r['phone'] }}">
                                        <x-maktabgid.icon name="phone" :width="14" :height="14" /> {{ $r['phone'] }}
                                    </a>
                                    <em>{{ $r['ago'] }}</em>
                                </div>
                                <div class="req-actions">
                                    @if ($r['status'] === 'new')
                                        <button class="btn btn-primary sm" type="button">Tasdiqlash</button>
                                        <button class="btn btn-ghost sm" type="button">Rad etish</button>
                                    @else
                                        <span class="appl-status done">Qabul qilindi</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- STAT TAB --}}
            <div class="js-inst-panel" data-panel="stat" style="display:none">
                <div class="panel">
                    <div class="panel-head"><h3>Statistika (oxirgi 30 kun)</h3></div>
                    <div class="cab-stats wide">
                        <div class="cstat"><b>1 248</b><span>profil ko'rishlar</span></div>
                        <div class="cstat"><b>37</b><span>ekskursiya arizasi</span></div>
                        <div class="cstat"><b>52</b><span>suhbat boshlandi</span></div>
                        <div class="cstat"><b>4.6★</b><span>o'rtacha reyting</span></div>
                    </div>
                    <div class="bars">
                        @foreach ([40, 62, 55, 78, 70, 90, 84] as $i => $h)
                            <div class="bar-col">
                                <span class="bar" style="height:{{ $h }}%"></span>
                                <em>{{ ['Du','Se','Ch','Pa','Ju','Sh','Ya'][$i] }}</em>
                            </div>
                        @endforeach
                    </div>
                    <p class="preview-hint">Haftalik profil ko'rishlar dinamikasi.</p>
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

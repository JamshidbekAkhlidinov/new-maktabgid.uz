<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
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
        $kindLabels = ['maktab' => "Xususiy maktab", 'bogcha' => "Xususiy bog'cha", 'markaz' => "O'quv markazi"];
        $statusLabels = ['pending' => "Ko'rib chiqilmoqda", 'confirmed' => 'Qabul qilindi', 'rejected' => 'Rad etildi'];
        $i = $institution;
        $mySpecs = $i ? $i->specializations->pluck('key')->all() : [];
    @endphp

    <x-maktabgid.nav />

    {{-- ===== PAGE HEAD ===== --}}
    <x-maktabgid.page-head
        icon="building"
        kicker="Muassasa kabineti"
        title="{{ $i?->name ?? 'Muassasa kabineti' }}"
        sub="Maktab, bog'cha yoki o'quv markazingiz ma'lumotlarini to'ldiring — minglab ota-ona ko'radi."
    />

    @unless ($i)
        {{-- ===== NOT LOGGED IN / WRONG KIND ===== --}}
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
    @else
        {{-- ===== INSTITUTION CABINET BODY (real DB'dan) ===== --}}
        <div class="wrap cab-body">

            {{-- ===== RAIL ===== --}}
            <aside class="cab-rail">
                <div class="cab-id">
                    <span class="avatar" style="width:56px;height:56px;border-radius:12px;font-size:22px;background:linear-gradient(140deg,var(--primary),var(--primary-700))">{{ MaktabgidData::monogram($i->name) }}</span>
                    <div>
                        <b>{{ $i->name }}</b>
                        <span>{{ $kindLabels[$i->type] ?? 'Muassasa' }}</span>
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
                        <em>{{ $stats['applications'] }}</em>
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

                {{-- Accept state toggle — real holat DB'dan --}}
                <div class="accept-card{{ $i->accepting ? ' on' : '' }}" id="js-accept-card">
                    <div>
                        <b>Qabul holati</b>
                        <span id="js-accept-text">{{ $i->accepting ? 'Arizalar qabul qilinmoqda' : 'Qabul vaqtincha yopiq' }}</span>
                    </div>
                    <button class="switch{{ $i->accepting ? ' on' : '' }}" type="button" id="js-accept-toggle"></button>
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
                                    <input type="text" id="js-f-name" value="{{ $i->name }}" placeholder="Masalan, Sodiq School" />
                                </span>
                            </label>
                            <div class="form-row2">
                                <label class="field">
                                    <span class="field-label"><x-maktabgid.icon name="school" :width="14" :height="14" /> Turi</span>
                                    <span class="field-control">
                                        <x-maktabgid.icon name="school" :width="17" :height="17" />
                                        <select id="js-f-kind">
                                            <option value="maktab" @selected($i->type === 'maktab')>Xususiy maktab</option>
                                            <option value="bogcha" @selected($i->type === 'bogcha')>Xususiy bog'cha</option>
                                            <option value="markaz" @selected($i->type === 'markaz')>O'quv markazi</option>
                                        </select>
                                    </span>
                                </label>
                                <label class="field">
                                    <span class="field-label"><x-maktabgid.icon name="globe" :width="14" :height="14" /> Ta'lim tili</span>
                                    <span class="field-control">
                                        <x-maktabgid.icon name="globe" :width="17" :height="17" />
                                        <select id="js-f-lang">
                                            @foreach (['Ingliz', "O'zbek", 'Rus', "O'zbek / Ingliz", 'Rus / Ingliz'] as $langOpt)
                                                <option @selected($i->lang === $langOpt)>{{ $langOpt }}</option>
                                            @endforeach
                                        </select>
                                    </span>
                                </label>
                            </div>
                            <label class="field">
                                <span class="field-label">Qisqacha tavsif <em style="font-style:normal;font-size:11.5px;color:var(--ink-3);font-weight:600">ota-onalar ko'radi</em></span>
                                <span class="field-control">
                                    <textarea id="js-f-about" rows="3" placeholder="Muassasangiz haqida 1–2 jumla: yondashuv, ustunliklar, dasturlar…" style="width:100%;resize:vertical">{{ $i->about }}</textarea>
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
                                                <option value="{{ $d }}" @selected($i->district?->name === $d)>{{ $d }}</option>
                                            @endforeach
                                        </select>
                                    </span>
                                </label>
                                <label class="field">
                                    <span class="field-label"><x-maktabgid.icon name="pin" :width="14" :height="14" /> Manzil</span>
                                    <span class="field-control">
                                        <x-maktabgid.icon name="pin" :width="17" :height="17" />
                                        <input type="text" id="js-f-address" value="{{ $i->address }}" placeholder="Ko'cha, uy" />
                                    </span>
                                </label>
                            </div>

                            <div class="form-section">Narx va dastur</div>
                            <div class="form-row2">
                                <label class="field">
                                    <span class="field-label">Oylik narx (so'm) <em style="font-style:normal;font-size:11.5px;color:var(--ink-3);font-weight:600">ixtiyoriy</em></span>
                                    <span class="field-control">
                                        <input type="number" id="js-f-price" value="{{ $i->monthly_price }}" placeholder="6500000" />
                                    </span>
                                </label>
                                <label class="field">
                                    <span class="field-label" id="js-f-grades-label">{{ $i->type === 'maktab' ? 'Sinflar' : "Yosh oralig'i" }}</span>
                                    <span class="field-control">
                                        <input type="text" id="js-f-grades" value="{{ $i->grades }}" placeholder="1–11" />
                                    </span>
                                </label>
                            </div>
                            <div class="form-row2">
                                <label class="field">
                                    <span class="field-label"><x-maktabgid.icon name="clock" :width="14" :height="14" /> Ish vaqti</span>
                                    <span class="field-control">
                                        <x-maktabgid.icon name="clock" :width="17" :height="17" />
                                        <input type="text" id="js-f-hours" value="{{ $i->work_hours ?: '08:00 – 18:00' }}" />
                                    </span>
                                </label>
                                <div class="field">
                                    <span class="field-label">Shanba kuni</span>
                                    <div class="switch-inline">
                                        <button class="switch{{ $i->works_saturday ? ' on' : '' }}" type="button" id="js-sat-toggle"></button>
                                        <span id="js-sat-label">{{ $i->works_saturday ? 'Ishlaydi' : 'Dam olish' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-section">Ixtisosliklar <em style="font-style:normal;font-size:12px;font-weight:600;color:var(--ink-3)">(qidiruvda chiqadi)</em></div>
                            <div class="chip-row" id="js-spec-chips">
                                @foreach ($specializations as $sp)
                                    <button type="button"
                                            class="chip{{ in_array($sp['key'], $mySpecs, true) ? ' on' : '' }}"
                                            data-spec="{{ $sp['key'] }}">
                                        <x-maktabgid.icon :name="$sp['icon']" :width="13" :height="13" />
                                        {{ $sp['label'] }}
                                    </button>
                                @endforeach
                            </div>

                            @php $mediaGallery = $i->media->where('type', 'gallery')->values(); @endphp
                            <div class="form-section">Rasmlar <em id="js-media-count" style="font-style:normal;font-size:12px;font-weight:600;color:var(--ink-3)">({{ $mediaGallery->count() }} ta yuklangan)</em></div>
                            <div class="upload-row">
                                @for ($slotIdx = 0; $slotIdx < 3; $slotIdx++)
                                    @php $existing = $mediaGallery->get($slotIdx); @endphp
                                    <label class="upload-slot js-media-upload{{ $existing ? ' filled' : '' }}"
                                           data-media-type="gallery"
                                           @if ($existing) style="background-image:url('{{ $existing->url }}')" @endif>
                                        <input type="file" accept="image/*" hidden />
                                        <x-maktabgid.icon name="upload" :width="20" :height="20" />
                                        <span>{{ $existing ? 'Yuklandi ✓' : ($slotIdx === 0 ? 'Asosiy rasm' : "Rasm qo'shish") }}</span>
                                    </label>
                                @endfor
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
                                    <span class="scard-mono" id="js-prev-mono">{{ MaktabgidData::monogram($i->name) }}</span>
                                    <span class="media-badge" id="js-prev-badge" @if(! $i->accepting) style="display:none" @endif>Qabul ochiq</span>
                                </div>
                                <div class="scard-body">
                                    <div class="scard-top">
                                        <h3 class="scard-name" id="js-prev-name">{{ $i->name }}</h3>
                                        <span class="scard-rating">
                                            <x-maktabgid.icon name="star" class="star" :width="15" :height="15" fill="currentColor" />
                                            {{ $i->rating > 0 ? $i->rating : 'Yangi' }}
                                        </span>
                                    </div>
                                    <div class="scard-meta">
                                        <span class="m"><x-maktabgid.icon name="pin" :width="15" :height="15" /> <span id="js-prev-district">{{ $i->district?->name ?? 'Tuman' }}</span></span>
                                        <span class="m"><x-maktabgid.icon name="users" :width="15" :height="15" /> <span id="js-prev-grades">{{ $i->grades ?: '—' }}</span></span>
                                        <span class="m"><x-maktabgid.icon name="clock" :width="15" :height="15" /> <span id="js-prev-hours">{{ $i->work_hours ?: '08:00 – 18:00' }}</span></span>
                                    </div>
                                    <div class="scard-tags">
                                        <span class="tag" id="js-prev-kind">{{ $kindLabels[$i->type] ?? 'Maktab' }}</span>
                                        <span class="tag lang" id="js-prev-lang">{{ $i->lang ?: 'Ingliz' }}</span>
                                        <span class="tag sat" id="js-prev-sat" @if(! $i->works_saturday) style="display:none" @endif>Shanba ish</span>
                                    </div>
                                    <div class="scard-foot">
                                        <div class="price" id="js-prev-price">
                                            @if ($i->monthly_price)
                                                <b>{{ number_format($i->monthly_price, 0, ',', ' ') }}</b> <span>so'm / oy</span>
                                            @else
                                                <span>Narx kelishilgan</span>
                                            @endif
                                        </div>
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
                            <span class="results-count"><span>{{ $applications->count() }} ta ariza</span></span>
                        </div>
                        @if ($applications->isEmpty())
                            <div class="empty">
                                <span class="empty-ico"><x-maktabgid.icon name="ticket" :width="26" :height="26" /></span>
                                <p>Hali ariza tushmagan.</p>
                            </div>
                        @else
                            <div class="req-table">
                                @foreach ($applications as $app)
                                    <div class="req-row{{ $app->status === 'pending' ? ' new' : '' }}" data-app-id="{{ $app->id }}">
                                        <x-maktabgid.avatar :name="$app->parent_name" :size="42" />
                                        <div class="req-main">
                                            <b>{{ $app->parent_name }}</b>
                                            <span>{{ $app->child_name }} · {{ $app->target_grade ?? $app->current_grade ?? '—' }}</span>
                                        </div>
                                        <div class="req-contact">
                                            <a href="tel:{{ $app->parent_phone }}">
                                                <x-maktabgid.icon name="phone" :width="14" :height="14" /> {{ $app->parent_phone }}
                                            </a>
                                            <em>{{ $app->created_at->diffForHumans() }}</em>
                                        </div>
                                        <div class="req-actions">
                                            @if ($app->status === 'pending')
                                                <button class="btn btn-primary sm" type="button" data-app-status="confirmed">Tasdiqlash</button>
                                                <button class="btn btn-ghost sm" type="button" data-app-status="rejected">Rad etish</button>
                                            @else
                                                <span class="appl-status {{ $app->status === 'confirmed' ? 'done' : 'rejected' }}">{{ $statusLabels[$app->status] ?? $app->status }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- STAT TAB --}}
                <div class="js-inst-panel" data-panel="stat" style="display:none">
                    <div class="panel">
                        <div class="panel-head"><h3>Statistika</h3></div>
                        <div class="cab-stats wide">
                            <div class="cstat"><b>{{ $stats['applications'] }}</b><span>ekskursiya arizasi</span></div>
                            <div class="cstat"><b>{{ $stats['pending'] ?? 0 }}</b><span>ko'rib chiqilmoqda</span></div>
                            <div class="cstat"><b>{{ $stats['conversations'] }}</b><span>suhbat boshlandi</span></div>
                            <div class="cstat"><b>{{ $i->rating > 0 ? $i->rating.'★' : '—' }}</b><span>o'rtacha reyting</span></div>
                        </div>
                        <p class="preview-hint">Profil ko'rishlar va haftalik dinamika keyingi bosqichda qo'shiladi.</p>
                    </div>
                </div>

            </section>
        </div>
    @endunless

    <x-maktabgid.trust-strip />
    <x-maktabgid.cta-band />
    <x-maktabgid.footer />

    <script src="{{ asset('js/maktabgid.js') }}"></script>
</body>
</html>

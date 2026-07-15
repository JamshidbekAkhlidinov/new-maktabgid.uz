@php
    $i = $institution;
    $kindLabels = ['maktab' => "Xususiy maktab", 'bogcha' => "Xususiy bog'cha", 'markaz' => "O'quv markazi"];
    $weekHours = $i->work_hours ?: '08:00 – 18:00';
    $scheduleDays = [
        ['key' => 'mon', 'label' => 'Dushanba', 'abbr' => 'Dush', 'on' => true, 'hours' => $weekHours, 'main' => true],
        ['key' => 'tue', 'label' => 'Seshanba', 'abbr' => 'Ses', 'on' => true, 'hours' => $weekHours],
        ['key' => 'wed', 'label' => 'Chorshanba', 'abbr' => 'Cho', 'on' => true, 'hours' => $weekHours],
        ['key' => 'thu', 'label' => 'Payshanba', 'abbr' => 'Pay', 'on' => true, 'hours' => $weekHours],
        ['key' => 'fri', 'label' => 'Juma', 'abbr' => 'Jum', 'on' => true, 'hours' => $weekHours],
        ['key' => 'sat', 'label' => 'Shanba', 'abbr' => 'Shan', 'on' => $i->works_saturday, 'hours' => '09:00 – 14:00', 'isSat' => true],
        ['key' => 'sun', 'label' => 'Yakshanba', 'abbr' => 'Yak', 'on' => false, 'hours' => '09:00 – 14:00'],
    ];
@endphp

<x-institution.shell
    active="profile"
    title="Muassasa profili"
    sub="Ma'lumotlaringizni to'ldiring — minglab ota-ona ko'radi"
    :institution="$institution"
    :organizations="$organizations"
    :counts="$counts"
    :user="$user"
>
    {{-- Diqqat: bu slot mazmuni x-institution.shell'ning @unless($institution) tekshiruvidan
         OLDIN render qilinadi (Blade slot capture tartibi shunday) — shuning uchun $institution
         bo'lmaganda $i->... chaqiruvlari xato bermasligi uchun butun tanani @if bilan o'raymiz. --}}
    @if ($institution)

    {{-- Qabul holati — real holat DB'dan, PATCH /ajax/institution/me/accepting --}}
    <div class="accept-card{{ $i->accepting ? ' on' : '' }}" id="js-accept-card">
        <div>
            <b>Qabul holati</b>
            <span id="js-accept-text">{{ $i->accepting ? 'Arizalar qabul qilinmoqda' : 'Qabul vaqtincha yopiq' }}</span>
        </div>
        <button class="switch{{ $i->accepting ? ' on' : '' }}" type="button" id="js-accept-toggle"></button>
    </div>

    <div class="inst-grid">

        {{-- ===== FORM ===== --}}
        <div class="panel">
            <div class="panel-head">
                <h3>Muassasa ma'lumotlari</h3>
                <div style="display:flex;align-items:center;gap:10px">
                    <span class="saved-pill" id="js-saved-pill" style="display:none">
                        <x-maktabgid.icon name="check" :width="14" :height="14" /> Saqlandi
                    </span>
                    <button type="button" class="btn btn-ghost sm" data-modal-open="idash-org-add-modal">
                        <x-maktabgid.icon name="plus" :width="15" :height="15" /> Yangi muassasa
                    </button>
                </div>
            </div>

            <div class="form-section" style="border-top:none;padding-top:0">Kategoriya</div>
            <div class="kind-pill-row" id="js-kind-pills">
                <button type="button" class="kind-pill{{ $i->type === 'maktab' ? ' on' : '' }}" data-kind="maktab">
                    <x-maktabgid.icon name="school" :width="16" :height="16" /> Maktab
                </button>
                <button type="button" class="kind-pill{{ $i->type === 'bogcha' ? ' on' : '' }}" data-kind="bogcha">
                    <x-maktabgid.icon name="heart" :width="16" :height="16" /> Bog'cha
                </button>
                <button type="button" class="kind-pill{{ $i->type === 'markaz' ? ' on' : '' }}" data-kind="markaz">
                    <x-maktabgid.icon name="book" :width="16" :height="16" /> O'quv markazi
                </button>
                <select id="js-f-kind" hidden>
                    <option value="maktab" @selected($i->type === 'maktab')>Xususiy maktab</option>
                    <option value="bogcha" @selected($i->type === 'bogcha')>Xususiy bog'cha</option>
                    <option value="markaz" @selected($i->type === 'markaz')>O'quv markazi</option>
                </select>
            </div>

            <div class="form-section">Asosiy ma'lumot</div>
            <label class="field">
                <span class="field-label"><x-maktabgid.icon name="building" :width="14" :height="14" /> Muassasa nomi</span>
                <span class="field-control">
                    <x-maktabgid.icon name="building" :width="17" :height="17" />
                    <input type="text" id="js-f-name" value="{{ $i->name }}" placeholder="Masalan, Sodiq School" />
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
            <label class="field">
                <span class="field-label">Mo'ljal <em style="font-style:normal;font-size:11.5px;color:var(--ink-3);font-weight:600">yaqin joylashuv orientiri</em></span>
                <span class="field-control">
                    <input type="text" id="js-f-landmark" placeholder="Masalan, metro bekati yonida, savdo markazi ro'parasida" />
                </span>
            </label>

            <div class="form-section">Telefon raqamlar</div>
            <div style="display:flex;flex-direction:column;gap:10px" id="js-phone-list">
                <div class="phone-row">
                    <span class="phone-ico"><x-maktabgid.icon name="phone" :width="17" :height="17" /></span>
                    <span class="field-control" style="flex:1">
                        <input type="tel" value="{{ $i->owner?->phone }}" placeholder="+998 __ ___ __ __" />
                    </span>
                    <button type="button" class="phone-del js-phone-del" title="O'chirish"><x-maktabgid.icon name="close" :width="16" :height="16" /></button>
                </div>
            </div>
            <button type="button" class="form-addlink" id="js-phone-add">
                <x-maktabgid.icon name="plus" :width="15" :height="15" /> Raqam qo'shish
            </button>

            <div class="form-section">Ish vaqti va kunlari</div>
            <p style="font-size:12.5px;color:var(--ink-3);font-weight:600;margin-top:-8px">Har bir kun uchun alohida ish vaqti belgilang</p>
            <div style="display:flex;flex-direction:column;gap:8px" id="js-day-rows">
                @foreach ($scheduleDays as $d)
                    <div class="day-row{{ $d['on'] ? ' on' : '' }}" data-abbr="{{ $d['abbr'] }}">
                        <button type="button"
                                class="switch js-day-toggle{{ $d['on'] ? ' on' : '' }}"
                                @if (! empty($d['isSat'])) id="js-sat-toggle" @endif>
                        </button>
                        <span class="day-label">{{ $d['label'] }}</span>
                        <input type="text"
                               class="day-hours-input"
                               @if (! empty($d['main'])) id="js-f-hours" @endif
                               value="{{ $d['hours'] }}"
                               placeholder="09:00 – 18:00"
                               style="{{ $d['on'] ? '' : 'display:none' }}" />
                    </div>
                @endforeach
            </div>

            <div class="form-section">Muassasa haqida</div>
            <label class="field">
                <span class="field-label">Qisqacha tavsif</span>
                <span class="field-control">
                    <textarea id="js-f-about" rows="3" placeholder="Muassasangiz haqida 1–2 jumla: yondashuv, ustunliklar, dasturlar…" style="width:100%;resize:vertical">{{ $i->about }}</textarea>
                </span>
            </label>
            <label class="field">
                <span class="field-label">Qo'shimcha ma'lumot <em style="font-style:normal;font-size:11.5px;color:var(--ink-3);font-weight:600">qulayliklar, xizmatlar</em></span>
                <span class="field-control">
                    <textarea id="js-f-extra" rows="2" placeholder="Basseyn, transport xizmati, kengaytirilgan kun guruhi…" style="width:100%;resize:vertical"></textarea>
                </span>
            </label>

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

            <div class="form-section">Infratuzilma va qulayliklar <em style="font-style:normal;font-size:12px;font-weight:600;color:var(--ink-3)">(profil sahifasida chiqadi)</em></div>
            <div class="chip-row" id="js-facility-chips">
                @foreach ($facilityCatalog as $f)
                    <button type="button"
                            class="chip{{ in_array($f['key'], $myFacilities, true) ? ' on' : '' }}"
                            data-facility="{{ $f['key'] }}">
                        <x-maktabgid.icon :name="$f['i']" :width="13" :height="13" />
                        {{ $f['t'] }}
                    </button>
                @endforeach
            </div>

            <div class="form-section">Narxlar <em style="font-style:normal;font-size:12px;font-weight:600;color:var(--ink-3)">(har sinf/guruh va o'quv tili uchun alohida narx va chegirma — ota-onalar profilingizda ko'radi, kataloqda eng arzoni ko'rsatiladi)</em></div>
            <div class="price-head">
                <span>Sinf / guruh</span><span>O'quv tili</span><span>Oylik narx (so'm)</span><span>Chegirma</span><span></span>
            </div>
            <div style="display:flex;flex-direction:column;gap:8px" id="js-price-rows">
                @forelse ($priceRows as $p)
                    <div class="price-row">
                        <input type="text" value="{{ $p->grade }}" placeholder="1-4-sinf (boshlang'ich)" />
                        <select>
                            <option @selected($p->lang === "O'zbek")>O'zbek</option>
                            <option @selected($p->lang === 'Rus')>Rus</option>
                            <option @selected($p->lang === 'Ingliz')>Ingliz</option>
                        </select>
                        <input type="text" value="{{ number_format($p->monthly_price, 0, ',', ' ') }}" placeholder="4 500 000" />
                        <input type="text" value="{{ $p->discount }}" placeholder="—" />
                        <button type="button" class="price-del js-price-del" title="O'chirish"><x-maktabgid.icon name="close" :width="15" :height="15" /></button>
                    </div>
                @empty
                    <div class="price-row">
                        <input type="text" value="{{ $i->grades ?: "1-4-sinf (boshlang'ich)" }}" placeholder="1-4-sinf (boshlang'ich)" />
                        <select>
                            <option @selected($i->lang === "O'zbek")>O'zbek</option>
                            <option @selected($i->lang === 'Rus')>Rus</option>
                            <option @selected($i->lang === 'Ingliz')>Ingliz</option>
                        </select>
                        <input type="text" placeholder="4 500 000" />
                        <input type="text" placeholder="—" />
                        <button type="button" class="price-del js-price-del" title="O'chirish"><x-maktabgid.icon name="close" :width="15" :height="15" /></button>
                    </div>
                @endforelse
            </div>
            <button type="button" class="form-addlink" id="js-price-add">
                <x-maktabgid.icon name="plus" :width="15" :height="15" /> Sinf / guruh qo'shish
            </button>

            <div class="idash-badge-soft" style="margin-top:4px">
                <x-maktabgid.icon name="sparkle" :width="14" :height="14" /> Mo'ljal, telefon raqamlar ro'yxati va kun-kun jadval hozircha faqat ko'rinish uchun — saqlashda asosiy ish vaqti, Shanba holati va narxlar real qabul qilinadi
            </div>

            <div class="form-section">Yoʻnalishlar va dastur <em style="font-style:normal;font-size:11.5px;color:var(--ink-3);font-weight:600">har biri uchun sarlavha va tavsif</em></div>
            <div style="display:flex;flex-direction:column;gap:8px" id="js-program-rows">
                @forelse ($programRows as $p)
                    <div class="js-program-row" style="display:flex;gap:10px;align-items:center">
                        <span class="field-control" style="flex:1"><input type="text" value="{{ $p['t'] ?? '' }}" placeholder="Masalan, Cambridge dasturi" /></span>
                        <span class="field-control" style="flex:1.4"><input type="text" value="{{ $p['d'] ?? '' }}" placeholder="Xalqaro standart va sertifikat" /></span>
                        <button type="button" class="phone-del js-program-del" title="O'chirish"><x-maktabgid.icon name="close" :width="16" :height="16" /></button>
                    </div>
                @empty
                    <div class="js-program-row" style="display:flex;gap:10px;align-items:center">
                        <span class="field-control" style="flex:1"><input type="text" placeholder="Masalan, Cambridge dasturi" /></span>
                        <span class="field-control" style="flex:1.4"><input type="text" placeholder="Xalqaro standart va sertifikat" /></span>
                        <button type="button" class="phone-del js-program-del" title="O'chirish"><x-maktabgid.icon name="close" :width="16" :height="16" /></button>
                    </div>
                @endforelse
            </div>
            <button type="button" class="form-addlink" id="js-program-add">
                <x-maktabgid.icon name="plus" :width="15" :height="15" /> Yoʻnalish qo'shish
            </button>

            <div class="form-section">Oʻquv jarayonidan lavhalar <em style="font-style:normal;font-size:11.5px;color:var(--ink-3);font-weight:600">har biri — bitta lavha nomi</em></div>
            <div style="display:flex;flex-direction:column;gap:8px" id="js-lesson-rows">
                @forelse ($lessonRows as $l)
                    <div class="js-lesson-row" style="display:flex;gap:10px;align-items:center">
                        <span class="field-control" style="flex:1"><input type="text" value="{{ is_array($l) ? ($l['label'] ?? '') : $l }}" placeholder="Masalan, Matematika darsi" /></span>
                        <button type="button" class="phone-del js-lesson-del" title="O'chirish"><x-maktabgid.icon name="close" :width="16" :height="16" /></button>
                    </div>
                @empty
                    <div class="js-lesson-row" style="display:flex;gap:10px;align-items:center">
                        <span class="field-control" style="flex:1"><input type="text" placeholder="Masalan, Matematika darsi" /></span>
                        <button type="button" class="phone-del js-lesson-del" title="O'chirish"><x-maktabgid.icon name="close" :width="16" :height="16" /></button>
                    </div>
                @endforelse
            </div>
            <button type="button" class="form-addlink" id="js-lesson-add">
                <x-maktabgid.icon name="plus" :width="15" :height="15" /> Lavha qo'shish
            </button>

            <div class="form-section">
                Videolar <em style="font-style:normal;font-size:11.5px;color:var(--ink-3);font-weight:600">real video fayl yuklanadi</em>
                <button type="button" class="form-addlink" style="margin-left:auto" data-modal-open="add-video-modal">
                    <x-maktabgid.icon name="plus" :width="15" :height="15" /> Video qo'shish
                </button>
            </div>
            @forelse ($videoItems as $v)
                <div class="js-video-row" style="display:flex;gap:10px;align-items:center;padding:10px 0;border-bottom:1px solid var(--line-2)">
                    <span class="idash-ach-ico"><x-maktabgid.icon name="play" :width="18" :height="18" /></span>
                    <div style="flex:1;min-width:0">
                        <b style="display:block;font-size:14px">{{ $v->caption ?: 'Video' }}</b>
                        <span style="font-size:12.5px;color:var(--ink-3);font-weight:600">
                            {{ collect([$v->duration, $v->description])->filter()->implode(' · ') }}
                        </span>
                    </div>
                    <button type="button" class="idash-lead-iconbtn danger js-video-delete" data-media-id="{{ $v->id }}" title="O'chirish"><x-maktabgid.icon name="close" :width="14" :height="14" /></button>
                </div>
            @empty
                <p style="font-size:13px;color:var(--ink-3);font-weight:600;margin:4px 0">Hali video qo'shilmagan.</p>
            @endforelse

            <div class="form-section">Qabul bosqichlari <em style="font-style:normal;font-size:11.5px;color:var(--ink-3);font-weight:600">har biri uchun sarlavha va tavsif</em></div>
            <div style="display:flex;flex-direction:column;gap:8px" id="js-step-rows">
                @forelse ($stepRows as $s)
                    <div class="js-step-row" style="display:flex;gap:10px;align-items:center">
                        <span class="field-control" style="flex:1"><input type="text" value="{{ $s['t'] ?? '' }}" placeholder="Masalan, Ariza qoldirish" /></span>
                        <span class="field-control" style="flex:1.4"><input type="text" value="{{ $s['d'] ?? '' }}" placeholder="Onlayn forma orqali ariza yuborasiz" /></span>
                        <button type="button" class="phone-del js-step-del" title="O'chirish"><x-maktabgid.icon name="close" :width="16" :height="16" /></button>
                    </div>
                @empty
                    <div class="js-step-row" style="display:flex;gap:10px;align-items:center">
                        <span class="field-control" style="flex:1"><input type="text" placeholder="Masalan, Ariza qoldirish" /></span>
                        <span class="field-control" style="flex:1.4"><input type="text" placeholder="Onlayn forma orqali ariza yuborasiz" /></span>
                        <button type="button" class="phone-del js-step-del" title="O'chirish"><x-maktabgid.icon name="close" :width="16" :height="16" /></button>
                    </div>
                @endforelse
            </div>
            <button type="button" class="form-addlink" id="js-step-add">
                <x-maktabgid.icon name="plus" :width="15" :height="15" /> Bosqich qo'shish
            </button>

            <div class="form-section">Koʻrsatkichlar <em style="font-style:normal;font-size:12px;font-weight:600;color:var(--ink-3)">(sarlavha ostida chiqadi)</em></div>
            <div class="form-row2">
                <label class="field">
                    <span class="field-label">{{ $statLabels[0] ?? '1-koʻrsatkich' }}</span>
                    <span class="field-control"><input type="text" id="js-f-stat1" value="{{ $i->stat_class_size }}" placeholder="16" /></span>
                </label>
                <label class="field">
                    <span class="field-label">{{ $statLabels[1] ?? '2-koʻrsatkich' }}</span>
                    <span class="field-control"><input type="text" id="js-f-stat2" value="{{ $i->stat_experience_years }}" placeholder="12" /></span>
                </label>
            </div>
            <div class="form-row2">
                <label class="field">
                    <span class="field-label">{{ $statLabels[2] ?? '3-koʻrsatkich' }}</span>
                    <span class="field-control"><input type="text" id="js-f-stat3" value="{{ $i->stat_admission_rate }}" placeholder="98%" /></span>
                </label>
                <label class="field">
                    <span class="field-label">{{ $statLabels[3] ?? '4-koʻrsatkich' }}</span>
                    <span class="field-control"><input type="text" id="js-f-stat4" value="{{ $i->stat_first_grade_seats }}" placeholder="24" /></span>
                </label>
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
                    <span class="pprev-hero" id="js-prev-mono">{{ $i->name }}</span>
                    <span class="media-badge" id="js-prev-badge" @if(! $i->accepting) style="display:none" @endif>Qabul ochiq</span>
                </div>
                <div class="scard-body">
                    <h3 class="scard-name" id="js-prev-name">{{ $i->name }}</h3>
                    <div class="scard-meta">
                        <span class="m"><x-maktabgid.icon name="pin" :width="15" :height="15" /> <span id="js-prev-district">{{ $i->district?->name ?? 'Tuman' }}</span></span>
                        <span class="m"><x-maktabgid.icon name="clock" :width="15" :height="15" /> <span id="js-prev-hours">{{ $i->work_hours ?: '08:00 – 18:00' }}</span></span>
                    </div>
                    <div class="scard-tags">
                        <span class="tag" id="js-prev-kind">{{ $kindLabels[$i->type] ?? 'Maktab' }}</span>
                        <span class="tag lang" id="js-prev-days">{{ collect(['Dush', 'Ses', 'Cho', 'Pay', 'Jum'])->when($i->works_saturday, fn ($c) => $c->push('Shan'))->implode(', ') }}</span>
                    </div>
                    <div class="scard-foot">
                        <div class="price" id="js-prev-price">
                            @if ($i->monthly_price)
                                <b>{{ number_format($i->monthly_price, 0, ',', ' ') }}</b> <span>so'mdan / oy</span>
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

    {{-- ===== "Video qo'shish" modali — real POST /ajax/institution/me/media (type=video),
         haqiqiy video fayl (mp4/webm/mov, maks 100MB) yoki tashqi havola (YouTube/Vimeo) bilan. ===== --}}
    <x-maktabgid.modal-shell id="add-video-modal" :width="480">
        <div class="js-modal-body">
            <div class="modal-head">
                <h3>Video qo'shish</h3>
            </div>

            <form class="form js-video-form" enctype="multipart/form-data">
                <input type="hidden" name="type" value="video" />
                <div class="js-form-error" style="display:none;padding:10px 14px;background:#fdecec;color:#d4504e;border-radius:var(--r-md);font-size:13px;font-weight:700"></div>

                <x-maktabgid.field label="Sarlavha" icon="play">
                    <input type="text" name="caption" required placeholder="Masalan, Maktab bilan tanishuv" />
                </x-maktabgid.field>
                <div class="form-row2">
                    <x-maktabgid.field label="Davomiyligi" hint="ixtiyoriy" icon="clock">
                        <input type="text" name="duration" placeholder="2:14" />
                    </x-maktabgid.field>
                    <x-maktabgid.field label="Izoh" hint="ixtiyoriy" icon="book">
                        <input type="text" name="description" placeholder="360° sayohat" />
                    </x-maktabgid.field>
                </div>

                <label class="upload-slot" style="flex-direction:row;justify-content:center;padding:16px">
                    <input type="file" name="file" accept="video/*" hidden />
                    <x-maktabgid.icon name="upload" :width="18" :height="18" />
                    <span>Video fayl yuklash (mp4/webm/mov, maks 100MB)</span>
                </label>

                <p class="form-note" style="text-align:center">yoki</p>
                <x-maktabgid.field label="Video havolasi" hint="ixtiyoriy, fayl o'rniga" icon="send">
                    <input type="url" name="url" placeholder="https://youtube.com/..." />
                </x-maktabgid.field>

                <div style="display:flex;gap:10px;margin-top:4px">
                    <button class="btn btn-primary form-submit" type="submit" style="flex:1;justify-content:center">Saqlash</button>
                    <button class="btn btn-ghost js-modal-close" type="button">Bekor qilish</button>
                </div>
            </form>
        </div>
    </x-maktabgid.modal-shell>

    @endif
</x-institution.shell>

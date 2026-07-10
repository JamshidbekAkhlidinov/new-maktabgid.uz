@php
    $i = $institution;
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

            <div class="form-section">Ustozlar <em style="font-style:normal;font-size:11.5px;color:var(--ink-3);font-weight:600">har bir qator: Ism Familiya | Yoʻnalish | Tajriba</em></div>
            <label class="field">
                <span class="field-control">
                    <textarea id="js-f-teachers" rows="3" style="width:100%;resize:vertical" placeholder="Aziz Karimov | Matematika | 10 yil">{{ $teachersText }}</textarea>
                </span>
            </label>

            <div class="form-section">Yoʻnalishlar va dastur <em style="font-style:normal;font-size:11.5px;color:var(--ink-3);font-weight:600">har bir qator: Sarlavha | Tavsif</em></div>
            <label class="field">
                <span class="field-control">
                    <textarea id="js-f-programs" rows="3" style="width:100%;resize:vertical" placeholder="Cambridge dasturi | Xalqaro standart va sertifikat">{{ $programsText }}</textarea>
                </span>
            </label>

            <div class="form-section">Oʻquv jarayonidan lavhalar <em style="font-style:normal;font-size:11.5px;color:var(--ink-3);font-weight:600">har bir qator — bitta lavha nomi</em></div>
            <label class="field">
                <span class="field-control">
                    <textarea id="js-f-lessons" rows="3" style="width:100%;resize:vertical" placeholder="Matematika darsi">{{ $lessonsText }}</textarea>
                </span>
            </label>

            <div class="form-section">Videolar <em style="font-style:normal;font-size:11.5px;color:var(--ink-3);font-weight:600">har bir qator: Sarlavha | Davomiyligi | Izoh</em></div>
            <label class="field">
                <span class="field-control">
                    <textarea id="js-f-videos" rows="3" style="width:100%;resize:vertical" placeholder="Maktab bilan tanishuv | 2:14 | 360° sayohat">{{ $videosText }}</textarea>
                </span>
            </label>

            <div class="form-section">Qabul bosqichlari <em style="font-style:normal;font-size:11.5px;color:var(--ink-3);font-weight:600">har bir qator: Sarlavha | Tavsif</em></div>
            <label class="field">
                <span class="field-control">
                    <textarea id="js-f-steps" rows="3" style="width:100%;resize:vertical" placeholder="Ariza qoldirish | Onlayn forma orqali ariza yuborasiz">{{ $stepsText }}</textarea>
                </span>
            </label>

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
                    <span class="scard-mono" id="js-prev-mono">{{ \App\Support\MaktabgidData::monogram($i->name) }}</span>
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
                        <span class="tag" id="js-prev-kind">{{ ['maktab' => "Xususiy maktab", 'bogcha' => "Xususiy bog'cha", 'markaz' => "O'quv markazi"][$i->type] ?? 'Maktab' }}</span>
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

    @endif
</x-institution.shell>

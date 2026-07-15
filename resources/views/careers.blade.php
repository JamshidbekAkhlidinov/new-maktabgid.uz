<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Vakansiyalar — {{ config('app.name', 'MaktabGID') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,600;12..96,700;12..96,800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/maktabgid.css') }}" />
</head>
<body>

    @php
        use App\Support\MaktabgidData;

        $moreVacancies = [
            ['id' => 101, 'title' => 'Matematika oʻqituvchisi',  'org' => 'Diplomat International', 'type' => 'Toʻliq stavka', 'salary' => '9 – 14 mln', 'until' => '20 Iyun 2026'],
            ['id' => 102, 'title' => 'Bogʻcha tarbiyachisi',      'org' => 'Maple Bear',             'type' => 'Toʻliq stavka', 'salary' => '5 – 8 mln',  'until' => '25 Iyun 2026'],
            ['id' => 103, 'title' => 'IT / Robototexnika ustozi', 'org' => 'IT Park School',         'type' => 'Yarim stavka',  'salary' => '8 – 12 mln', 'until' => '30 Iyun 2026'],
            ['id' => 104, 'title' => 'IELTS instruktori',         'org' => 'Bright Kids',            'type' => 'Toʻliq stavka', 'salary' => '10 – 16 mln','until' => '18 Iyun 2026'],
        ];

        $specs = MaktabgidData::specializations();
    @endphp

    <x-maktabgid.nav />

    {{-- ===== PAGE HEAD with action buttons ===== --}}
    <x-maktabgid.page-head
        icon="bag"
        kicker="Vakansiya va rezyume"
        title="Taʼlim sohasidagi ish va nomzodlar"
        sub="Oʻqituvchilar ish topadi, muassasalar eng yaxshi mutaxassislarni jalb qiladi."
    >
        <div class="phead-actions">
            <button class="btn btn-white" type="button" id="js-post-resume-btn">
                <x-maktabgid.icon name="user" :width="17" :height="17" /> Rezyume joylash
            </button>
            <button class="btn btn-outline-w" type="button" id="js-post-vac-btn">
                <x-maktabgid.icon name="plus" :width="17" :height="17" /> Vakansiya eʼlon qilish
            </button>
        </div>
    </x-maktabgid.page-head>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="wrap section">
        <x-maktabgid.segmented
            :tabs="[
                ['key' => 'vac', 'label' => 'Vakansiyalar (' . (count($vacancies) + count($moreVacancies)) . ')', 'href' => route('careers.index', ['tab' => 'vac'])],
                ['key' => 'res', 'label' => 'Rezyumelar (' . count($resumes) . ')', 'href' => route('careers.index', ['tab' => 'res'])],
            ]"
            :active="$tab"
        />

        @if ($tab === 'res')
            {{-- ===== RESUMES TAB ===== --}}
            <div class="res-grid" style="margin-top:24px">
                @foreach ($resumes as $r)
                    <article class="res-card">
                        <div class="res-top">
                            <x-maktabgid.avatar :name="$r['name']" :size="48" />
                            <div><b>{{ $r['name'] }}</b><span>{{ $r['role'] }}</span></div>
                        </div>
                        <div class="res-meta">
                            <span><x-maktabgid.icon name="award" :width="15" :height="15" /> {{ $r['exp'] }}</span>
                            <span><x-maktabgid.icon name="pin"   :width="15" :height="15" /> {{ $r['district'] }}</span>
                            <span><x-maktabgid.icon name="globe" :width="15" :height="15" /> {{ $r['langs'] }}</span>
                        </div>
                        <div class="res-foot">
                            <div class="vac-salary">{{ $r['salary'] }} <span>UZS</span></div>
                            <button class="btn btn-ghost sm" type="button">
                                <x-maktabgid.icon name="phone" :width="14" :height="14" /> Bogʻlanish
                            </button>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            {{-- ===== VACANCIES TAB ===== --}}
            <div class="vac-grid" style="margin-top:24px">
                @foreach ($vacancies as $v)
                    <a href="{{ route('careers.show', $v['id']) }}" class="vac-card">
                        <span class="vac-type">{{ $v['type'] }}</span>
                        <h3>{{ $v['title'] }}</h3>
                        <div class="vac-org"><span class="av">{{ mb_substr($v['org'], 0, 1) }}</span> {{ $v['org'] }}</div>
                        <div class="vac-foot">
                            <div class="vac-salary">{{ $v['salary'] }} <span>UZS</span></div>
                            <span class="vac-until"><x-maktabgid.icon name="cal" :width="14" :height="14" /> {{ $v['until'] }}</span>
                        </div>
                    </a>
                @endforeach
                @foreach ($moreVacancies as $v)
                    <article class="vac-card">
                        <span class="vac-type">{{ $v['type'] }}</span>
                        <h3>{{ $v['title'] }}</h3>
                        <div class="vac-org"><span class="av">{{ mb_substr($v['org'], 0, 1) }}</span> {{ $v['org'] }}</div>
                        <div class="vac-foot">
                            <div class="vac-salary">{{ $v['salary'] }} <span>UZS</span></div>
                            <span class="vac-until"><x-maktabgid.icon name="cal" :width="14" :height="14" /> {{ $v['until'] }}</span>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>

    <x-maktabgid.trust-strip />
    <x-maktabgid.cta-band />
    <x-maktabgid.footer />

    {{-- ===== MODAL: REZYUME JOYLASH ===== --}}
    <div class="modal-scrim js-modal" id="post-resume-modal" hidden>
        <div class="modal-card" style="max-width:520px;width:100%">
            <button class="modal-x js-modal-close" type="button" aria-label="Yopish">
                <x-maktabgid.icon name="close" :width="20" :height="20" />
            </button>

            {{-- Header --}}
            <div class="modal-head js-fake-form-head">
                <h3>Rezyume joylash</h3>
                <p>Ish beruvchilar sizni topadi</p>
            </div>

            {{-- Form: real POST /ajax/resumes (auth) --}}
            <form class="form js-resume-form" novalidate>
                <label class="field">
                    <span class="field-label"><x-maktabgid.icon name="user" :width="14" :height="14" /> Ism Familiya</span>
                    <span class="field-control"><input name="full_name" required placeholder="F.I.Sh." /></span>
                </label>
                <label class="field">
                    <span class="field-label"><x-maktabgid.icon name="bag" :width="14" :height="14" /> Lavozim</span>
                    <span class="field-control"><input name="role_title" required placeholder="Masalan, Ingliz tili oʻqituvchisi" /></span>
                </label>
                <div class="form-row2">
                    <label class="field">
                        <span class="field-label">Tajriba</span>
                        <span class="field-control"><input name="experience" required placeholder="5 yil" /></span>
                    </label>
                    <label class="field">
                        <span class="field-label">Maosh kutilmasi</span>
                        <span class="field-control"><input name="salary_expectation" placeholder="8 – 12 mln" /></span>
                    </label>
                </div>
                <label class="field">
                    <span class="field-label">Yoʻnalish</span>
                    <span class="field-control">
                        <select name="specialization_key">
                            @foreach ($specs as $s)
                                <option value="{{ $s['key'] }}">{{ $s['label'] }}</option>
                            @endforeach
                        </select>
                    </span>
                </label>
                <button class="btn btn-primary form-submit" type="submit">
                    <x-maktabgid.icon name="send" :width="16" :height="16" /> Rezyumeni joylash
                </button>
            </form>

            {{-- Success --}}
            <div class="js-fake-success success-note" style="display:none">
                <div class="success-ico">
                    <x-maktabgid.icon name="check" :width="28" :height="28" />
                </div>
                <h4>Rezyumeingiz joylandi!</h4>
                <p>Eʼloningiz moderatsiyadan soʻng bazada koʻrinadi. Ish beruvchilar siz bilan bogʻlanishi mumkin.</p>
                <button class="btn btn-primary js-modal-close" type="button">Yopish</button>
            </div>
        </div>
    </div>

    {{-- ===== MODAL: VAKANSIYA E'LON QILISH ===== --}}
    <div class="modal-scrim js-modal" id="post-vac-modal" hidden>
        <div class="modal-card" style="max-width:520px;width:100%">
            <button class="modal-x js-modal-close" type="button" aria-label="Yopish">
                <x-maktabgid.icon name="close" :width="20" :height="20" />
            </button>

            {{-- Header --}}
            <div class="modal-head js-fake-form-head">
                <h3>Vakansiya eʼlon qilish</h3>
                <p>Eng yaxshi nomzodlarni jalb qiling</p>
            </div>

            {{-- Form: real POST /ajax/vacancies (auth) --}}
            <form class="form js-vacancy-form" novalidate>
                <label class="field">
                    <span class="field-label"><x-maktabgid.icon name="bag" :width="14" :height="14" /> Lavozim</span>
                    <span class="field-control"><input name="title" required placeholder="Masalan, Matematika oʻqituvchisi" /></span>
                </label>
                <label class="field">
                    <span class="field-label"><x-maktabgid.icon name="building" :width="14" :height="14" /> Muassasa</span>
                    <span class="field-control"><input name="org" required placeholder="Muassasa nomi" /></span>
                </label>
                <div class="form-row2">
                    <label class="field">
                        <span class="field-label">Maosh</span>
                        <span class="field-control"><input name="salary_range" placeholder="9 – 14 mln" /></span>
                    </label>
                    <label class="field">
                        <span class="field-label">Bandlik</span>
                        <span class="field-control">
                            <select name="employment_type">
                                <option value="full">Toʻliq stavka</option>
                                <option value="part">Yarim stavka</option>
                                <option value="hourly">Soatbay</option>
                            </select>
                        </span>
                    </label>
                </div>
                <button class="btn btn-primary form-submit" type="submit">
                    <x-maktabgid.icon name="send" :width="16" :height="16" /> Eʼlon qilish
                </button>
            </form>

            {{-- Success --}}
            <div class="js-fake-success success-note" style="display:none">
                <div class="success-ico">
                    <x-maktabgid.icon name="check" :width="28" :height="28" />
                </div>
                <h4>Vakansiya eʼlon qilindi!</h4>
                <p>Eʼloningiz tez orada nomzodlarga koʻrinadi.</p>
                <button class="btn btn-primary js-modal-close" type="button">Yopish</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/maktabgid.js') }}"></script>

    <script>
    (function () {
        // Diqqat: eski "mg_user" (localStorage) tekshiruvi hech qachon o'rnatilmagan edi
        // (real auth session-based) — shu sababli tugma doim "kirish kerak" holatiga
        // tushib qolardi. Endi haqiqiy server sessiyasidan (auth()->check()) o'qiladi.
        var isAuthed = @json(auth()->check());

        function openPostModal(id) {
            var modal = document.getElementById(id);
            if (!modal) return;
            // reset state so form shows (not success)
            var form    = modal.querySelector("form");
            var head    = modal.querySelector(".js-fake-form-head");
            var success = modal.querySelector(".js-fake-success");
            if (form)    { form.style.display = "block"; if (form.reset) form.reset(); }
            if (head)    { head.style.display = "block"; }
            if (success) { success.style.display = "none"; }
            modal.hidden = false;
            document.body.classList.add("modal-open");
        }

        function openAuth() {
            var btn = document.getElementById("js-kirish-btn");
            if (btn) btn.click();
        }

        var resumeBtn = document.getElementById("js-post-resume-btn");
        var vacBtn    = document.getElementById("js-post-vac-btn");

        if (resumeBtn) {
            resumeBtn.addEventListener("click", function () {
                if (!isAuthed) { openAuth(); return; }
                openPostModal("post-resume-modal");
            });
        }

        if (vacBtn) {
            vacBtn.addEventListener("click", function () {
                if (!isAuthed) { openAuth(); return; }
                openPostModal("post-vac-modal");
            });
        }
    }());
    </script>

</body>
</html>

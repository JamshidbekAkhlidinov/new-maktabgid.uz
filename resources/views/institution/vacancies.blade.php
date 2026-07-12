@php
    // Mock: kabinet ichidagi vakansiya boshqaruvi (nomzodlar, holat) — real Vacancy modeli
    // mavjud (careers sahifasida ishlatiladi), lekin bu boshqaruv paneli hali ulanmagan,
    // shuning uchun namunaviy ro'yxat bilan ko'rsatiladi.
    $mockVacancies = [
        ['title' => 'Ingliz tili o\'qituvchisi', 'type' => 'To\'liq stavka', 'status' => 'active', 'stLabel' => 'Faol', 'applicants' => 12, 'until' => '30-avgustgacha', 'salary' => '6 000 000', 'requirements' => 'IELTS 7.0+, kamida 2 yil tajriba, boshlang\'ich/o\'rta sinflar bilan ishlay olish', 'candidates' => [
            ['name' => 'Kamola Yusupova', 'exp' => '6 yil tajriba', 'note' => 'IELTS 8.0', 'ago' => 'Bugun 09:15'],
            ['name' => 'Otabek Rahimov', 'exp' => '4 yil tajriba', 'note' => 'CELTA sertifikati', 'ago' => 'Kecha 18:02'],
            ['name' => 'Dilnoza Saidova', 'exp' => '3 yil tajriba', 'note' => 'IELTS 7.5', 'ago' => '2 kun oldin'],
        ]],
        ['title' => 'Boshlang\'ich sinf o\'qituvchisi', 'type' => 'To\'liq stavka', 'status' => 'active', 'stLabel' => 'Faol', 'applicants' => 8, 'until' => '15-avgustgacha', 'salary' => '5 500 000', 'requirements' => 'Pedagogika yo\'nalishi bo\'yicha diplom, bolalar bilan ishlash tajribasi', 'candidates' => [
            ['name' => 'Aziz Karimov', 'exp' => '8 yil tajriba', 'note' => 'IELTS 8.0', 'ago' => 'Bugun 10:20'],
            ['name' => 'Madina Tosheva', 'exp' => '5 yil tajriba', 'note' => 'TDPU', 'ago' => 'Kecha 15:40'],
            ['name' => 'Rustam Qodirov', 'exp' => '12 yil tajriba', 'note' => 'Olimpiada murabbiyi', 'ago' => '2 kun oldin'],
        ]],
        ['title' => 'IT / dasturlash to\'garak rahbari', 'type' => 'Yarim stavka', 'status' => 'review', 'stLabel' => 'Ko\'rib chiqilmoqda', 'applicants' => 5, 'until' => '10-sentabrgacha', 'salary' => '4 000 000', 'requirements' => 'Python/Scratch asoslari, o\'quvchilarga tushuntira olish qobiliyati', 'candidates' => [
            ['name' => 'Jasur Nazarov', 'exp' => '3 yil tajriba', 'note' => 'Python/Scratch mentor', 'ago' => 'Bugun 08:45'],
            ['name' => 'Sardor Yo\'ldoshev', 'exp' => '5 yil tajriba', 'note' => 'Hackathon g\'olibi', 'ago' => '3 kun oldin'],
        ]],
        ['title' => 'Psixolog', 'type' => 'To\'liq stavka', 'status' => 'closed', 'stLabel' => 'Yopilgan', 'applicants' => 3, 'until' => 'Yakunlangan', 'salary' => '4 500 000', 'requirements' => 'Amaliy psixologiya yo\'nalishi, maktab tajribasi afzallik', 'candidates' => [
            ['name' => 'Feruza Alimova', 'exp' => '7 yil tajriba', 'note' => 'Amaliy psixologiya', 'ago' => '1 hafta oldin'],
            ['name' => 'Nodira Ergasheva', 'exp' => '4 yil tajriba', 'note' => 'Maktab tajribasi bor', 'ago' => '1 hafta oldin'],
            ['name' => 'Shahzod Tursunov', 'exp' => '2 yil tajriba', 'note' => 'Yosh mutaxassis', 'ago' => '2 hafta oldin'],
        ]],
    ];
    $statusStyle = [
        'active' => ['bg' => 'var(--primary-soft)', 'color' => 'var(--primary-ink)'],
        'review' => ['bg' => 'var(--accent-soft)', 'color' => '#b45309'],
        'closed' => ['bg' => 'var(--surface-2)', 'color' => 'var(--ink-3)'],
    ];
    $totalApplicants = collect($mockVacancies)->sum('applicants');
@endphp

<x-institution.shell
    active="vacancies"
    title="Vakansiyalar"
    sub="Muassasangiz uchun xodim qidiring"
    :institution="$institution"
    :organizations="$organizations"
    :counts="$counts"
    :user="$user"
>
    @if ($institution)

    <div class="idash-toolbar">
        <span class="idash-chart-meta">{{ count($mockVacancies) }} ta e'lon · {{ $totalApplicants }} nomzod · e'lon narxi 100 000 so'm</span>
        <button type="button" class="btn btn-primary sm" data-modal-open="add-vacancy-modal">
            <x-maktabgid.icon name="plus" :width="15" :height="15" /> Vakansiya ochish
        </button>
    </div>

    <div class="idash-vac-grid">
        @foreach ($mockVacancies as $v)
            <div class="idash-vac-card">
                <div class="idash-vac-top">
                    <span class="idash-pill-neutral" style="background:var(--primary-soft);color:var(--primary-ink)">{{ $v['type'] }}</span>
                    <span class="idash-status-pill" style="background:{{ $statusStyle[$v['status']]['bg'] }};color:{{ $statusStyle[$v['status']]['color'] }}">{{ $v['stLabel'] }}</span>
                </div>
                <h3>{{ $v['title'] }}</h3>
                <div class="idash-vac-meta">
                    <span><x-maktabgid.icon name="users" :width="15" :height="15" /> {{ $v['applicants'] }} nomzod</span>
                    <span><x-maktabgid.icon name="cal" :width="15" :height="15" /> {{ $v['until'] }}</span>
                </div>
                <div class="idash-vac-foot">
                    <span class="idash-vac-price">{{ $v['salary'] }} <span>so'm</span></span>
                    <div class="idash-vac-actions">
                        <button type="button" class="idash-lead-iconbtn" title="Tahrirlash" data-modal-open="edit-vacancy-{{ $loop->index }}"><x-maktabgid.icon name="edit" :width="14" :height="14" /></button>
                        <button type="button" class="idash-lead-iconbtn danger" title="O'chirish"><x-maktabgid.icon name="close" :width="14" :height="14" /></button>
                        <button type="button" class="idash-vac-cand" data-modal-open="candidates-vacancy-{{ $loop->index }}">Nomzodlar ({{ $v['applicants'] }})</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="idash-badge-soft">
        <x-maktabgid.icon name="sparkle" :width="14" :height="14" /> Bu bo'lim demo ma'lumot bilan ko'rsatilmoqda — nomzodlar boshqaruvi tez orada ulanadi
    </div>

    {{-- ===== "Vakansiya ochish" modali — real Vacancy modeliga hali ulanmagan (yuqoridagi
         $mockVacancies'ga qarang), shuning uchun umumiy "fake form" andozasi orqali ishlaydi
         — xuddi rezyume joylashdagi to'lov eslatmasi kabi (vakansiya joylash ham pullik). ===== --}}
    <x-maktabgid.modal-shell id="add-vacancy-modal" :width="480">
        <div class="js-modal-body">
            <div class="modal-head js-fake-form-head">
                <h3>Vakansiya ochish</h3>
            </div>

            <form class="form js-fake-form">
                <x-maktabgid.field label="Lavozim" icon="bag">
                    <input type="text" required placeholder="Ingliz tili o'qituvchisi" />
                </x-maktabgid.field>
                <div class="form-row2">
                    <x-maktabgid.field label="Stavka" icon="sliders">
                        <select required>
                            <option>To'liq stavka</option>
                            <option>Yarim stavka</option>
                        </select>
                    </x-maktabgid.field>
                    <x-maktabgid.field label="Maosh diapazoni (mln)" icon="card">
                        <input type="text" required placeholder="8-12 mln" />
                    </x-maktabgid.field>
                </div>
                <x-maktabgid.field label="Talablar" icon="edit">
                    <textarea rows="3" placeholder="Talablarni yozing…"></textarea>
                </x-maktabgid.field>
                <x-maktabgid.field label="Murojaat muddati" icon="cal">
                    <input type="text" required placeholder="30-iyul" />
                </x-maktabgid.field>

                <div style="display:flex;align-items:center;gap:9px;padding:12px 14px;background:var(--accent-soft);border-radius:var(--r-md);font-size:12.5px;font-weight:700;color:#b45309">
                    <x-maktabgid.icon name="card" :width="16" :height="16" />
                    Vakansiya joylash pullik — 100 000 so'm. To'lovdan keyin e'lon aktivlashadi.
                </div>

                <div style="display:flex;gap:10px;margin-top:4px">
                    <button class="btn btn-primary form-submit" type="submit" style="flex:1;justify-content:center">E'lon qilish (100 000 so'm)</button>
                    <button class="btn btn-ghost js-modal-close" type="button">Bekor qilish</button>
                </div>
            </form>

            <x-maktabgid.success-note title="Vakansiya joylandi!" :close-target="true" class="js-fake-success" style="display:none">
                To'lovdan so'ng e'lon darhol faollashadi va nomzodlar ariza yubora boshlaydi.
            </x-maktabgid.success-note>
        </div>
    </x-maktabgid.modal-shell>

    {{-- ===== "Vakansiyani tahrirlash" modali — har bir vakansiya uchun alohida ===== --}}
    @foreach ($mockVacancies as $v)
        <x-maktabgid.modal-shell id="edit-vacancy-{{ $loop->index }}" :width="480">
            <div class="js-modal-body">
                <div class="modal-head js-fake-form-head">
                    <h3>Vakansiyani tahrirlash</h3>
                </div>

                <form class="form js-fake-form">
                    <x-maktabgid.field label="Lavozim" icon="bag">
                        <input type="text" value="{{ $v['title'] }}" required />
                    </x-maktabgid.field>
                    <div class="form-row2">
                        <x-maktabgid.field label="Stavka" icon="sliders">
                            <select required>
                                <option @selected($v['type'] === "To'liq stavka")>To'liq stavka</option>
                                <option @selected($v['type'] === 'Yarim stavka')>Yarim stavka</option>
                            </select>
                        </x-maktabgid.field>
                        <x-maktabgid.field label="Maosh diapazoni (mln)" icon="card">
                            <input type="text" value="{{ $v['salary'] }}" required />
                        </x-maktabgid.field>
                    </div>
                    <x-maktabgid.field label="Talablar" icon="edit">
                        <textarea rows="3">{{ $v['requirements'] }}</textarea>
                    </x-maktabgid.field>
                    <x-maktabgid.field label="Murojaat muddati" icon="cal">
                        <input type="text" value="{{ $v['until'] }}" required />
                    </x-maktabgid.field>

                    <div style="display:flex;gap:10px;margin-top:4px">
                        <button class="btn btn-primary form-submit" type="submit" style="flex:1;justify-content:center">Saqlash</button>
                        <button class="btn btn-ghost js-modal-close" type="button">Bekor qilish</button>
                    </div>
                </form>

                <x-maktabgid.success-note title="Ma'lumotlar yangilandi!" :close-target="true" class="js-fake-success" style="display:none">
                    O'zgarishlar e'lon sahifasida ham aks etadi.
                </x-maktabgid.success-note>
            </div>
        </x-maktabgid.modal-shell>
    @endforeach

    {{-- ===== "Nomzodlar" ko'rish modali — har bir vakansiya uchun alohida (statik namoyish,
         real ariza/rezyume bog'lanishi hali yo'q, shuning uchun qabul/rad tugmalari hozircha
         faqat ko'rinish uchun). ===== --}}
    @foreach ($mockVacancies as $v)
        <x-maktabgid.modal-shell id="candidates-vacancy-{{ $loop->index }}" :width="560">
            <div class="js-modal-body">
                <div class="modal-head">
                    <h3>Nomzodlar</h3>
                    <p>{{ $v['title'] }} — {{ $v['applicants'] }} nomzod</p>
                </div>

                <div class="idash-cand-list">
                    @foreach ($v['candidates'] as $c)
                        <div class="idash-cand-row">
                            <x-maktabgid.avatar :name="$c['name']" :size="48" />
                            <div class="idash-cand-main">
                                <b>{{ $c['name'] }}</b>
                                <span>{{ $c['exp'] }} · {{ $c['note'] }} · {{ $c['ago'] }}</span>
                            </div>
                            <div class="idash-cand-actions">
                                <button type="button" class="idash-cand-resume">Rezyume</button>
                                <button type="button" class="idash-cand-btn accept" title="Qabul qilish"><x-maktabgid.icon name="check" :width="16" :height="16" /></button>
                                <button type="button" class="idash-cand-btn reject" title="Rad etish"><x-maktabgid.icon name="close" :width="16" :height="16" /></button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </x-maktabgid.modal-shell>
    @endforeach

    @endif
</x-institution.shell>

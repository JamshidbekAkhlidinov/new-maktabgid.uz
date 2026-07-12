@php
    // Mock: careers.index (public /vakansiyalar) real Vacancy modeliga ulangan, lekin bu yerda
    // "menga mos" filtrlangan ro'yxat va bir bosishda "Ariza yuborish" ustoz kabinetiga hali
    // ulanmagan — shuning uchun namunaviy ro'yxat bilan ko'rsatiladi.
    $teacherVac = [
        ['title' => 'Ingliz tili o\'qituvchisi', 'org' => 'Bilim Ziyo maktabi', 'district' => 'Yunusobod', 'salary' => '6 000 000-8 000 000', 'type' => 'To\'liq stavka', 'ago' => '2 soat oldin'],
        ['title' => 'IELTS mentor', 'org' => 'Cambridge School', 'district' => 'Mirobod', 'salary' => '7 500 000', 'type' => 'Yarim stavka', 'ago' => 'Kecha'],
        ['title' => 'Ingliz tili to\'garak rahbari', 'org' => 'IT Park School', 'district' => 'Mirzo Ulug\'bek', 'salary' => '5 000 000', 'type' => 'To\'liq stavka', 'ago' => '3 kun oldin'],
    ];

    // Mock: "Ariza yuborish" modalidagi rezyume tanlash — teacher/resumes.blade.php dagi
    // namunaviy ro'yxat bilan bir xil (real bog'lanish keyingi bosqichda).
    $myResumes = [
        'Ingliz tili o\'qituvchisi (faol)',
        'IELTS mentor — qo\'shimcha (muddati tugagan)',
    ];
@endphp

<x-teacher.shell active="vacancies" title="Vakansiyalar" sub="Mos ish o'rinlarini toping" :teacher="$teacher" :counts="$counts">

    <div style="display:flex;gap:12px;flex-wrap:wrap;align-items:center">
        <label class="idash-lead-search" style="flex:1;min-width:240px">
            <x-maktabgid.icon name="search" :width="16" :height="16" />
            <input type="text" placeholder="Lavozim, muassasa yoki tuman bo'yicha qidiring…" />
        </label>
        <div style="display:inline-flex;align-items:center;gap:8px;padding:11px 16px;border-radius:var(--r-pill);border:1px solid var(--line);background:var(--surface);color:var(--ink-3);font-weight:700;font-size:13.5px">
            <x-maktabgid.icon name="sliders" :width="16" :height="16" />
            <select style="border:none;outline:none;background:none;font:inherit;font-weight:700;color:var(--ink);appearance:none">
                <option>Barcha tumanlar</option>
                <option>Yunusobod</option>
                <option>Mirobod</option>
                <option>Sergeli</option>
                <option>Mirzo Ulug'bek</option>
            </select>
        </div>
    </div>

    <div style="display:flex;flex-direction:column;gap:14px;margin-top:14px">
        @foreach ($teacherVac as $v)
            <div class="idash-vjob-row">
                <span class="idash-vjob-ico">{{ \App\Support\MaktabgidData::monogram($v['org']) }}</span>
                <div>
                    <div class="idash-vjob-head">
                        <b>{{ $v['title'] }}</b>
                        <span class="idash-pill-neutral" style="background:var(--primary-soft);color:var(--primary-ink)">{{ $v['type'] }}</span>
                    </div>
                    <div class="idash-vjob-meta">
                        <span>{{ $v['org'] }}</span>
                        <span><x-maktabgid.icon name="pin" :width="13" :height="13" /> {{ $v['district'] }}</span>
                        <span style="color:var(--primary-ink);font-weight:700">{{ $v['salary'] }} so'm</span>
                    </div>
                </div>
                <div class="idash-vjob-side">
                    <span class="idash-vjob-ago">{{ $v['ago'] }}</span>
                    <button type="button" class="btn btn-primary sm" data-modal-open="apply-vacancy-{{ $loop->index }}">Ariza yuborish</button>
                </div>
            </div>
        @endforeach
    </div>

    <div class="idash-badge-soft">
        <x-maktabgid.icon name="sparkle" :width="14" :height="14" /> To'liq ro'yxat uchun <a href="{{ route('careers.index') }}">Vakansiyalar</a> sahifasiga o'ting — bu yer ustoz kabinetidagi shaxsiylashtirilgan ko'rinish, tez orada real filtrlar bilan ishga tushadi
    </div>

    {{-- ===== "Vakansiyaga ariza" modali — har bir vakansiya uchun alohida (statik namoyish,
         real ariza/rezyume bog'lanishi hali yo'q, shuning uchun umumiy "fake form" andozasi
         orqali ishlaydi — xuddi saytdagi boshqa hali-backend'siz formalar kabi). ===== --}}
    @foreach ($teacherVac as $v)
        <x-maktabgid.modal-shell id="apply-vacancy-{{ $loop->index }}" :width="480">
            <div class="js-modal-body">
                <div class="modal-head js-fake-form-head">
                    <h3>Vakansiyaga ariza</h3>
                </div>

                <div style="display:flex;align-items:center;gap:14px;background:var(--primary-soft);border-radius:var(--r-md);padding:14px 16px;margin-bottom:18px" class="js-fake-form-head">
                    <span class="idash-vjob-ico" style="width:44px;height:44px;font-size:14px">{{ \App\Support\MaktabgidData::monogram($v['org']) }}</span>
                    <div>
                        <b style="display:block;font-family:var(--font-display);font-size:15.5px">{{ $v['title'] }}</b>
                        <span style="font-size:13px;color:var(--ink-2);font-weight:600">{{ $v['org'] }} · {{ $v['salary'] }} so'm</span>
                    </div>
                </div>

                <form class="form js-fake-form">
                    <div class="form-row2">
                        <x-maktabgid.field label="Ism-familiya" icon="user">
                            <input type="text" required placeholder="{{ $teacher['name'] ?? 'Ism Familiya' }}" />
                        </x-maktabgid.field>
                        <x-maktabgid.field label="Telefon" icon="phone">
                            <input type="tel" required placeholder="+998 90 123 45 67" />
                        </x-maktabgid.field>
                    </div>
                    <x-maktabgid.field label="Rezyume tanlang" icon="book">
                        <select>
                            @foreach ($myResumes as $r)
                                <option>{{ $r }}</option>
                            @endforeach
                        </select>
                    </x-maktabgid.field>
                    <x-maktabgid.field label="Qisqa xat" hint="ixtiyoriy" icon="edit">
                        <textarea rows="3" placeholder="Nega aynan siz? 2-3 jumla yozing…"></textarea>
                    </x-maktabgid.field>
                    <div style="display:flex;gap:10px;margin-top:4px">
                        <button class="btn btn-primary form-submit" type="submit" style="flex:1;justify-content:center">
                            <x-maktabgid.icon name="send" :width="16" :height="16" /> Ariza yuborish
                        </button>
                        <button class="btn btn-ghost js-modal-close" type="button">Bekor qilish</button>
                    </div>
                </form>

                <x-maktabgid.success-note title="Ariza yuborildi!" :close-target="true" class="js-fake-success" style="display:none">
                    {{ $v['org'] }} tez orada siz bilan bog'lanadi.
                </x-maktabgid.success-note>
            </div>
        </x-maktabgid.modal-shell>
    @endforeach

</x-teacher.shell>

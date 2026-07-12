@php
    // Mock: rezyume joylash pullik xizmat sifatida rejalashtirilgan (narxni admin belgilaydi —
    // Admin\ResumeController'ga qarang), lekin ustoz kabineti tomoni hali ulanmagan.
    $resumes = [
        ['title' => 'Ingliz tili o\'qituvchisi', 'spec' => 'Umumiy o\'rta ta\'lim · IELTS/CELTA', 'status' => 'live', 'stLabel' => 'Faol', 'views' => 214, 'applies' => 6, 'until' => '18 kun qoldi · 30-avgustgacha', 'exp' => '8 yil', 'salary' => '10 000 000', 'edu' => 'UzSWLU — 2016-yil', 'skills' => 'IELTS 8.0, CELTA…', 'contact' => $teacher['phone'] ?? ''],
        ['title' => 'IELTS mentor (qo\'shimcha)', 'spec' => 'Individual va guruh darslari', 'status' => 'expired', 'stLabel' => 'Muddati tugagan', 'views' => 89, 'applies' => 2, 'until' => 'Yangilash kerak', 'exp' => '5 yil', 'salary' => '7 000 000', 'edu' => 'ToshDPU — 2019-yil', 'skills' => 'IELTS 7.5', 'contact' => $teacher['phone'] ?? ''],
    ];
    $statusStyle = ['live' => 'live', 'pending' => 'pending', 'expired' => 'expired'];
    $payMethods = [
        ['key' => 'humo', 'label' => 'Humo · 8842', 'dot' => '#2aabee', 'on' => true],
        ['key' => 'payme', 'label' => 'Payme', 'dot' => '#3fc4e8', 'on' => false],
    ];
@endphp

<x-teacher.shell active="resumes" title="Rezyumelarim" sub="Rezyume joylash — pullik, narxni admin belgilaydi" :teacher="$teacher" :counts="$counts">

    <div class="idash-toolbar">
        <span class="idash-chart-meta">{{ count($resumes) }} ta rezyume · joylash narxi 30 000 so'm</span>
        <a href="#new-resume" class="btn btn-primary sm">
            <x-maktabgid.icon name="plus" :width="15" :height="15" /> Yangi rezyume
        </a>
    </div>

    <div style="display:flex;flex-direction:column;gap:14px">
        @foreach ($resumes as $r)
            <div class="idash-resume-card">
                <div>
                    <div class="idash-resume-top">
                        <b>{{ $r['title'] }}</b>
                        <span class="idash-status-pill {{ $statusStyle[$r['status']] }}">{{ $r['stLabel'] }}</span>
                    </div>
                    <span class="idash-resume-spec">{{ $r['spec'] }}</span>
                    <div class="idash-resume-meta">
                        <span><x-maktabgid.icon name="eye" :width="15" :height="15" /> {{ $r['views'] }} ko'rildi</span>
                        <span><x-maktabgid.icon name="mail" :width="15" :height="15" /> {{ $r['applies'] }} taklif</span>
                    </div>
                    <span class="idash-resume-until">{{ $r['until'] }}</span>
                </div>
                <div class="idash-resume-actions">
                    <button type="button" class="btn btn-ghost sm" data-modal-open="edit-resume-{{ $loop->index }}">Tahrirlash</button>
                    <button type="button" class="idash-lead-iconbtn" title="Boshqa"><x-maktabgid.icon name="sliders" :width="15" :height="15" /></button>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ===== "Rezyumeni tahrirlash" modali — har bir rezyume uchun alohida (statik namoyish,
         real saqlash hali yo'q, shuning uchun umumiy "fake form" andozasi orqali ishlaydi). ===== --}}
    @foreach ($resumes as $r)
        <x-maktabgid.modal-shell id="edit-resume-{{ $loop->index }}" :width="480">
            <div class="js-modal-body">
                <div class="modal-head js-fake-form-head">
                    <h3>Rezyumeni tahrirlash</h3>
                </div>

                <form class="form js-fake-form">
                    <x-maktabgid.field label="Lavozim" icon="bag">
                        <input type="text" value="{{ $r['title'] }}" />
                    </x-maktabgid.field>
                    <div class="form-row2">
                        <x-maktabgid.field label="Tajriba" icon="clock">
                            <input type="text" value="{{ $r['exp'] }}" />
                        </x-maktabgid.field>
                        <x-maktabgid.field label="Kutilayotgan maosh (so'm)" icon="card">
                            <input type="text" value="{{ $r['salary'] }}" />
                        </x-maktabgid.field>
                    </div>
                    <x-maktabgid.field label="Ma'lumoti" icon="book">
                        <input type="text" value="{{ $r['edu'] }}" />
                    </x-maktabgid.field>
                    <x-maktabgid.field label="Ko'nikmalar" icon="sparkle">
                        <textarea rows="2">{{ $r['skills'] }}</textarea>
                    </x-maktabgid.field>
                    <x-maktabgid.field label="Bog'lanish" icon="phone">
                        <input type="text" value="{{ $r['contact'] }}" placeholder="+998 90 123 45 67" />
                    </x-maktabgid.field>

                    <div style="display:flex;align-items:center;gap:9px;padding:12px 14px;background:var(--accent-soft);border-radius:var(--r-md);font-size:12.5px;font-weight:700;color:#b45309">
                        <x-maktabgid.icon name="card" :width="16" :height="16" />
                        Rezyume joylash pullik — 30 000 so'm (narxni admin belgilaydi).
                    </div>

                    <div style="display:flex;gap:10px;margin-top:4px">
                        <button class="btn btn-primary form-submit" type="submit" style="flex:1;justify-content:center">Saqlash</button>
                        <button class="btn btn-ghost js-modal-close" type="button">Bekor qilish</button>
                    </div>
                </form>

                <x-maktabgid.success-note title="Rezyume yangilandi!" :close-target="true" class="js-fake-success" style="display:none">
                    O'zgarishlar saqlandi.
                </x-maktabgid.success-note>
            </div>
        </x-maktabgid.modal-shell>
    @endforeach

    <div class="panel" id="new-resume">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap" class="js-fake-form-head">
            <div>
                <h3 style="font-size:19px">Yangi rezyume joylash</h3>
                <p style="font-size:14px;color:var(--ink-3);font-weight:600;margin-top:6px;max-width:56ch">Rezyume joylash pullik. To'lovdan so'ng rezyume darhol e'lon qilinadi va 30 kun davomida faol turadi.</p>
            </div>
            <div style="text-align:right;background:var(--primary-soft);border-radius:var(--r-lg);padding:14px 20px">
                <b style="font-family:var(--font-display);font-size:26px;color:var(--primary-ink)">30 000</b>
                <span style="display:block;font-size:12px;color:var(--primary-ink);font-weight:700">so'm / 30 kun</span>
            </div>
        </div>

        <form class="form js-fake-form" style="margin-top:18px">
            <x-maktabgid.field label="Lavozim" icon="bag">
                <input type="text" placeholder="Masalan, Ingliz tili o'qituvchisi" required />
            </x-maktabgid.field>
            <div class="form-row2">
                <x-maktabgid.field label="Tajriba" icon="clock">
                    <input type="text" placeholder="8 yil" required />
                </x-maktabgid.field>
                <x-maktabgid.field label="Kutilayotgan maosh (so'm)" icon="card">
                    <input type="text" placeholder="10 000 000" required />
                </x-maktabgid.field>
            </div>
            <x-maktabgid.field label="Ma'lumoti" icon="book">
                <input type="text" placeholder="UzSWLU — 2016-yil" required />
            </x-maktabgid.field>
            <x-maktabgid.field label="Ko'nikmalar" icon="sparkle">
                <textarea rows="2" placeholder="IELTS 8.0, CELTA…"></textarea>
            </x-maktabgid.field>
            <x-maktabgid.field label="Bog'lanish" icon="phone">
                <input type="text" value="{{ $teacher['phone'] ?? '' }}" placeholder="+998 90 123 45 67" required />
            </x-maktabgid.field>

            <span style="display:block;font-size:12.5px;font-weight:700;color:var(--ink-2);margin:6px 0 10px">To'lov usuli</span>
            <div class="idash-paymethods">
                @foreach ($payMethods as $pm)
                    <button type="button" class="idash-paymethod{{ $pm['on'] ? ' on' : '' }}">
                        <i style="background:{{ $pm['dot'] }}"></i> {{ $pm['label'] }}
                        @if ($pm['on'])
                            <span class="check"><x-maktabgid.icon name="check" :width="16" :height="16" /></span>
                        @endif
                    </button>
                @endforeach
            </div>

            <div class="idash-pay-cta-row">
                <button class="btn btn-primary form-submit" type="submit">To'lash va joylash <x-maktabgid.icon name="arrowR" :width="16" :height="16" /></button>
                <span class="idash-pay-secure"><x-maktabgid.icon name="lock" :width="15" :height="15" /> 256-bit SSL · Payme &amp; Click</span>
            </div>
        </form>

        <x-maktabgid.success-note title="Rezyume joylandi!" class="js-fake-success" style="display:none">
            To'lov muvaffaqiyatli — rezyumengiz darhol e'lon qilindi va 30 kun davomida faol turadi.
        </x-maktabgid.success-note>
    </div>

    <div class="idash-badge-soft">
        <x-maktabgid.icon name="sparkle" :width="14" :height="14" /> To'lov tizimi tez orada ulanadi — hozircha demo ko'rinish
    </div>

</x-teacher.shell>

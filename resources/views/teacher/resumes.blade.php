@php
    // Real ro'yxat — App\Models\Resume (owner_user_id = joriy ustoz). Yangi rezyume
    // joylash pullik xizmat sifatida rejalashtirilgan (narxni admin belgilaydi —
    // Admin\ResumeController'ga qarang), to'lov tizimi hali ulanmagani uchun
    // "Yangi rezyume" formasi hozircha demo ko'rinishda qoladi (ADR-0002, Faza 1
    // — faqat ro'yxat/o'qish tomoni real qilindi).
    $payMethods = [
        ['key' => 'humo', 'label' => 'Humo · 8842', 'dot' => '#2aabee', 'on' => true],
        ['key' => 'payme', 'label' => 'Payme', 'dot' => '#3fc4e8', 'on' => false],
    ];
@endphp

<x-teacher.shell active="resumes" title="Rezyumelarim" sub="Rezyume joylash — pullik, narxni admin belgilaydi" :teacher="$teacher" :counts="$counts">

    <div class="idash-toolbar">
        <span class="idash-chart-meta">{{ $resumes->count() }} ta rezyume · joylash narxi 30 000 so'm</span>
        <a href="#new-resume" class="btn btn-primary sm">
            <x-maktabgid.icon name="plus" :width="15" :height="15" /> Yangi rezyume
        </a>
    </div>

    @if ($resumes->isEmpty())
        <div class="idash-badge-soft">
            <x-maktabgid.icon name="sparkle" :width="14" :height="14" /> Hali rezyumengiz yo'q — pastdagi "Yangi rezyume joylash" bo'limidan boshlang
        </div>
    @else
        <div style="display:flex;flex-direction:column;gap:14px">
            @foreach ($resumes as $r)
                <div class="idash-resume-card">
                    <div>
                        <div class="idash-resume-top">
                            <b>{{ $r->role_title }}</b>
                        </div>
                        @if ($r->specialization_key)
                            <span class="idash-resume-spec">{{ \App\Support\MaktabgidData::specializationLabel($r->specialization_key)['label'] ?? $r->specialization_key }}</span>
                        @endif
                        <div class="idash-resume-meta">
                            <span><x-maktabgid.icon name="clock" :width="15" :height="15" /> {{ $r->experience }}</span>
                            @if ($r->salary_expectation)
                                <span><x-maktabgid.icon name="card" :width="15" :height="15" /> {{ $r->salary_expectation }} so'm</span>
                            @endif
                        </div>
                        <span class="idash-resume-until">Joylangan: {{ $r->created_at->format('d.m.Y') }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

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

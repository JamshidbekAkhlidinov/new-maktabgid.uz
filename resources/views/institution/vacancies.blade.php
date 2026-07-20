@php
    // Real ro'yxat — App\Models\Vacancy (Institution::vacancies()). "Vakansiya ochish"
    // (yaratish) formasi hali pullik-demo bo'lib qoladi, nomzodlar/ariza boshqaruvi esa
    // hali qurilmagan (ADR-0002, Faza 2: vacancy_applications jadvali) — shuning uchun
    // pastda faqat real e'lonlar ro'yxati va o'chirish amali ko'rsatiladi.
    $employmentLabels = ['full' => "To'liq stavka", 'part' => 'Yarim stavka', 'hourly' => 'Soatbay'];
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
        <span class="idash-chart-meta">{{ $vacancies->count() }} ta e'lon · e'lon narxi 100 000 so'm</span>
        <button type="button" class="btn btn-primary sm" data-modal-open="add-vacancy-modal">
            <x-maktabgid.icon name="plus" :width="15" :height="15" /> Vakansiya ochish
        </button>
    </div>

    @if ($vacancies->isEmpty())
        <div class="idash-badge-soft">
            <x-maktabgid.icon name="sparkle" :width="14" :height="14" /> Hali e'loningiz yo'q — "Vakansiya ochish" orqali birinchisini joylang
        </div>
    @else
        <div class="idash-vac-grid">
            @foreach ($vacancies as $v)
                <div class="idash-vac-card" data-vacancy-id="{{ $v->id }}">
                    <div class="idash-vac-top">
                        <span class="idash-pill-neutral" style="background:var(--primary-soft);color:var(--primary-ink)">{{ $employmentLabels[$v->employment_type] ?? $v->employment_type }}</span>
                    </div>
                    <h3>{{ $v->title }}</h3>
                    <div class="idash-vac-meta">
                        @if ($v->specialization_key)
                            <span><x-maktabgid.icon name="sparkle" :width="15" :height="15" /> {{ \App\Support\MaktabgidData::specializationLabel($v->specialization_key)['label'] ?? $v->specialization_key }}</span>
                        @endif
                        @if ($v->expires_at)
                            <span><x-maktabgid.icon name="cal" :width="15" :height="15" /> {{ $v->expires_at->format('d.m.Y') }}gacha</span>
                        @endif
                    </div>
                    <div class="idash-vac-foot">
                        <span class="idash-vac-price">{{ $v->salary_range ?: 'Kelishilgan' }}</span>
                        <div class="idash-vac-actions">
                            <button type="button" class="idash-lead-iconbtn danger js-vacancy-delete" data-vacancy-id="{{ $v->id }}" title="O'chirish">
                                <x-maktabgid.icon name="close" :width="14" :height="14" />
                            </button>
                            <button type="button" class="idash-vac-cand" data-modal-open="candidates-vacancy-{{ $v->id }}">Nomzodlar ({{ $v->applications->count() }})</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- ===== "Vakansiya ochish" modali — vakansiya joylash pullik xizmat (100 000 so'm),
         to'lov tizimi (Payme/Click) hali ulanmagani uchun umumiy "fake form" andozasi
         orqali demo ko'rinishda ishlaydi (ADR-0002). ===== --}}
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

    {{-- ===== "Nomzodlar" ko'rish modali — har bir vakansiya uchun alohida, real
         VacancyApplication (ADR-0002, Faza 2). Qabul/rad tugmalari real PATCH
         /ajax/institution/me/vacancy-applications/{id}/status ga ulangan. ===== --}}
    @foreach ($vacancies as $v)
        <x-maktabgid.modal-shell id="candidates-vacancy-{{ $v->id }}" :width="560">
            <div class="js-modal-body">
                <div class="modal-head">
                    <h3>Nomzodlar</h3>
                    <p>{{ $v->title }} — {{ $v->applications->count() }} nomzod</p>
                </div>

                @if ($v->applications->isEmpty())
                    <p style="color:var(--ink-3);font-weight:600;font-size:14px">Hali ariza kelmagan.</p>
                @else
                    <div class="idash-cand-list">
                        @foreach ($v->applications->sortByDesc('created_at') as $c)
                            <div class="idash-cand-row" data-application-id="{{ $c->id }}">
                                <x-maktabgid.avatar :name="$c->full_name" :size="48" />
                                <div class="idash-cand-main">
                                    <b>{{ $c->full_name }}</b>
                                    <span>{{ $c->phone }} · {{ $c->created_at->diffForHumans() }}@if ($c->note) · {{ \Illuminate\Support\Str::limit($c->note, 60) }} @endif</span>
                                    @if ($c->resume_path)
                                        <a href="{{ $c->resume_url }}" target="_blank" rel="noopener" style="display:inline-flex;align-items:center;gap:5px;margin-top:4px;font-size:12.5px;font-weight:700;color:var(--primary)">
                                            <x-maktabgid.icon name="paperclip" :width="13" :height="13" /> {{ $c->resume_original_name ?: 'Rezyume' }}
                                        </a>
                                    @endif
                                </div>
                                @if ($c->status === 'pending')
                                    <div class="idash-cand-actions">
                                        <button type="button" class="idash-cand-btn accept js-vacancy-app-status" data-application-id="{{ $c->id }}" data-status="accepted" title="Qabul qilish"><x-maktabgid.icon name="check" :width="16" :height="16" /></button>
                                        <button type="button" class="idash-cand-btn reject js-vacancy-app-status" data-application-id="{{ $c->id }}" data-status="rejected" title="Rad etish"><x-maktabgid.icon name="close" :width="16" :height="16" /></button>
                                    </div>
                                @else
                                    <span class="idash-status-pill" style="background:{{ $c->status === 'accepted' ? 'var(--primary-soft)' : 'var(--surface-2)' }};color:{{ $c->status === 'accepted' ? 'var(--primary-ink)' : 'var(--ink-3)' }}">{{ $c->status === 'accepted' ? 'Qabul qilindi' : 'Rad etildi' }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </x-maktabgid.modal-shell>
    @endforeach

    @endif
</x-institution.shell>

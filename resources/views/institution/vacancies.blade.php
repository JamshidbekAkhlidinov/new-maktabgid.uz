@php
    // Real ro'yxat — App\Models\Vacancy (Institution::vacancies()). "Vakansiya ochish"
    // (yaratish) formasi hali pullik-demo bo'lib qoladi, nomzodlar/ariza boshqaruvi esa
    // hali qurilmagan (ADR-0002, Faza 2: vacancy_applications jadvali) — shuning uchun
    // pastda faqat real e'lonlar ro'yxati va o'chirish amali ko'rsatiladi.
    $employmentLabels = ['full' => __('cabinet_institution.employment_full'), 'part' => __('cabinet_institution.employment_part'), 'hourly' => __('cabinet_institution.employment_hourly')];
@endphp

<x-institution.shell
    active="vacancies"
    title="{{ __('cabinet_institution.nav_vacancies') }}"
    sub="{{ __('cabinet_institution.institution_vacancies_sub') }}"
    :institution="$institution"
    :organizations="$organizations"
    :counts="$counts"
    :user="$user"
>
    @if ($institution)

    <div class="idash-toolbar">
        <span class="idash-chart-meta">{{ __('cabinet_institution.vacancies_toolbar_meta', ['count' => $vacancies->count()]) }}</span>
        <button type="button" class="btn btn-primary sm" data-modal-open="add-vacancy-modal">
            <x-maktabgid.icon name="plus" :width="15" :height="15" /> {{ __('cabinet_institution.open_vacancy') }}
        </button>
    </div>

    @if ($vacancies->isEmpty())
        <div class="idash-badge-soft">
            <x-maktabgid.icon name="sparkle" :width="14" :height="14" /> {{ __('cabinet_institution.vacancies_empty_notice') }}
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
                            <span><x-maktabgid.icon name="cal" :width="15" :height="15" /> {{ __('cabinet_institution.until_date', ['date' => $v->expires_at->format('d.m.Y')]) }}</span>
                        @endif
                    </div>
                    <div class="idash-vac-foot">
                        <span class="idash-vac-price">{{ $v->salary_range ?: __('cabinet_institution.negotiable') }}</span>
                        <div class="idash-vac-actions">
                            <button type="button" class="idash-lead-iconbtn danger js-vacancy-delete" data-vacancy-id="{{ $v->id }}" title="{{ __('cabinet_institution.delete') }}">
                                <x-maktabgid.icon name="close" :width="14" :height="14" />
                            </button>
                            <button type="button" class="idash-vac-cand" data-modal-open="candidates-vacancy-{{ $v->id }}">{{ __('cabinet_institution.candidates_count', ['count' => $v->applications->count()]) }}</button>
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
                <h3>{{ __('cabinet_institution.open_vacancy') }}</h3>
            </div>

            <form class="form js-fake-form">
                <x-maktabgid.field label="{{ __('cabinet_institution.field_position') }}" icon="bag">
                    <input type="text" required placeholder="Ingliz tili o'qituvchisi" />
                </x-maktabgid.field>
                <div class="form-row2">
                    <x-maktabgid.field label="{{ __('cabinet_institution.field_employment') }}" icon="sliders">
                        <select required>
                            <option>{{ __('cabinet_institution.employment_full') }}</option>
                            <option>{{ __('cabinet_institution.employment_part') }}</option>
                        </select>
                    </x-maktabgid.field>
                    <x-maktabgid.field label="{{ __('cabinet_institution.field_salary_range') }}" icon="card">
                        <input type="text" required placeholder="8-12 mln" />
                    </x-maktabgid.field>
                </div>
                <x-maktabgid.field label="{{ __('cabinet_institution.field_requirements') }}" icon="edit">
                    <textarea rows="3" placeholder="{{ __('cabinet_institution.requirements_placeholder') }}"></textarea>
                </x-maktabgid.field>
                <x-maktabgid.field label="{{ __('cabinet_institution.field_deadline') }}" icon="cal">
                    <input type="text" required placeholder="30-iyul" />
                </x-maktabgid.field>

                <div style="display:flex;align-items:center;gap:9px;padding:12px 14px;background:var(--accent-soft);border-radius:var(--r-md);font-size:12.5px;font-weight:700;color:#b45309">
                    <x-maktabgid.icon name="card" :width="16" :height="16" />
                    {{ __('cabinet_institution.vacancy_paid_notice') }}
                </div>

                <div style="display:flex;gap:10px;margin-top:4px">
                    <button class="btn btn-primary form-submit" type="submit" style="flex:1;justify-content:center">{{ __('cabinet_institution.post_vacancy_cta') }}</button>
                    <button class="btn btn-ghost js-modal-close" type="button">{{ __('cabinet_institution.cancel') }}</button>
                </div>
            </form>

            <x-maktabgid.success-note title="{{ __('cabinet_institution.vacancy_posted_title') }}" :close-target="true" class="js-fake-success" style="display:none">
                {{ __('cabinet_institution.vacancy_posted_body') }}
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
                    <h3>{{ __('cabinet_institution.candidates') }}</h3>
                    <p>{{ $v->title }} — {{ __('cabinet_institution.candidates_count_plain', ['count' => $v->applications->count()]) }}</p>
                </div>

                @if ($v->applications->isEmpty())
                    <p style="color:var(--ink-3);font-weight:600;font-size:14px">{{ __('cabinet_institution.no_applications_received') }}</p>
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
                                            <x-maktabgid.icon name="paperclip" :width="13" :height="13" /> {{ $c->resume_original_name ?: __('cabinet_institution.resume_word') }}
                                        </a>
                                    @endif
                                </div>
                                @if ($c->status === 'pending')
                                    <div class="idash-cand-actions">
                                        <button type="button" class="idash-cand-btn accept js-vacancy-app-status" data-application-id="{{ $c->id }}" data-status="accepted" title="{{ __('cabinet_institution.accept') }}"><x-maktabgid.icon name="check" :width="16" :height="16" /></button>
                                        <button type="button" class="idash-cand-btn reject js-vacancy-app-status" data-application-id="{{ $c->id }}" data-status="rejected" title="{{ __('cabinet_institution.reject') }}"><x-maktabgid.icon name="close" :width="16" :height="16" /></button>
                                    </div>
                                @else
                                    <span class="idash-status-pill" style="background:{{ $c->status === 'accepted' ? 'var(--primary-soft)' : 'var(--surface-2)' }};color:{{ $c->status === 'accepted' ? 'var(--primary-ink)' : 'var(--ink-3)' }}">{{ $c->status === 'accepted' ? __('cabinet_institution.candidate_accepted') : __('cabinet_institution.candidate_rejected') }}</span>
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

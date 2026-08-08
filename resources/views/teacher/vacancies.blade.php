@php
    // Real bozor ro'yxati — App\Models\Vacancy (muddati o'tmagan e'lonlar). "Ariza yuborish"
    // real POST /ajax/vacancies/{id}/apply orqali ishlaydi (mehmon ham yubora oladi,
    // ustoz sifatida ro'yxatdan o'tgan bo'lsa hisobiga bog'lanadi) — ADR-0002, Faza 2.
    $employmentLabels = ['full' => __('cabinet_teacher.employment_full'), 'part' => __('cabinet_teacher.employment_part'), 'hourly' => __('cabinet_teacher.employment_hourly')];
@endphp

<x-teacher.shell active="vacancies" :title="__('cabinet_teacher.nav_vacancies')" :sub="__('cabinet_teacher.vacancies_sub')" :teacher="$teacher" :counts="$counts">

    @if ($vacancies->isEmpty())
        <div class="idash-badge-soft">
            <x-maktabgid.icon name="sparkle" :width="14" :height="14" /> {{ __('cabinet_teacher.vacancies_empty') }}
        </div>
    @else
        <div style="display:flex;flex-direction:column;gap:14px;margin-top:14px">
            @foreach ($vacancies as $v)
                <div class="idash-vjob-row">
                    <span class="idash-vjob-ico">{{ \App\Support\MaktabgidData::monogram($v->org_name) }}</span>
                    <div>
                        <div class="idash-vjob-head">
                            <b>{{ $v->title }}</b>
                            <span class="idash-pill-neutral" style="background:var(--primary-soft);color:var(--primary-ink)">{{ $employmentLabels[$v->employment_type] ?? $v->employment_type }}</span>
                        </div>
                        <div class="idash-vjob-meta">
                            <span>{{ $v->org_name }}</span>
                            @if ($v->salary_range)
                                <span style="color:var(--primary-ink);font-weight:700">{{ $v->salary_range }} {{ __('cabinet_teacher.currency_sum') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="idash-vjob-side">
                        <span class="idash-vjob-ago">{{ $v->created_at->diffForHumans() }}</span>
                        <button type="button" class="btn btn-primary sm" data-modal-open="apply-vacancy-{{ $v->id }}">{{ __('cabinet_teacher.apply') }}</button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="idash-badge-soft">
        <x-maktabgid.icon name="sparkle" :width="14" :height="14" /> {{ __('cabinet_teacher.full_list_notice_prefix') }} <a href="{{ route('careers.index') }}">{{ __('cabinet_teacher.nav_vacancies') }}</a> {{ __('cabinet_teacher.full_list_notice_suffix') }}
    </div>

    {{-- ===== "Vakansiyaga ariza" modali — har bir vakansiya uchun alohida, real
         POST /ajax/vacancies/{id}/apply (ADR-0002, Faza 2). ===== --}}
    @foreach ($vacancies as $v)
        <x-maktabgid.modal-shell id="apply-vacancy-{{ $v->id }}" :width="480">
            <div class="js-modal-body js-inline-enroll">
                <div class="modal-head js-fake-form-head">
                    <h3>{{ __('cabinet_teacher.vacancy_apply_heading') }}</h3>
                </div>

                <div style="display:flex;align-items:center;gap:14px;background:var(--primary-soft);border-radius:var(--r-md);padding:14px 16px;margin-bottom:18px" class="js-fake-form-head">
                    <span class="idash-vjob-ico" style="width:44px;height:44px;font-size:14px">{{ \App\Support\MaktabgidData::monogram($v->org_name) }}</span>
                    <div>
                        <b style="display:block;font-family:var(--font-display);font-size:15.5px">{{ $v->title }}</b>
                        <span style="font-size:13px;color:var(--ink-2);font-weight:600">{{ $v->org_name }}@if ($v->salary_range) · {{ $v->salary_range }} {{ __('cabinet_teacher.currency_sum') }} @endif</span>
                    </div>
                </div>

                <form class="form js-vacancy-apply-form" data-vacancy-id="{{ $v->id }}" enctype="multipart/form-data">
                    <div class="form-row2">
                        <x-maktabgid.field :label="__('cabinet_teacher.field_fullname')" icon="user">
                            <input type="text" name="full_name" required value="{{ $teacher['name'] ?? '' }}" placeholder="Ism Familiya" />
                        </x-maktabgid.field>
                        <x-maktabgid.field :label="__('cabinet_teacher.field_phone')" icon="phone">
                            <input type="tel" name="phone" required value="{{ $teacher['phone'] ?? '' }}" placeholder="+998 90 123 45 67" />
                        </x-maktabgid.field>
                    </div>
                    <x-maktabgid.field :label="__('cabinet_teacher.field_cover_note')" :hint="__('cabinet_teacher.hint_optional')" icon="edit">
                        <textarea name="note" rows="3" placeholder="{{ __('cabinet_teacher.cover_note_placeholder') }}"></textarea>
                    </x-maktabgid.field>
                    <x-maktabgid.field :label="__('cabinet_teacher.field_resume_cv')" :hint="__('cabinet_teacher.hint_optional_pdf_word')" icon="paperclip">
                        <input type="file" name="resume" accept=".pdf,.doc,.docx" />
                    </x-maktabgid.field>
                    <div style="display:flex;gap:10px;margin-top:4px">
                        <button class="btn btn-primary form-submit" type="submit" style="flex:1;justify-content:center">
                            <x-maktabgid.icon name="send" :width="16" :height="16" /> {{ __('cabinet_teacher.apply') }}
                        </button>
                        <button class="btn btn-ghost js-modal-close" type="button">{{ __('cabinet_teacher.cancel') }}</button>
                    </div>
                </form>

                <x-maktabgid.success-note :title="__('cabinet_teacher.application_sent_title')" :close-target="true" class="js-fake-success" style="display:none">
                    {{ __('cabinet_teacher.application_sent_body', ['org' => $v->org_name]) }}
                </x-maktabgid.success-note>
            </div>
        </x-maktabgid.modal-shell>
    @endforeach

</x-teacher.shell>

@php
    // Mock: kabinet ichidagi "O'qituvchilar" boshqaruvi — hozircha namunaviy ro'yxat.
    // Real ma'lumot manbasi allaqachon mavjud: Institution::$teachers (json, profil
    // sahifasidagi "O'qituvchilar" bo'limida ishlatiladi — InstitutionCabinetController::profile()
    // dagi $teachersText'ga qarang). Keyingi bosqichda shu sahifa o'sha maydondan o'qiydigan
    // va CRUD qiladigan bo'ladi.
    $mockTeachers = [
        ['name' => 'Aziza Karimova', 'subject' => 'Ingliz tili', 'exp' => '8 yil tajriba', 'edu' => 'CELTA sertifikati · Toshkent davlat chet tillar instituti', 'ach' => ["IELTS 8.5 — 12 ta o'quvchi", 'Eng yaxshi ustoz 2025']],
        ['name' => 'Bekzod Rashidov', 'subject' => 'Matematika', 'exp' => '12 yil tajriba', 'edu' => "O'zMU, amaliy matematika", 'ach' => ["Respublika olimpiadasi — 3 g'olib"]],
        ['name' => 'Nilufar Tosheva', 'subject' => 'Boshlang\'ich ta\'lim', 'exp' => '6 yil tajriba', 'edu' => 'Nizomiy nomidagi TDPU', 'ach' => ['Montessori metodikasi bo\'yicha sertifikat']],
        ['name' => 'Sardor Yo\'ldoshev', 'subject' => 'IT / dasturlash', 'exp' => '5 yil tajriba', 'edu' => 'IT Park Academy, mentor', 'ach' => ["O'quvchilar hackathon g'olibi", 'Google sertifikati']],
    ];
@endphp

<x-institution.shell
    active="teachers"
    :title="__('cabinet_institution.nav_teachers')"
    :sub="__('cabinet_institution.teachers_sub')"
    :institution="$institution"
    :organizations="$organizations"
    :counts="$counts"
    :user="$user"
>
    @if ($institution)

    <div class="idash-toolbar">
        <span class="idash-chart-meta">{{ __('cabinet_institution.teachers_toolbar_meta', ['count' => count($mockTeachers)]) }}</span>
        <button type="button" class="btn btn-primary sm" data-modal-open="add-teacher-modal">
            <x-maktabgid.icon name="plus" :width="15" :height="15" /> {{ __('cabinet_institution.add_teacher') }}
        </button>
    </div>

    <div class="idash-grid2">
        @foreach ($mockTeachers as $t)
            <div class="idash-tcard">
                <x-maktabgid.avatar :name="$t['name']" :size="64" :square="true" />
                <div class="idash-tcard-main">
                    <div class="idash-tcard-head">
                        <div>
                            <b>{{ $t['name'] }}</b>
                            <span>{{ $t['subject'] }} · {{ $t['exp'] }}</span>
                        </div>
                        <div class="idash-card-actions">
                            <button type="button" class="idash-lead-iconbtn" title="{{ __('cabinet_institution.edit') }}" data-modal-open="edit-teacher-{{ $loop->index }}"><x-maktabgid.icon name="edit" :width="14" :height="14" /></button>
                            <button type="button" class="idash-lead-iconbtn danger" title="{{ __('cabinet_institution.delete') }}"><x-maktabgid.icon name="close" :width="14" :height="14" /></button>
                        </div>
                    </div>
                    <span class="idash-tcard-edu">{{ $t['edu'] }}</span>
                    <div class="idash-tcard-ach">
                        @foreach ($t['ach'] as $a)
                            <span><x-maktabgid.icon name="award" :width="14" :height="14" /> {{ $a }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="idash-badge-soft">
        <x-maktabgid.icon name="sparkle" :width="14" :height="14" /> {{ __('cabinet_institution.teachers_demo_notice') }}
    </div>

    {{-- ===== "O'qituvchi qo'shish" modali — real Teacher/Institution bog'lanishi hali yo'q
         (yuqoridagi $mockTeachers'ga qarang), shuning uchun umumiy "fake form" andozasi
         orqali ishlaydi. ===== --}}
    <x-maktabgid.modal-shell id="add-teacher-modal" :width="480">
        <div class="js-modal-body">
            <div class="modal-head js-fake-form-head">
                <h3>{{ __('cabinet_institution.add_teacher') }}</h3>
            </div>

            <form class="form js-fake-form">
                <x-maktabgid.field :label="__('cabinet_institution.field_fullname')" icon="user">
                    <input type="text" required placeholder="Masalan, Alisher Normatov" />
                </x-maktabgid.field>
                <div class="form-row2">
                    <x-maktabgid.field :label="__('cabinet_institution.field_position_subject')" icon="bag">
                        <input type="text" required placeholder="Matematika" />
                    </x-maktabgid.field>
                    <x-maktabgid.field :label="__('cabinet_institution.field_experience_years')" icon="clock">
                        <input type="text" required placeholder="10 yil" />
                    </x-maktabgid.field>
                </div>
                <x-maktabgid.field :label="__('cabinet_institution.field_education')" :hint="__('cabinet_institution.hint_edu')" icon="book">
                    <input type="text" placeholder="TDPU — 2009-yil" />
                </x-maktabgid.field>
                <x-maktabgid.field :label="__('cabinet_institution.field_achievements')" :hint="__('cabinet_institution.hint_comma_separated')" icon="award">
                    <textarea rows="3" placeholder="Yil o'qituvchisi — 2023, Respublika murabbiyi"></textarea>
                </x-maktabgid.field>

                <label class="upload-slot js-fake-photo" style="flex-direction:row;justify-content:center;padding:16px">
                    <input type="file" accept="image/*" hidden />
                    <x-maktabgid.icon name="camera" :width="18" :height="18" />
                    <span>{{ __('cabinet_institution.upload_photo_optional') }}</span>
                </label>

                <div style="display:flex;gap:10px;margin-top:4px">
                    <button class="btn btn-primary form-submit" type="submit" style="flex:1;justify-content:center">{{ __('cabinet_institution.save') }}</button>
                    <button class="btn btn-ghost js-modal-close" type="button">{{ __('cabinet_institution.cancel') }}</button>
                </div>
            </form>

            <x-maktabgid.success-note :title="__('cabinet_institution.teacher_added_title')" :close-target="true" class="js-fake-success" style="display:none">
                {{ __('cabinet_institution.teacher_added_body') }}
            </x-maktabgid.success-note>
        </div>
    </x-maktabgid.modal-shell>

    {{-- ===== "O'qituvchini tahrirlash" modali — har bir o'qituvchi uchun alohida ===== --}}
    @foreach ($mockTeachers as $t)
        <x-maktabgid.modal-shell id="edit-teacher-{{ $loop->index }}" :width="480">
            <div class="js-modal-body">
                <div class="modal-head js-fake-form-head">
                    <h3>{{ __('cabinet_institution.edit_teacher') }}</h3>
                </div>

                <form class="form js-fake-form">
                    <x-maktabgid.field :label="__('cabinet_institution.field_fullname')" icon="user">
                        <input type="text" value="{{ $t['name'] }}" required />
                    </x-maktabgid.field>
                    <div class="form-row2">
                        <x-maktabgid.field :label="__('cabinet_institution.field_position_subject')" icon="bag">
                            <input type="text" value="{{ $t['subject'] }}" required />
                        </x-maktabgid.field>
                        <x-maktabgid.field :label="__('cabinet_institution.field_experience_years')" icon="clock">
                            <input type="text" value="{{ $t['exp'] }}" required />
                        </x-maktabgid.field>
                    </div>
                    <x-maktabgid.field :label="__('cabinet_institution.field_education')" :hint="__('cabinet_institution.hint_edu')" icon="book">
                        <input type="text" value="{{ $t['edu'] }}" />
                    </x-maktabgid.field>
                    <x-maktabgid.field :label="__('cabinet_institution.field_achievements')" :hint="__('cabinet_institution.hint_comma_separated')" icon="award">
                        <textarea rows="3">{{ implode(', ', $t['ach']) }}</textarea>
                    </x-maktabgid.field>

                    <label class="upload-slot js-fake-photo" style="flex-direction:row;justify-content:center;padding:16px">
                        <input type="file" accept="image/*" hidden />
                        <x-maktabgid.icon name="camera" :width="18" :height="18" />
                        <span>{{ __('cabinet_institution.upload_photo_optional') }}</span>
                    </label>

                    <div style="display:flex;gap:10px;margin-top:4px">
                        <button class="btn btn-primary form-submit" type="submit" style="flex:1;justify-content:center">{{ __('cabinet_institution.save') }}</button>
                        <button class="btn btn-ghost js-modal-close" type="button">{{ __('cabinet_institution.cancel') }}</button>
                    </div>
                </form>

                <x-maktabgid.success-note :title="__('cabinet_institution.data_updated_title')" :close-target="true" class="js-fake-success" style="display:none">
                    {{ __('cabinet_institution.data_updated_body') }}
                </x-maktabgid.success-note>
            </div>
        </x-maktabgid.modal-shell>
    @endforeach

    @endif
</x-institution.shell>

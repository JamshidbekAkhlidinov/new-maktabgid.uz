@props(['school'])

<section class="card-block enroll-block" id="joylashtirish">
    <div class="enroll-head">
        <h3 style="margin:0"><x-maktabgid.icon name="edit" :width="19" :height="19" /> {{ __('school.enroll_title') }}</h3>
        <p>{{ __('school.enroll_intro') }}</p>
    </div>

    <div class="js-inline-enroll" data-school="{{ $school['name'] }}">
        <form class="enroll-form js-application-form">
            <input type="hidden" name="institution_id" value="{{ $school['id'] }}" />
            <input type="hidden" name="type" value="enrollment" />

            <div class="form-section">{{ __('school.child_section') }}</div>
            <x-maktabgid.field :label="__('school.child_name_label')" icon="user"><input name="child_name" required placeholder="{{ __('school.child_name_ph') }}" /></x-maktabgid.field>
            <div class="form-row2">
                <x-maktabgid.field :label="__('school.birth_date_label')" icon="cal"><input name="child_birth_date" required type="date" /></x-maktabgid.field>
                <x-maktabgid.field :label="__('school.current_grade_label')" icon="school"><input name="current_grade" placeholder="{{ __('school.current_grade_ph') }}" /></x-maktabgid.field>
            </div>
            <div class="form-row2">
                <x-maktabgid.field :label="__('school.target_grade_label')" icon="school"><input name="target_grade" required placeholder="{{ __('school.target_grade_ph') }}" /></x-maktabgid.field>
                <x-maktabgid.field :label="__('school.start_time_label')" icon="cal">
                    <select name="preferred_start"><option>{{ __('school.start_now') }}</option><option>{{ __('school.start_next_quarter') }}</option><option selected>{{ __('school.start_next_year') }}</option></select>
                </x-maktabgid.field>
            </div>
            <x-maktabgid.field :label="__('school.previous_school_label')" :hint="__('school.optional')"><input name="previous_school" placeholder="{{ __('school.previous_school_ph') }}" /></x-maktabgid.field>

            <div class="form-section">{{ __('school.parent_section') }}</div>
            <div class="form-row2">
                <x-maktabgid.field :label="__('school.parent_name_label')" icon="user"><input name="parent_name" required placeholder="{{ __('school.parent_name_ph') }}" /></x-maktabgid.field>
                <x-maktabgid.field :label="__('school.phone_label')" icon="phone"><input name="parent_phone" required placeholder="+998 90 123 45 67" /></x-maktabgid.field>
            </div>
            <x-maktabgid.field :label="__('school.note_label')" :hint="__('school.optional')"><textarea name="note" rows="2" placeholder="{{ __('school.note_ph') }}"></textarea></x-maktabgid.field>

            <p class="form-note"><x-maktabgid.icon name="shield" :width="15" :height="15" /> {{ __('school.enroll_note') }}</p>
            <div class="enroll-actions">
                <button class="btn btn-primary" type="submit"><x-maktabgid.icon name="send" :width="16" :height="16" /> {{ __('school.enroll_submit') }}</button>
            </div>
        </form>
        <x-maktabgid.success-note :title="__('school.enroll_success_title')" class="js-fake-success" style="display:none">
            {!! __('school.enroll_success_body', ['name' => '<b>'.e($school['name']).'</b>']) !!}
        </x-maktabgid.success-note>
    </div>
</section>

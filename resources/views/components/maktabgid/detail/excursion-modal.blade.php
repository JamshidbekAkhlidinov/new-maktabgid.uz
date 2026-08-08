@props(['school'])

<x-maktabgid.modal-shell id="excursion-modal" :width="460">
    <div class="js-modal-body">
        <div class="modal-head js-fake-form-head">
            <h3>{{ __('school.excursion_title') }}</h3>
            <p>{{ $school['name'] }}</p>
        </div>
        <form class="form js-application-form">
            <input type="hidden" name="institution_id" value="{{ $school['id'] }}" />
            <input type="hidden" name="type" value="excursion" />
            <x-maktabgid.field :label="__('school.parent_fullname_label')" icon="user"><input name="parent_name" required placeholder="{{ __('school.parent_name_ph') }}" /></x-maktabgid.field>
            <x-maktabgid.field :label="__('school.phone_label')" icon="phone"><input name="parent_phone" required placeholder="+998 90 123 45 67" /></x-maktabgid.field>
            <div class="form-row2">
                <x-maktabgid.field :label="__('school.child_age_label')" icon="user"><input name="child_age" required type="number" min="1" max="18" placeholder="7" /></x-maktabgid.field>
                <x-maktabgid.field :label="__('school.grade_group_label')" icon="school"><input name="target_grade" required placeholder="{{ __('school.target_grade_ph') }}" /></x-maktabgid.field>
            </div>
            <div class="form-row2">
                <x-maktabgid.field :label="__('school.excursion_date_label')" icon="cal"><input name="excursion_date" required type="date" min="{{ now()->toDateString() }}" /></x-maktabgid.field>
                <x-maktabgid.field :label="__('school.excursion_time_label')" icon="clock"><input name="excursion_time" required type="time" value="10:00" /></x-maktabgid.field>
            </div>
            <p class="form-note"><x-maktabgid.icon name="shield" :width="15" :height="15" /> {{ __('school.excursion_note') }}</p>
            <button class="btn btn-primary form-submit" type="submit"><x-maktabgid.icon name="send" :width="16" :height="16" /> {{ __('school.submit_application') }}</button>
        </form>
        <x-maktabgid.success-note :title="__('school.excursion_success_title')" :close-target="true" class="js-fake-success" style="display:none">
            {!! __('school.excursion_success_body', ['name' => '<b>'.e($school['name']).'</b>']) !!}
        </x-maktabgid.success-note>
    </div>
</x-maktabgid.modal-shell>

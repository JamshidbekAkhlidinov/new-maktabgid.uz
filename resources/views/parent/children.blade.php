@php
    // Real jadval — App\Models\Child (User::children(), ParentCabinetController::context()).
    // $children ParentCabinetController'dan keladi (dashboard bilan bir xil ro'yxat,
    // ikkalasi izchil bo'lishi uchun).
    $childWhy = [
        ['icon' => 'target', 'title' => __('cabinet_parent.why_1_title'), 'text' => __('cabinet_parent.why_1_text')],
        ['icon' => 'bell', 'title' => __('cabinet_parent.why_2_title'), 'text' => __('cabinet_parent.why_2_text')],
        ['icon' => 'shield', 'title' => __('cabinet_parent.why_3_title'), 'text' => __('cabinet_parent.why_3_text')],
    ];
    $interestCatalog = ['Sport', 'Musiqa', 'Matematika', 'Dasturlash', 'Rasm', 'Tillar', 'Fan', 'Adabiyot', 'Raqs'];
    $interestLabels = [
        'Sport' => __('cabinet_parent.interest_sport'),
        'Musiqa' => __('cabinet_parent.interest_music'),
        'Matematika' => __('cabinet_parent.interest_math'),
        'Dasturlash' => __('cabinet_parent.interest_programming'),
        'Rasm' => __('cabinet_parent.interest_drawing'),
        'Tillar' => __('cabinet_parent.interest_languages'),
        'Fan' => __('cabinet_parent.interest_science'),
        'Adabiyot' => __('cabinet_parent.interest_literature'),
        'Raqs' => __('cabinet_parent.interest_dance'),
    ];
    $genderLabel = ['ogil' => __('cabinet_parent.gender_boy_full'), 'qiz' => __('cabinet_parent.gender_girl_full')];
@endphp

<x-parent.shell active="children" :title="__('cabinet_parent.nav_children')" :sub="__('cabinet_parent.children_sub')" :user="$user" :stats="$stats">

    <div class="panel">
        <div class="panel-head"><h3>{{ __('cabinet_parent.nav_children') }}</h3></div>

        <div class="cab-why">
            <b>{{ __('cabinet_parent.why_title') }}</b>
            <div class="cab-why-grid">
                @foreach ($childWhy as $w)
                    <div class="cab-why-item">
                        <span class="cab-why-ico"><x-maktabgid.icon :name="$w['icon']" :width="20" :height="20" /></span>
                        <div><b>{{ $w['title'] }}</b><span>{{ $w['text'] }}</span></div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="cab-child-grid">
            @foreach ($children as $ch)
                <div class="cab-child-card">
                    <div class="cab-child-top">
                        <x-maktabgid.avatar :name="$ch->name" :size="56" />
                        <div class="cab-child-main">
                            <b>{{ trim($ch->name . ' ' . $ch->last_name) }}</b>
                            <span>{{ __('cabinet_parent.age_years', ['age' => $ch->age]) }} · {{ $genderLabel[$ch->gender] ?? '' }}</span>
                        </div>
                        <div style="display:flex;gap:6px">
                            <button type="button" class="idash-lead-iconbtn" title="{{ __('cabinet_parent.edit') }}" data-modal-open="edit-child-{{ $ch->id }}"><x-maktabgid.icon name="edit" :width="15" :height="15" /></button>
                            <button type="button" class="idash-lead-iconbtn danger js-child-delete" data-child-id="{{ $ch->id }}" title="{{ __('cabinet_parent.delete') }}"><x-maktabgid.icon name="close" :width="15" :height="15" /></button>
                        </div>
                    </div>
                    <div class="cab-child-tags">
                        @foreach (($ch->interests ?? []) as $i)
                            <span>{{ $interestLabels[$i] ?? $i }}</span>
                        @endforeach
                    </div>
                </div>
            @endforeach
            <button type="button" class="cab-child-add" data-modal-open="add-child-modal">
                <x-maktabgid.icon name="plus" :width="22" :height="22" />
                {{ __('cabinet_parent.add_child') }}
            </button>
        </div>
    </div>

    {{-- ===== "Farzand qo'shish" modali — real POST /ajax/children ===== --}}
    <x-maktabgid.modal-shell id="add-child-modal" :width="480">
        <div class="js-modal-body">
            <div class="modal-head">
                <h3>{{ __('cabinet_parent.add_child') }}</h3>
            </div>
            <form class="form js-child-form">
                <div class="js-form-error" style="display:none;padding:10px 14px;background:#fdecec;color:#d4504e;border-radius:var(--r-md);font-size:13px;font-weight:700"></div>

                <div class="form-row2">
                    <x-maktabgid.field :label="__('cabinet_parent.field_firstname')">
                        <input type="text" name="name" required placeholder="Ali" />
                    </x-maktabgid.field>
                    <x-maktabgid.field :label="__('cabinet_parent.field_lastname')">
                        <input type="text" name="last_name" placeholder="Rahimov" />
                    </x-maktabgid.field>
                </div>

                <span style="display:block;font-size:12.5px;font-weight:700;color:var(--ink-2);margin-bottom:2px">{{ __('cabinet_parent.field_gender') }}</span>
                <div class="choice-row js-child-gender" id="add-child-gender">
                    <button type="button" class="choice-btn on" data-gender="ogil">{{ __('cabinet_parent.gender_boy_short') }}</button>
                    <button type="button" class="choice-btn" data-gender="qiz">{{ __('cabinet_parent.gender_girl_short') }}</button>
                </div>

                <x-maktabgid.field :label="__('cabinet_parent.field_age')">
                    <input type="number" name="age" min="0" max="20" required placeholder="3" />
                </x-maktabgid.field>

                <span style="display:block;font-size:12.5px;font-weight:700;color:var(--ink-2);margin-bottom:2px">{{ __('cabinet_parent.field_interests') }}</span>
                <div class="chip-row js-child-interests" id="add-child-interests">
                    @foreach ($interestCatalog as $interest)
                        <button type="button" class="chip" data-interest="{{ $interest }}">{{ $interestLabels[$interest] ?? $interest }}</button>
                    @endforeach
                </div>

                <div style="display:flex;gap:10px;margin-top:4px">
                    <button class="btn btn-primary form-submit" type="submit" style="flex:1;justify-content:center">{{ __('cabinet_parent.save') }}</button>
                    <button class="btn btn-ghost js-modal-close" type="button">{{ __('cabinet_parent.cancel') }}</button>
                </div>
            </form>
        </div>
    </x-maktabgid.modal-shell>

    {{-- ===== "Farzandni tahrirlash" modali — har bir farzand uchun alohida,
         real PUT /ajax/children/{id} ===== --}}
    @foreach ($children as $ch)
        <x-maktabgid.modal-shell id="edit-child-{{ $ch->id }}" :width="480">
            <div class="js-modal-body">
                <div class="modal-head">
                    <h3>{{ __('cabinet_parent.edit_child') }}</h3>
                </div>
                <form class="form js-child-form" data-child-id="{{ $ch->id }}">
                    <div class="js-form-error" style="display:none;padding:10px 14px;background:#fdecec;color:#d4504e;border-radius:var(--r-md);font-size:13px;font-weight:700"></div>

                    <div class="form-row2">
                        <x-maktabgid.field :label="__('cabinet_parent.field_firstname')">
                            <input type="text" name="name" value="{{ $ch->name }}" required />
                        </x-maktabgid.field>
                        <x-maktabgid.field :label="__('cabinet_parent.field_lastname')">
                            <input type="text" name="last_name" value="{{ $ch->last_name }}" />
                        </x-maktabgid.field>
                    </div>

                    <span style="display:block;font-size:12.5px;font-weight:700;color:var(--ink-2);margin-bottom:2px">{{ __('cabinet_parent.field_gender') }}</span>
                    <div class="choice-row js-child-gender" id="edit-child-gender-{{ $ch->id }}">
                        <button type="button" class="choice-btn{{ $ch->gender === 'ogil' ? ' on' : '' }}" data-gender="ogil">{{ __('cabinet_parent.gender_boy_short') }}</button>
                        <button type="button" class="choice-btn{{ $ch->gender === 'qiz' ? ' on' : '' }}" data-gender="qiz">{{ __('cabinet_parent.gender_girl_short') }}</button>
                    </div>

                    <x-maktabgid.field :label="__('cabinet_parent.field_age')">
                        <input type="number" name="age" min="0" max="20" value="{{ $ch->age }}" required />
                    </x-maktabgid.field>

                    <span style="display:block;font-size:12.5px;font-weight:700;color:var(--ink-2);margin-bottom:2px">{{ __('cabinet_parent.field_interests') }}</span>
                    <div class="chip-row js-child-interests" id="edit-child-interests-{{ $ch->id }}">
                        @foreach ($interestCatalog as $interest)
                            <button type="button" class="chip{{ in_array($interest, $ch->interests ?? [], true) ? ' on' : '' }}" data-interest="{{ $interest }}">{{ $interestLabels[$interest] ?? $interest }}</button>
                        @endforeach
                    </div>

                    <div style="display:flex;gap:10px;margin-top:4px">
                        <button class="btn btn-primary form-submit" type="submit" style="flex:1;justify-content:center">{{ __('cabinet_parent.save') }}</button>
                        <button class="btn btn-ghost js-modal-close" type="button">{{ __('cabinet_parent.cancel') }}</button>
                    </div>
                </form>
            </div>
        </x-maktabgid.modal-shell>
    @endforeach

</x-parent.shell>

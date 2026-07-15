@php
    // Real jadval — App\Models\Child (User::children(), ParentCabinetController::context()).
    // $children ParentCabinetController'dan keladi (dashboard bilan bir xil ro'yxat,
    // ikkalasi izchil bo'lishi uchun).
    $childWhy = [
        ['icon' => 'target', 'title' => 'Aniqroq tavsiya', 'text' => 'AI Tanlovchi yosh va qiziqishga mos muassasa taklif qiladi.'],
        ['icon' => 'bell', 'title' => 'Muhim eslatmalar', 'text' => "Ekskursiya va ariza holatlari farzand bo'yicha ajratiladi."],
        ['icon' => 'shield', 'title' => 'Xavfsiz va shaxsiy', 'text' => "Ma'lumotlar faqat sizga ko'rinadi, uchinchi shaxsga berilmaydi."],
    ];
    $interestCatalog = ['Sport', 'Musiqa', 'Matematika', 'Dasturlash', 'Rasm', 'Tillar', 'Fan', 'Adabiyot', 'Raqs'];
    $genderLabel = ['ogil' => "O'g'il bola", 'qiz' => 'Qiz bola'];
@endphp

<x-parent.shell active="children" title="Farzandlarim" sub="AI Tanlovchi uchun farzand ma'lumotlari" :user="$user" :stats="$stats">

    <div class="panel">
        <div class="panel-head"><h3>Farzandlarim</h3></div>

        <div class="cab-why">
            <b>Nima uchun farzand qo'shish kerak?</b>
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
                            <span>{{ $ch->age }} yosh · {{ $genderLabel[$ch->gender] ?? '' }}</span>
                        </div>
                        <div style="display:flex;gap:6px">
                            <button type="button" class="idash-lead-iconbtn" title="Tahrirlash" data-modal-open="edit-child-{{ $ch->id }}"><x-maktabgid.icon name="edit" :width="15" :height="15" /></button>
                            <button type="button" class="idash-lead-iconbtn danger js-child-delete" data-child-id="{{ $ch->id }}" title="O'chirish"><x-maktabgid.icon name="close" :width="15" :height="15" /></button>
                        </div>
                    </div>
                    <div class="cab-child-tags">
                        @foreach (($ch->interests ?? []) as $i)
                            <span>{{ $i }}</span>
                        @endforeach
                    </div>
                </div>
            @endforeach
            <button type="button" class="cab-child-add" data-modal-open="add-child-modal">
                <x-maktabgid.icon name="plus" :width="22" :height="22" />
                Farzand qo'shish
            </button>
        </div>
    </div>

    {{-- ===== "Farzand qo'shish" modali — real POST /ajax/children ===== --}}
    <x-maktabgid.modal-shell id="add-child-modal" :width="480">
        <div class="js-modal-body">
            <div class="modal-head">
                <h3>Farzand qo'shish</h3>
            </div>
            <form class="form js-child-form">
                <div class="js-form-error" style="display:none;padding:10px 14px;background:#fdecec;color:#d4504e;border-radius:var(--r-md);font-size:13px;font-weight:700"></div>

                <div class="form-row2">
                    <x-maktabgid.field label="Ismi">
                        <input type="text" name="name" required placeholder="Ali" />
                    </x-maktabgid.field>
                    <x-maktabgid.field label="Familiyasi">
                        <input type="text" name="last_name" placeholder="Rahimov" />
                    </x-maktabgid.field>
                </div>

                <span style="display:block;font-size:12.5px;font-weight:700;color:var(--ink-2);margin-bottom:2px">Jinsi</span>
                <div class="choice-row js-child-gender" id="add-child-gender">
                    <button type="button" class="choice-btn on" data-gender="ogil">O'g'il</button>
                    <button type="button" class="choice-btn" data-gender="qiz">Qiz</button>
                </div>

                <x-maktabgid.field label="Yoshi (yil)">
                    <input type="number" name="age" min="0" max="20" required placeholder="3" />
                </x-maktabgid.field>

                <span style="display:block;font-size:12.5px;font-weight:700;color:var(--ink-2);margin-bottom:2px">Qiziqishlari</span>
                <div class="chip-row js-child-interests" id="add-child-interests">
                    @foreach ($interestCatalog as $interest)
                        <button type="button" class="chip" data-interest="{{ $interest }}">{{ $interest }}</button>
                    @endforeach
                </div>

                <div style="display:flex;gap:10px;margin-top:4px">
                    <button class="btn btn-primary form-submit" type="submit" style="flex:1;justify-content:center">Saqlash</button>
                    <button class="btn btn-ghost js-modal-close" type="button">Bekor qilish</button>
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
                    <h3>Farzandni tahrirlash</h3>
                </div>
                <form class="form js-child-form" data-child-id="{{ $ch->id }}">
                    <div class="js-form-error" style="display:none;padding:10px 14px;background:#fdecec;color:#d4504e;border-radius:var(--r-md);font-size:13px;font-weight:700"></div>

                    <div class="form-row2">
                        <x-maktabgid.field label="Ismi">
                            <input type="text" name="name" value="{{ $ch->name }}" required />
                        </x-maktabgid.field>
                        <x-maktabgid.field label="Familiyasi">
                            <input type="text" name="last_name" value="{{ $ch->last_name }}" />
                        </x-maktabgid.field>
                    </div>

                    <span style="display:block;font-size:12.5px;font-weight:700;color:var(--ink-2);margin-bottom:2px">Jinsi</span>
                    <div class="choice-row js-child-gender" id="edit-child-gender-{{ $ch->id }}">
                        <button type="button" class="choice-btn{{ $ch->gender === 'ogil' ? ' on' : '' }}" data-gender="ogil">O'g'il</button>
                        <button type="button" class="choice-btn{{ $ch->gender === 'qiz' ? ' on' : '' }}" data-gender="qiz">Qiz</button>
                    </div>

                    <x-maktabgid.field label="Yoshi (yil)">
                        <input type="number" name="age" min="0" max="20" value="{{ $ch->age }}" required />
                    </x-maktabgid.field>

                    <span style="display:block;font-size:12.5px;font-weight:700;color:var(--ink-2);margin-bottom:2px">Qiziqishlari</span>
                    <div class="chip-row js-child-interests" id="edit-child-interests-{{ $ch->id }}">
                        @foreach ($interestCatalog as $interest)
                            <button type="button" class="chip{{ in_array($interest, $ch->interests ?? [], true) ? ' on' : '' }}" data-interest="{{ $interest }}">{{ $interest }}</button>
                        @endforeach
                    </div>

                    <div style="display:flex;gap:10px;margin-top:4px">
                        <button class="btn btn-primary form-submit" type="submit" style="flex:1;justify-content:center">Saqlash</button>
                        <button class="btn btn-ghost js-modal-close" type="button">Bekor qilish</button>
                    </div>
                </form>
            </div>
        </x-maktabgid.modal-shell>
    @endforeach

</x-parent.shell>

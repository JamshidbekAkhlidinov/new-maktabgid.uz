@php
    // Mock: farzand profillari (AI Tanlovchi uchun) hali alohida DB jadvali yo'q —
    // $mockChildren ParentCabinetController::context()'dan keladi (dashboard bilan bir xil
    // ro'yxat, ikkalasi izchil bo'lishi uchun).
    $childWhy = [
        ['icon' => 'target', 'title' => 'Aniqroq tavsiya', 'text' => 'AI Tanlovchi yosh va qiziqishga mos muassasa taklif qiladi.'],
        ['icon' => 'bell', 'title' => 'Muhim eslatmalar', 'text' => "Ekskursiya va ariza holatlari farzand bo'yicha ajratiladi."],
        ['icon' => 'shield', 'title' => 'Xavfsiz va shaxsiy', 'text' => "Ma'lumotlar faqat sizga ko'rinadi, uchinchi shaxsga berilmaydi."],
    ];
    $interestCatalog = ['Sport', 'Musiqa', 'Matematika', 'Dasturlash', 'Rasm', 'Tillar', 'Fan', 'Adabiyot', 'Raqs'];
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
            @foreach ($mockChildren as $ch)
                <div class="cab-child-card">
                    <div class="cab-child-top">
                        <x-maktabgid.avatar :name="$ch['name']" :size="56" />
                        <div class="cab-child-main">
                            <b>{{ $ch['name'] }}</b>
                            <span>{{ $ch['age'] }} · {{ $ch['gender'] }}</span>
                        </div>
                        <div style="display:flex;gap:6px">
                            <button type="button" class="idash-lead-iconbtn" title="Tahrirlash" data-modal-open="edit-child-{{ $loop->index }}"><x-maktabgid.icon name="edit" :width="15" :height="15" /></button>
                        </div>
                    </div>
                    <div class="cab-child-tags">
                        @foreach ($ch['interests'] as $i)
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

    <div class="idash-badge-soft">
        <x-maktabgid.icon name="sparkle" :width="14" :height="14" /> Bu bo'lim demo ma'lumot bilan ko'rsatilmoqda — tez orada real farzand profillari bilan ishga tushadi
    </div>

    {{-- ===== "Farzand qo'shish" modali — real farzand jadvali hali yo'q (yuqoridagi
         $mockChildren'ga qarang), shuning uchun umumiy "fake form" andozasi orqali ishlaydi. ===== --}}
    <x-maktabgid.modal-shell id="add-child-modal" :width="480">
        <div class="js-modal-body">
            <div class="modal-head js-fake-form-head">
                <h3>Farzand qo'shish</h3>
            </div>
            <form class="form js-fake-form">
                <div class="form-row2">
                    <x-maktabgid.field label="Ismi">
                        <input type="text" required placeholder="Ali" />
                    </x-maktabgid.field>
                    <x-maktabgid.field label="Familiyasi">
                        <input type="text" placeholder="Rahimov" />
                    </x-maktabgid.field>
                </div>

                <span style="display:block;font-size:12.5px;font-weight:700;color:var(--ink-2);margin-bottom:2px">Jinsi</span>
                <div class="choice-row" id="add-child-gender">
                    <button type="button" class="choice-btn on" data-gender="ogil">O'g'il</button>
                    <button type="button" class="choice-btn" data-gender="qiz">Qiz</button>
                </div>

                <x-maktabgid.field label="Yoshi (yil)">
                    <input type="number" min="0" max="20" required placeholder="3" />
                </x-maktabgid.field>

                <span style="display:block;font-size:12.5px;font-weight:700;color:var(--ink-2);margin-bottom:2px">Qiziqishlari</span>
                <div class="chip-row" id="add-child-interests">
                    @foreach ($interestCatalog as $interest)
                        <button type="button" class="chip" data-interest="{{ $interest }}">{{ $interest }}</button>
                    @endforeach
                </div>

                <div style="display:flex;gap:10px;margin-top:4px">
                    <button class="btn btn-primary form-submit" type="submit" style="flex:1;justify-content:center">Saqlash</button>
                    <button class="btn btn-ghost js-modal-close" type="button">Bekor qilish</button>
                </div>
            </form>
            <x-maktabgid.success-note title="Farzand qo'shildi!" :close-target="true" class="js-fake-success" style="display:none">
                AI Tanlovchi endi shu ma'lumotlar asosida tavsiya beradi.
            </x-maktabgid.success-note>
        </div>
    </x-maktabgid.modal-shell>

    {{-- ===== "Farzandni tahrirlash" modali — har bir farzand uchun alohida ===== --}}
    @foreach ($mockChildren as $ch)
        @php
            $parts = explode(' ', trim($ch['name']), 2);
            $isBoy = str_starts_with($ch['gender'], "O'g'il");
            $ageNum = (int) preg_replace('/\D/', '', $ch['age']);
        @endphp
        <x-maktabgid.modal-shell id="edit-child-{{ $loop->index }}" :width="480">
            <div class="js-modal-body">
                <div class="modal-head js-fake-form-head">
                    <h3>Farzandni tahrirlash</h3>
                </div>
                <form class="form js-fake-form">
                    <div class="form-row2">
                        <x-maktabgid.field label="Ismi">
                            <input type="text" value="{{ $parts[0] ?? '' }}" required />
                        </x-maktabgid.field>
                        <x-maktabgid.field label="Familiyasi">
                            <input type="text" value="{{ $parts[1] ?? '' }}" />
                        </x-maktabgid.field>
                    </div>

                    <span style="display:block;font-size:12.5px;font-weight:700;color:var(--ink-2);margin-bottom:2px">Jinsi</span>
                    <div class="choice-row" id="edit-child-gender-{{ $loop->index }}">
                        <button type="button" class="choice-btn{{ $isBoy ? ' on' : '' }}" data-gender="ogil">O'g'il</button>
                        <button type="button" class="choice-btn{{ ! $isBoy ? ' on' : '' }}" data-gender="qiz">Qiz</button>
                    </div>

                    <x-maktabgid.field label="Yoshi (yil)">
                        <input type="number" min="0" max="20" value="{{ $ageNum }}" required />
                    </x-maktabgid.field>

                    <span style="display:block;font-size:12.5px;font-weight:700;color:var(--ink-2);margin-bottom:2px">Qiziqishlari</span>
                    <div class="chip-row" id="edit-child-interests-{{ $loop->index }}">
                        @foreach ($interestCatalog as $interest)
                            <button type="button" class="chip{{ in_array($interest, $ch['interests'], true) ? ' on' : '' }}" data-interest="{{ $interest }}">{{ $interest }}</button>
                        @endforeach
                    </div>

                    <div style="display:flex;gap:10px;margin-top:4px">
                        <button class="btn btn-primary form-submit" type="submit" style="flex:1;justify-content:center">Saqlash</button>
                        <button class="btn btn-ghost js-modal-close" type="button">Bekor qilish</button>
                    </div>
                </form>
                <x-maktabgid.success-note title="Ma'lumotlar yangilandi!" :close-target="true" class="js-fake-success" style="display:none">
                    O'zgarishlar saqlandi.
                </x-maktabgid.success-note>
            </div>
        </x-maktabgid.modal-shell>
    @endforeach

</x-parent.shell>

@php
    // Mock: "O'quvchilar yutuqlari" — hali alohida DB jadvali yo'q, shuning uchun
    // namunaviy ro'yxat bilan ko'rsatiladi (leads.blade.php dagi kabi yondashuv).
    $levelMeta = [
        'intl' => ['label' => 'Xalqaro', 'class' => 'intl'],
        'national' => ['label' => 'Respublika', 'class' => 'national'],
        'regional' => ['label' => 'Viloyat', 'class' => 'regional'],
        'city' => ['label' => 'Shahar', 'class' => 'city'],
    ];
    $mockAchievements = [
        ['title' => 'IELTS 8.5 ball', 'student' => 'Sardor Aliyev', 'year' => 2025, 'type' => 'Xalqaro imtihon', 'level' => 'intl'],
        ['title' => 'Respublika matematika olimpiadasi — 1-o\'rin', 'student' => 'Madina Yusupova', 'year' => 2025, 'type' => 'Olimpiada', 'level' => 'national'],
        ['title' => 'Robototexnika tanlovi — g\'olib', 'student' => 'Jasur Kamolov', 'year' => 2024, 'type' => 'Tanlov', 'level' => 'national'],
        ['title' => 'Viloyat she\'riyat kechasi — 2-o\'rin', 'student' => 'Laylo Ergasheva', 'year' => 2024, 'type' => 'Ijodiy tanlov', 'level' => 'regional'],
    ];
@endphp

<x-institution.shell
    active="achievements"
    title="O'quvchilar yutuqlari"
    sub="Ota-onalar uchun ishonch — profil sahifasida ko'rinadi"
    :institution="$institution"
    :organizations="$organizations"
    :counts="$counts"
    :user="$user"
>
    @if ($institution)

    <div class="idash-toolbar">
        <span class="idash-chart-meta">Yutuqlar profil sahifasida ota-onalarga ko'rinadi — ishonchni oshiradi</span>
        <button type="button" class="btn btn-primary sm" data-modal-open="add-achievement-modal">
            <x-maktabgid.icon name="plus" :width="15" :height="15" /> Yutuq qo'shish
        </button>
    </div>

    <div class="panel" style="padding:10px">
        <div class="idash-ach-table">
            @foreach ($mockAchievements as $a)
                <div class="idash-ach-row">
                    <span class="idash-ach-ico"><x-maktabgid.icon name="trophy" :width="24" :height="24" /></span>
                    <div class="idash-ach-main">
                        <b>{{ $a['title'] }}</b>
                        <span>{{ $a['student'] }} · {{ $a['year'] }}-yil · <a href="#">Sertifikat ko'rish</a></span>
                    </div>
                    <span class="idash-pill-neutral">{{ $a['type'] }}</span>
                    <span class="idash-pill-level {{ $levelMeta[$a['level']]['class'] }}">{{ $levelMeta[$a['level']]['label'] }}</span>
                    <div class="idash-card-actions">
                        <button type="button" class="idash-lead-iconbtn" title="Tahrirlash" data-modal-open="edit-achievement-{{ $loop->index }}"><x-maktabgid.icon name="edit" :width="14" :height="14" /></button>
                        <button type="button" class="idash-lead-iconbtn danger" title="O'chirish"><x-maktabgid.icon name="close" :width="14" :height="14" /></button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="idash-badge-soft">
        <x-maktabgid.icon name="sparkle" :width="14" :height="14" /> Bu bo'lim demo ma'lumot bilan ko'rsatilmoqda — tez orada real yutuqlar bazasi bilan ishga tushadi
    </div>

    {{-- ===== "Yutuq qo'shish" modali — real yutuqlar jadvali hali yo'q (yuqoridagi
         $mockAchievements'ga qarang), shuning uchun umumiy "fake form" andozasi orqali
         ishlaydi. ===== --}}
    <x-maktabgid.modal-shell id="add-achievement-modal" :width="480">
        <div class="js-modal-body">
            <div class="modal-head js-fake-form-head">
                <h3>Yutuq qo'shish</h3>
            </div>

            <form class="form js-fake-form">
                <x-maktabgid.field label="Yutuq / mukofot nomi" icon="trophy">
                    <input type="text" required placeholder="Matematika olimpiadasi — 1-o'rin" />
                </x-maktabgid.field>
                <div class="form-row2">
                    <x-maktabgid.field label="O'quvchi ismi" hint="ixtiyoriy" icon="user">
                        <input type="text" placeholder="Sardor Karimov" />
                    </x-maktabgid.field>
                    <x-maktabgid.field label="Yil" icon="cal">
                        <input type="text" required value="{{ now()->year }}" />
                    </x-maktabgid.field>
                </div>
                <div class="form-row2">
                    <x-maktabgid.field label="Turi" icon="award">
                        <input type="text" required placeholder="Olimpiada" />
                    </x-maktabgid.field>
                    <x-maktabgid.field label="Daraja" icon="badge">
                        <select required>
                            @foreach ($levelMeta as $key => $lvl)
                                <option value="{{ $key }}">{{ $lvl['label'] }}</option>
                            @endforeach
                        </select>
                    </x-maktabgid.field>
                </div>

                <label class="upload-slot js-fake-photo" style="flex-direction:row;justify-content:center;padding:16px">
                    <input type="file" accept="image/*,.pdf" hidden />
                    <x-maktabgid.icon name="upload" :width="18" :height="18" />
                    <span>Sertifikat / rasm yuklash (ixtiyoriy)</span>
                </label>

                <div style="display:flex;gap:10px;margin-top:4px">
                    <button class="btn btn-primary form-submit" type="submit" style="flex:1;justify-content:center">Saqlash</button>
                    <button class="btn btn-ghost js-modal-close" type="button">Bekor qilish</button>
                </div>
            </form>

            <x-maktabgid.success-note title="Yutuq qo'shildi!" :close-target="true" class="js-fake-success" style="display:none">
                Profil sahifasidagi "Yutuqlar" bo'limida ko'rinadi.
            </x-maktabgid.success-note>
        </div>
    </x-maktabgid.modal-shell>

    {{-- ===== "Yutuqni tahrirlash" modali — har bir yutuq uchun alohida ===== --}}
    @foreach ($mockAchievements as $a)
        <x-maktabgid.modal-shell id="edit-achievement-{{ $loop->index }}" :width="480">
            <div class="js-modal-body">
                <div class="modal-head js-fake-form-head">
                    <h3>Yutuqni tahrirlash</h3>
                </div>

                <form class="form js-fake-form">
                    <x-maktabgid.field label="Yutuq / mukofot nomi" icon="trophy">
                        <input type="text" value="{{ $a['title'] }}" required />
                    </x-maktabgid.field>
                    <div class="form-row2">
                        <x-maktabgid.field label="O'quvchi ismi" hint="ixtiyoriy" icon="user">
                            <input type="text" value="{{ $a['student'] }}" />
                        </x-maktabgid.field>
                        <x-maktabgid.field label="Yil" icon="cal">
                            <input type="text" value="{{ $a['year'] }}" required />
                        </x-maktabgid.field>
                    </div>
                    <div class="form-row2">
                        <x-maktabgid.field label="Turi" icon="award">
                            <input type="text" value="{{ $a['type'] }}" required />
                        </x-maktabgid.field>
                        <x-maktabgid.field label="Daraja" icon="badge">
                            <select required>
                                @foreach ($levelMeta as $key => $lvl)
                                    <option value="{{ $key }}" @selected($key === $a['level'])>{{ $lvl['label'] }}</option>
                                @endforeach
                            </select>
                        </x-maktabgid.field>
                    </div>

                    <label class="upload-slot js-fake-photo" style="flex-direction:row;justify-content:center;padding:16px">
                        <input type="file" accept="image/*,.pdf" hidden />
                        <x-maktabgid.icon name="upload" :width="18" :height="18" />
                        <span>Sertifikat / rasm yuklash (ixtiyoriy)</span>
                    </label>

                    <div style="display:flex;gap:10px;margin-top:4px">
                        <button class="btn btn-primary form-submit" type="submit" style="flex:1;justify-content:center">Saqlash</button>
                        <button class="btn btn-ghost js-modal-close" type="button">Bekor qilish</button>
                    </div>
                </form>

                <x-maktabgid.success-note title="Ma'lumotlar yangilandi!" :close-target="true" class="js-fake-success" style="display:none">
                    O'zgarishlar profil sahifasida ham aks etadi.
                </x-maktabgid.success-note>
            </div>
        </x-maktabgid.modal-shell>
    @endforeach

    @endif
</x-institution.shell>

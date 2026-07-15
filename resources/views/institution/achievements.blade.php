@php
    // Real ro'yxat — App\Models\Achievement (Institution::achievements()). Ommaviy
    // profil sahifasida (/maktab/{id}) ham shu yerdan chiqadi — ADR-0002, Faza 2.
    $levelMeta = [
        'intl' => ['label' => 'Xalqaro', 'class' => 'intl'],
        'national' => ['label' => 'Respublika', 'class' => 'national'],
        'regional' => ['label' => 'Viloyat', 'class' => 'regional'],
        'city' => ['label' => 'Shahar', 'class' => 'city'],
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
        @if ($achievements->isEmpty())
            <div class="empty">
                <span class="empty-ico"><x-maktabgid.icon name="trophy" :width="26" :height="26" /></span>
                <p>Hali yutuq qo'shilmagan.</p>
            </div>
        @else
            <div class="idash-ach-table">
                @foreach ($achievements as $a)
                    <div class="idash-ach-row">
                        @if ($a->image_url)
                            <span class="idash-ach-ico" style="background-image:url('{{ $a->image_url }}');background-size:cover;background-position:center"></span>
                        @else
                            <span class="idash-ach-ico"><x-maktabgid.icon name="trophy" :width="24" :height="24" /></span>
                        @endif
                        <div class="idash-ach-main">
                            <b>{{ $a->title }}</b>
                            <span>
                                {{ collect([$a->student_name, $a->year ? "{$a->year}-yil" : null])->filter()->implode(' · ') }}
                                @if ($a->image_url)
                                    · <a href="{{ $a->image_url }}" target="_blank" rel="noopener">Sertifikat ko'rish</a>
                                @endif
                            </span>
                        </div>
                        @if ($a->type)
                            <span class="idash-pill-neutral">{{ $a->type }}</span>
                        @endif
                        <span class="idash-pill-level {{ $levelMeta[$a->level]['class'] ?? 'city' }}">{{ $levelMeta[$a->level]['label'] ?? $a->level }}</span>
                        <div class="idash-card-actions">
                            <button type="button" class="idash-lead-iconbtn" title="Tahrirlash" data-modal-open="edit-achievement-{{ $a->id }}"><x-maktabgid.icon name="edit" :width="14" :height="14" /></button>
                            <button type="button" class="idash-lead-iconbtn danger js-achievement-delete" data-achievement-id="{{ $a->id }}" title="O'chirish"><x-maktabgid.icon name="close" :width="14" :height="14" /></button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ===== "Yutuq qo'shish" modali — real POST /ajax/institution/me/achievements
         (multipart, ixtiyoriy sertifikat rasmi bilan) — ADR-0002, Faza 2. ===== --}}
    <x-maktabgid.modal-shell id="add-achievement-modal" :width="480">
        <div class="js-modal-body">
            <div class="modal-head">
                <h3>Yutuq qo'shish</h3>
            </div>

            <form class="form js-achievement-form" enctype="multipart/form-data">
                <div class="js-form-error" style="display:none;padding:10px 14px;background:#fdecec;color:#d4504e;border-radius:var(--r-md);font-size:13px;font-weight:700"></div>

                <x-maktabgid.field label="Yutuq / mukofot nomi" icon="trophy">
                    <input type="text" name="title" required placeholder="Matematika olimpiadasi — 1-o'rin" />
                </x-maktabgid.field>
                <div class="form-row2">
                    <x-maktabgid.field label="O'quvchi ismi" hint="ixtiyoriy" icon="user">
                        <input type="text" name="student_name" placeholder="Sardor Karimov" />
                    </x-maktabgid.field>
                    <x-maktabgid.field label="Yil" icon="cal">
                        <input type="number" name="year" min="2000" max="2100" value="{{ now()->year }}" />
                    </x-maktabgid.field>
                </div>
                <div class="form-row2">
                    <x-maktabgid.field label="Turi" hint="ixtiyoriy" icon="award">
                        <input type="text" name="type" placeholder="Olimpiada" />
                    </x-maktabgid.field>
                    <x-maktabgid.field label="Daraja" icon="badge">
                        <select name="level" required>
                            @foreach ($levelMeta as $key => $lvl)
                                <option value="{{ $key }}">{{ $lvl['label'] }}</option>
                            @endforeach
                        </select>
                    </x-maktabgid.field>
                </div>

                <label class="upload-slot" style="flex-direction:row;justify-content:center;padding:16px">
                    <input type="file" name="image" accept="image/*" hidden />
                    <x-maktabgid.icon name="upload" :width="18" :height="18" />
                    <span>Sertifikat / rasm yuklash (ixtiyoriy)</span>
                </label>

                <div style="display:flex;gap:10px;margin-top:4px">
                    <button class="btn btn-primary form-submit" type="submit" style="flex:1;justify-content:center">Saqlash</button>
                    <button class="btn btn-ghost js-modal-close" type="button">Bekor qilish</button>
                </div>
            </form>
        </div>
    </x-maktabgid.modal-shell>

    {{-- ===== "Yutuqni tahrirlash" modali — har bir yutuq uchun alohida, real
         PUT /ajax/institution/me/achievements/{id} ===== --}}
    @foreach ($achievements as $a)
        <x-maktabgid.modal-shell id="edit-achievement-{{ $a->id }}" :width="480">
            <div class="js-modal-body">
                <div class="modal-head">
                    <h3>Yutuqni tahrirlash</h3>
                </div>

                <form class="form js-achievement-form" enctype="multipart/form-data" data-achievement-id="{{ $a->id }}">
                    <div class="js-form-error" style="display:none;padding:10px 14px;background:#fdecec;color:#d4504e;border-radius:var(--r-md);font-size:13px;font-weight:700"></div>

                    <x-maktabgid.field label="Yutuq / mukofot nomi" icon="trophy">
                        <input type="text" name="title" value="{{ $a->title }}" required />
                    </x-maktabgid.field>
                    <div class="form-row2">
                        <x-maktabgid.field label="O'quvchi ismi" hint="ixtiyoriy" icon="user">
                            <input type="text" name="student_name" value="{{ $a->student_name }}" />
                        </x-maktabgid.field>
                        <x-maktabgid.field label="Yil" icon="cal">
                            <input type="number" name="year" min="2000" max="2100" value="{{ $a->year }}" />
                        </x-maktabgid.field>
                    </div>
                    <div class="form-row2">
                        <x-maktabgid.field label="Turi" hint="ixtiyoriy" icon="award">
                            <input type="text" name="type" value="{{ $a->type }}" />
                        </x-maktabgid.field>
                        <x-maktabgid.field label="Daraja" icon="badge">
                            <select name="level" required>
                                @foreach ($levelMeta as $key => $lvl)
                                    <option value="{{ $key }}" @selected($key === $a->level)>{{ $lvl['label'] }}</option>
                                @endforeach
                            </select>
                        </x-maktabgid.field>
                    </div>

                    <label class="upload-slot" style="flex-direction:row;justify-content:center;padding:16px">
                        <input type="file" name="image" accept="image/*" hidden />
                        <x-maktabgid.icon name="upload" :width="18" :height="18" />
                        <span>{{ $a->image_url ? 'Rasmni almashtirish' : 'Sertifikat / rasm yuklash' }} (ixtiyoriy)</span>
                    </label>

                    <div style="display:flex;gap:10px;margin-top:4px">
                        <button class="btn btn-primary form-submit" type="submit" style="flex:1;justify-content:center">Saqlash</button>
                        <button class="btn btn-ghost js-modal-close" type="button">Bekor qilish</button>
                    </div>
                </form>
            </div>
        </x-maktabgid.modal-shell>
    @endforeach

    @endif
</x-institution.shell>

@php($institution = $institution ?? null)

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <x-admin.input name="name" label="Nomi" :value="$institution?->name" required />

    <x-admin.select name="type" label="Turi" :value="$institution?->type" required :options="[
        'maktab' => 'Maktab',
        'bogcha' => 'Bog\'cha',
        'markaz' => 'Markaz',
        'mutaxassis' => 'Mutaxassis',
    ]" />

    <x-admin.select name="owner_user_id" label="Egasi (foydalanuvchi)" placeholder="— Tanlanmagan —"
        :value="$institution?->owner_user_id" :options="$owners->pluck('name', 'id')" />

    <x-admin.select name="district_id" label="Tuman" placeholder="— Tanlanmagan —"
        :value="$institution?->district_id" :options="$districts->pluck('name', 'id')" />

    <x-admin.input name="address" label="Manzil" :value="$institution?->address" />
    <x-admin.input name="lang" label="Ta'lim tili" :value="$institution?->lang" />

    <x-admin.input name="lat" label="Kenglik (lat)" type="number" :value="$institution?->lat" step="0.0000001" />
    <x-admin.input name="lng" label="Uzunlik (lng)" type="number" :value="$institution?->lng" step="0.0000001" />

    <x-admin.input name="monthly_price" label="Oylik narx (so'm)" type="number" :value="$institution?->monthly_price" />
    <x-admin.input name="grades" label="Sinflar / yosh guruhlari" :value="$institution?->grades" />

    <x-admin.input name="work_hours" label="Ish vaqti" :value="$institution?->work_hours" placeholder="08:00–18:00" />
    <x-admin.input name="badge" label="Belgi (badge)" :value="$institution?->badge" />

    <x-admin.input name="rating" label="Reyting (0–5)" type="number" step="0.1" :value="$institution?->rating" />
    <x-admin.input name="review_count" label="Sharhlar soni" type="number" :value="$institution?->review_count" />
</div>

<div class="mt-5">
    <x-admin.textarea name="about" label="Tavsif" :value="$institution?->about" rows="4" />
</div>

<div class="mt-5 flex flex-wrap gap-6">
    <x-admin.checkbox name="works_saturday" label="Shanba kuni ishlaydi" :checked="(bool) $institution?->works_saturday" />
    <x-admin.checkbox name="accepting" label="Hozir qabul qilmoqda" :checked="$institution ? (bool) $institution->accepting : true" />
</div>

<div class="mt-5">
    <p class="block text-sm font-medium text-slate-700 mb-2">Yo'nalishlar (kategoriyalar)</p>
    <div class="flex flex-wrap gap-4 rounded-lg border border-slate-200 p-4">
        @forelse ($specializations as $spec)
            @php($isChecked = $institution?->specializations?->pluck('id')->contains($spec->id))
            <label class="flex items-center gap-2 text-sm text-slate-700">
                <input type="checkbox" name="specializations[]" value="{{ $spec->id }}" @checked($isChecked)
                       class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                {{ $spec->label }}
            </label>
        @empty
            <p class="text-sm text-slate-500">Kategoriyalar hali qo'shilmagan.</p>
        @endforelse
    </div>
</div>

{{-- ==================================================================
     2026-07-15: Muassasa kabinetidagi "Muassasa profili" sahifasi bilan
     bir xil imkoniyat — /maktab/{id} ommaviy sahifasida ko'rinadigan
     barcha bo'lim shu yerdan (admin tomonidan ham) to'ldiriladi:
     qulayliklar, o'qituvchilar, yo'nalish/dastur, darslar, qabul
     bosqichlari, ko'rsatkichlar, narxlar. Gallereya/video va yutuqlar
     alohida sahifada (fayl yuklash uchun) — edit.blade.php'dagi
     havolalarga qarang.
     ================================================================== --}}

<div class="mt-8 pt-6 border-t border-slate-200">
    <p class="block text-sm font-medium text-slate-700 mb-2">Infratuzilma va qulayliklar <span class="text-slate-400 font-normal">(profil sahifasida chiqadi)</span></p>
    <div class="flex flex-wrap gap-4 rounded-lg border border-slate-200 p-4">
        @php($myFacilities = old('facilities', $institution?->facilities ?? []))
        @foreach ($facilityCatalog as $f)
            <label class="flex items-center gap-2 text-sm text-slate-700">
                <input type="checkbox" name="facilities[]" value="{{ $f['key'] }}" @checked(in_array($f['key'], $myFacilities, true))
                       class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                {{ $f['t'] }}
            </label>
        @endforeach
    </div>
</div>

<div class="mt-6">
    <p class="block text-sm font-medium text-slate-700 mb-2">Narxlar <span class="text-slate-400 font-normal">(har sinf/guruh uchun alohida — kataloqda eng arzoni ko'rsatiladi)</span></p>
    <div data-repeater="prices" data-repeater-next="{{ count($institution?->prices ?? []) }}">
        <div data-repeater-rows class="space-y-2">
            @forelse ($institution?->prices ?? [] as $i => $p)
                <div class="flex gap-2 items-center" data-repeater-row>
                    <input type="text" name="prices[{{ $i }}][grade]" value="{{ $p->grade }}" placeholder="1-4-sinf" class="flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                    <select name="prices[{{ $i }}][lang]" class="w-32 rounded-lg border border-slate-300 px-3 py-2 text-sm bg-white">
                        @foreach (["O'zbek", 'Rus', 'Ingliz'] as $langOpt)
                            <option @selected($p->lang === $langOpt)>{{ $langOpt }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="prices[{{ $i }}][price]" value="{{ $p->monthly_price }}" placeholder="4500000" class="w-36 rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                    <input type="text" name="prices[{{ $i }}][discount]" value="{{ $p->discount }}" placeholder="Chegirma" class="w-28 rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                    <button type="button" data-repeater-remove title="O'chirish"><x-admin.icon name="trash" class="w-4 h-4 text-rose-500" /></button>
                </div>
            @empty
            @endforelse
        </div>
        <template data-repeater-template>
            <div class="flex gap-2 items-center" data-repeater-row>
                <input type="text" name="prices[__I__][grade]" placeholder="1-4-sinf" class="flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                <select name="prices[__I__][lang]" class="w-32 rounded-lg border border-slate-300 px-3 py-2 text-sm bg-white">
                    <option>O'zbek</option>
                    <option>Rus</option>
                    <option>Ingliz</option>
                </select>
                <input type="number" name="prices[__I__][price]" placeholder="4500000" class="w-36 rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                <input type="text" name="prices[__I__][discount]" placeholder="Chegirma" class="w-28 rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                <button type="button" data-repeater-remove title="O'chirish"><x-admin.icon name="trash" class="w-4 h-4 text-rose-500" /></button>
            </div>
        </template>
        <button type="button" data-repeater-add class="mt-2 inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-700">
            <x-admin.icon name="plus" class="w-4 h-4" /> Sinf / guruh qo'shish
        </button>
    </div>
</div>

<div class="mt-6">
    <p class="block text-sm font-medium text-slate-700 mb-2">O'qituvchilar <span class="text-slate-400 font-normal">(profil sahifasidagi "Ustozlar" bo'limi)</span></p>
    @php($myTeachers = old('teachers', $institution?->teachers ?? []))
    <div data-repeater="teachers" data-repeater-next="{{ count($myTeachers) }}">
        <div data-repeater-rows class="space-y-2">
            @forelse ($myTeachers as $i => $t)
                <div class="flex gap-2 items-center" data-repeater-row>
                    <input type="text" name="teachers[{{ $i }}][n]" value="{{ $t['n'] ?? '' }}" placeholder="Ism familiya" class="flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                    <input type="text" name="teachers[{{ $i }}][role]" value="{{ $t['role'] ?? '' }}" placeholder="Fan / lavozim" class="flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                    <input type="text" name="teachers[{{ $i }}][exp]" value="{{ $t['exp'] ?? '' }}" placeholder="6 yil" class="w-28 rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                    <button type="button" data-repeater-remove title="O'chirish"><x-admin.icon name="trash" class="w-4 h-4 text-rose-500" /></button>
                </div>
            @empty
            @endforelse
        </div>
        <template data-repeater-template>
            <div class="flex gap-2 items-center" data-repeater-row>
                <input type="text" name="teachers[__I__][n]" placeholder="Ism familiya" class="flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                <input type="text" name="teachers[__I__][role]" placeholder="Fan / lavozim" class="flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                <input type="text" name="teachers[__I__][exp]" placeholder="6 yil" class="w-28 rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                <button type="button" data-repeater-remove title="O'chirish"><x-admin.icon name="trash" class="w-4 h-4 text-rose-500" /></button>
            </div>
        </template>
        <button type="button" data-repeater-add class="mt-2 inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-700">
            <x-admin.icon name="plus" class="w-4 h-4" /> O'qituvchi qo'shish
        </button>
    </div>
</div>

<div class="mt-6">
    <p class="block text-sm font-medium text-slate-700 mb-2">Yo'nalishlar va dastur <span class="text-slate-400 font-normal">(sarlavha + tavsif)</span></p>
    @php($myPrograms = old('programs', $institution?->programs ?? []))
    <div data-repeater="programs" data-repeater-next="{{ count($myPrograms) }}">
        <div data-repeater-rows class="space-y-2">
            @forelse ($myPrograms as $i => $p)
                <div class="flex gap-2 items-center" data-repeater-row>
                    <input type="text" name="programs[{{ $i }}][t]" value="{{ $p['t'] ?? '' }}" placeholder="Masalan, Cambridge dasturi" class="flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                    <input type="text" name="programs[{{ $i }}][d]" value="{{ $p['d'] ?? '' }}" placeholder="Xalqaro standart va sertifikat" class="flex-[1.4] rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                    <button type="button" data-repeater-remove title="O'chirish"><x-admin.icon name="trash" class="w-4 h-4 text-rose-500" /></button>
                </div>
            @empty
            @endforelse
        </div>
        <template data-repeater-template>
            <div class="flex gap-2 items-center" data-repeater-row>
                <input type="text" name="programs[__I__][t]" placeholder="Masalan, Cambridge dasturi" class="flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                <input type="text" name="programs[__I__][d]" placeholder="Xalqaro standart va sertifikat" class="flex-[1.4] rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                <button type="button" data-repeater-remove title="O'chirish"><x-admin.icon name="trash" class="w-4 h-4 text-rose-500" /></button>
            </div>
        </template>
        <button type="button" data-repeater-add class="mt-2 inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-700">
            <x-admin.icon name="plus" class="w-4 h-4" /> Yo'nalish qo'shish
        </button>
    </div>
</div>

<div class="mt-6">
    <p class="block text-sm font-medium text-slate-700 mb-2">O'quv jarayonidan lavhalar <span class="text-slate-400 font-normal">(har biri — bitta lavha nomi)</span></p>
    @php($myLessons = old('lessons', $institution?->lessons ?? []))
    <div data-repeater="lessons">
        <div data-repeater-rows class="space-y-2">
            @forelse ($myLessons as $l)
                <div class="flex gap-2 items-center" data-repeater-row>
                    <input type="text" name="lessons[]" value="{{ is_array($l) ? ($l['label'] ?? '') : $l }}" placeholder="Masalan, Matematika darsi" class="flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                    <button type="button" data-repeater-remove title="O'chirish"><x-admin.icon name="trash" class="w-4 h-4 text-rose-500" /></button>
                </div>
            @empty
            @endforelse
        </div>
        <template data-repeater-template>
            <div class="flex gap-2 items-center" data-repeater-row>
                <input type="text" name="lessons[]" placeholder="Masalan, Matematika darsi" class="flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                <button type="button" data-repeater-remove title="O'chirish"><x-admin.icon name="trash" class="w-4 h-4 text-rose-500" /></button>
            </div>
        </template>
        <button type="button" data-repeater-add class="mt-2 inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-700">
            <x-admin.icon name="plus" class="w-4 h-4" /> Lavha qo'shish
        </button>
    </div>
</div>

<div class="mt-6">
    <p class="block text-sm font-medium text-slate-700 mb-2">Qabul bosqichlari <span class="text-slate-400 font-normal">(sarlavha + tavsif)</span></p>
    @php($mySteps = old('admission_steps', $institution?->admission_steps ?? []))
    <div data-repeater="admission_steps" data-repeater-next="{{ count($mySteps) }}">
        <div data-repeater-rows class="space-y-2">
            @forelse ($mySteps as $i => $s)
                <div class="flex gap-2 items-center" data-repeater-row>
                    <input type="text" name="admission_steps[{{ $i }}][t]" value="{{ $s['t'] ?? '' }}" placeholder="Masalan, Ariza qoldirish" class="flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                    <input type="text" name="admission_steps[{{ $i }}][d]" value="{{ $s['d'] ?? '' }}" placeholder="Onlayn forma orqali ariza yuborasiz" class="flex-[1.4] rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                    <button type="button" data-repeater-remove title="O'chirish"><x-admin.icon name="trash" class="w-4 h-4 text-rose-500" /></button>
                </div>
            @empty
            @endforelse
        </div>
        <template data-repeater-template>
            <div class="flex gap-2 items-center" data-repeater-row>
                <input type="text" name="admission_steps[__I__][t]" placeholder="Masalan, Ariza qoldirish" class="flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                <input type="text" name="admission_steps[__I__][d]" placeholder="Onlayn forma orqali ariza yuborasiz" class="flex-[1.4] rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                <button type="button" data-repeater-remove title="O'chirish"><x-admin.icon name="trash" class="w-4 h-4 text-rose-500" /></button>
            </div>
        </template>
        <button type="button" data-repeater-add class="mt-2 inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-700">
            <x-admin.icon name="plus" class="w-4 h-4" /> Bosqich qo'shish
        </button>
    </div>
</div>

<div class="mt-6">
    <p class="block text-sm font-medium text-slate-700 mb-2">Ko'rsatkichlar <span class="text-slate-400 font-normal">(sarlavha ostida chiqadi — bo'sh qoldirilsa ko'rsatilmaydi)</span></p>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
        <x-admin.input name="stat_class_size" label="Bir sinfda / guruhda" :value="$institution?->stat_class_size" placeholder="16" />
        <x-admin.input name="stat_experience_years" label="Yillik tajriba" :value="$institution?->stat_experience_years" placeholder="12" />
        <x-admin.input name="stat_admission_rate" label="Qabul ko'rsatkichi" :value="$institution?->stat_admission_rate" placeholder="98%" />
        <x-admin.input name="stat_first_grade_seats" label="1-sinf joylari" :value="$institution?->stat_first_grade_seats" placeholder="24" />
    </div>
</div>

@once
    <script>
        // Diqqat: admin panelda Alpine.js yo'q — yuqoridagi "narxlar/o'qituvchilar/
        // yo'nalishlar/darslar/qabul bosqichlari" qatorlarini qo'shish/o'chirish shu
        // umumiy vanilla JS "repeater" orqali ishlaydi (icon-picker komponentidagi
        // bilan bir xil uslub). Har bir qator ko'p maydonli bo'lsa (masalan
        // teachers[IDX][n]/[role]/[exp]) HTML forma __I__ o'rniga haqiqiy raqamli
        // indeks qo'yiladi — bo'sh qavs "foo[][a]&foo[][b]" PHP'da ikkita alohida
        // qatorga bo'linib ketadi, shu sababli aniq indeks shart.
        document.addEventListener('click', function (e) {
            var addBtn = e.target.closest('[data-repeater-add]');
            if (addBtn) {
                var wrap = addBtn.closest('[data-repeater]');
                var tpl = wrap.querySelector('[data-repeater-template]');
                var rows = wrap.querySelector('[data-repeater-rows]');
                var next = parseInt(wrap.getAttribute('data-repeater-next') || '0', 10);
                var html = tpl.innerHTML.split('__I__').join(String(next));
                var holder = document.createElement('div');
                holder.innerHTML = html.trim();
                rows.appendChild(holder.firstElementChild);
                wrap.setAttribute('data-repeater-next', String(next + 1));
                return;
            }

            var removeBtn = e.target.closest('[data-repeater-remove]');
            if (removeBtn) {
                var row = removeBtn.closest('[data-repeater-row]');
                if (row) row.remove();
            }
        });
    </script>
@endonce

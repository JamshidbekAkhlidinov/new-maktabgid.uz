@php($institution = $institution ?? null)

{{-- Uch tillilik (2026-08-06) — quyidagi maydonlar har bir til uchun alohida
     saqlanadi ({"uz":..,"ru":..,"en":..}); saytda foydalanuvchi tanlagan tilga
     mos matn avtomatik ko'rsatiladi (bo'sh bo'lsa o'zbekchaga tushadi). --}}
<div class="rounded-lg border border-slate-200 p-4 mb-5">
    <p class="text-sm font-medium text-slate-700 mb-3">Nomi <span class="text-slate-400 font-normal">(uch tilda)</span></p>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <x-admin.input name="name_uz" label="O'zbekcha" :value="old('name_uz', $institution?->getTranslation('name', 'uz'))" required />
        <x-admin.input name="name_ru" label="Ruscha" :value="old('name_ru', $institution?->getTranslation('name', 'ru'))" />
        <x-admin.input name="name_en" label="Inglizcha" :value="old('name_en', $institution?->getTranslation('name', 'en'))" />
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
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

    <x-admin.input name="lang" label="Ta'lim tili" :value="$institution?->lang" />

    <x-admin.input name="lat" label="Kenglik (lat)" type="number" :value="$institution?->lat" step="0.0000001" />
    <x-admin.input name="lng" label="Uzunlik (lng)" type="number" :value="$institution?->lng" step="0.0000001" />

    <x-admin.input name="monthly_price" label="Oylik narx (so'm)" type="number" :value="$institution?->monthly_price" />

    <x-admin.input name="rating" label="Reyting (0–5)" type="number" step="0.1" :value="$institution?->rating" />
    <x-admin.input name="review_count" label="Sharhlar soni" type="number" :value="$institution?->review_count" />

    <x-admin.input name="location_url" label="Xarita havolasi" :value="$institution?->location_url" placeholder="https://yandex.uz/maps/..." />

    <x-admin.input name="slug" label="Slug (URL manzili)" :value="$institution?->slug" placeholder="masalan-maktab-nomi" />
</div>

<div class="rounded-lg border border-slate-200 p-4 mt-5">
    <p class="text-sm font-medium text-slate-700 mb-3">Manzil <span class="text-slate-400 font-normal">(uch tilda)</span></p>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <x-admin.input name="address_uz" label="O'zbekcha" :value="old('address_uz', $institution?->getTranslation('address', 'uz'))" />
        <x-admin.input name="address_ru" label="Ruscha" :value="old('address_ru', $institution?->getTranslation('address', 'ru'))" />
        <x-admin.input name="address_en" label="Inglizcha" :value="old('address_en', $institution?->getTranslation('address', 'en'))" />
    </div>
</div>

<div class="rounded-lg border border-slate-200 p-4 mt-5">
    <p class="text-sm font-medium text-slate-700 mb-3">Mo'ljal <span class="text-slate-400 font-normal">(uch tilda, masalan "Farxod bozori yonida")</span></p>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <x-admin.input name="refer_point_uz" label="O'zbekcha" :value="old('refer_point_uz', $institution?->getTranslation('refer_point', 'uz'))" />
        <x-admin.input name="refer_point_ru" label="Ruscha" :value="old('refer_point_ru', $institution?->getTranslation('refer_point', 'ru'))" />
        <x-admin.input name="refer_point_en" label="Inglizcha" :value="old('refer_point_en', $institution?->getTranslation('refer_point', 'en'))" />
    </div>
</div>

<div class="rounded-lg border border-slate-200 p-4 mt-5">
    <p class="text-sm font-medium text-slate-700 mb-3">Sinflar / yosh guruhlari <span class="text-slate-400 font-normal">(uch tilda)</span></p>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <x-admin.input name="grades_uz" label="O'zbekcha" :value="old('grades_uz', $institution?->getTranslation('grades', 'uz'))" />
        <x-admin.input name="grades_ru" label="Ruscha" :value="old('grades_ru', $institution?->getTranslation('grades', 'ru'))" />
        <x-admin.input name="grades_en" label="Inglizcha" :value="old('grades_en', $institution?->getTranslation('grades', 'en'))" />
    </div>
</div>

<div class="rounded-lg border border-slate-200 p-4 mt-5">
    <p class="text-sm font-medium text-slate-700 mb-3">Ish vaqti <span class="text-slate-400 font-normal">(uch tilda)</span></p>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <x-admin.input name="work_hours_uz" label="O'zbekcha" :value="old('work_hours_uz', $institution?->getTranslation('work_hours', 'uz'))" placeholder="08:00–18:00" />
        <x-admin.input name="work_hours_ru" label="Ruscha" :value="old('work_hours_ru', $institution?->getTranslation('work_hours', 'ru'))" />
        <x-admin.input name="work_hours_en" label="Inglizcha" :value="old('work_hours_en', $institution?->getTranslation('work_hours', 'en'))" />
    </div>
</div>

<div class="rounded-lg border border-slate-200 p-4 mt-5">
    <p class="text-sm font-medium text-slate-700 mb-3">Belgi (badge) <span class="text-slate-400 font-normal">(uch tilda, ixtiyoriy)</span></p>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <x-admin.input name="badge_uz" label="O'zbekcha" :value="old('badge_uz', $institution?->getTranslation('badge', 'uz'))" />
        <x-admin.input name="badge_ru" label="Ruscha" :value="old('badge_ru', $institution?->getTranslation('badge', 'ru'))" />
        <x-admin.input name="badge_en" label="Inglizcha" :value="old('badge_en', $institution?->getTranslation('badge', 'en'))" />
    </div>
</div>

<div class="rounded-lg border border-slate-200 p-4 mt-5">
    <p class="text-sm font-medium text-slate-700 mb-3">Tavsif <span class="text-slate-400 font-normal">(uch tilda)</span></p>
    <div class="grid grid-cols-1 gap-4">
        <x-admin.textarea name="about_uz" label="O'zbekcha" :value="old('about_uz', $institution?->getTranslation('about', 'uz'))" rows="4" />
        <x-admin.textarea name="about_ru" label="Ruscha" :value="old('about_ru', $institution?->getTranslation('about', 'ru'))" rows="4" />
        <x-admin.textarea name="about_en" label="Inglizcha" :value="old('about_en', $institution?->getTranslation('about', 'en'))" rows="4" />
    </div>
</div>

<div class="mt-6">
    <p class="block text-sm font-medium text-slate-700 mb-2">Telefon raqamlari</p>
    @php($myPhones = old('phone_numbers', $institution?->phone_numbers ?? []))
    <div data-repeater="phone_numbers">
        <div data-repeater-rows class="space-y-2">
            @forelse ($myPhones as $p)
                <div class="flex gap-2 items-center" data-repeater-row>
                    <input type="text" name="phone_numbers[]" value="{{ $p }}" placeholder="+998 90 123-45-67" class="flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                    <button type="button" data-repeater-remove title="O'chirish"><x-admin.icon name="trash" class="w-4 h-4 text-rose-500" /></button>
                </div>
            @empty
            @endforelse
        </div>
        <template data-repeater-template>
            <div class="flex gap-2 items-center" data-repeater-row>
                <input type="text" name="phone_numbers[]" placeholder="+998 90 123-45-67" class="flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                <button type="button" data-repeater-remove title="O'chirish"><x-admin.icon name="trash" class="w-4 h-4 text-rose-500" /></button>
            </div>
        </template>
        <button type="button" data-repeater-add class="mt-2 inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-700">
            <x-admin.icon name="plus" class="w-4 h-4" /> Telefon qo'shish
        </button>
    </div>
</div>

<div class="mt-6">
    <p class="block text-sm font-medium text-slate-700 mb-2">Ijtimoiy tarmoqlar</p>
    @php($mySocial = $institution?->social_links ?? [])
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <x-admin.input name="social_links[instagram]" label="Instagram" :value="$mySocial['instagram'] ?? null" placeholder="https://instagram.com/..." />
        <x-admin.input name="social_links[facebook]" label="Facebook" :value="$mySocial['facebook'] ?? null" placeholder="https://facebook.com/..." />
        <x-admin.input name="social_links[telegram]" label="Telegram" :value="$mySocial['telegram'] ?? null" placeholder="https://t.me/..." />
        <x-admin.input name="social_links[website]" label="Veb-sayt" :value="$mySocial['website'] ?? null" placeholder="https://..." />
    </div>
</div>

<div class="mt-5 flex flex-wrap gap-6">
    <x-admin.checkbox name="works_saturday" label="Shanba kuni ishlaydi" :checked="(bool) $institution?->works_saturday" />
    <x-admin.checkbox name="accepting" label="Hozir qabul qilmoqda" :checked="$institution ? (bool) $institution->accepting : true" />
    <x-admin.checkbox name="is_active" label="Sahifa faol (saytda ko'rinadi)" :checked="$institution ? (bool) $institution->is_active : true" />
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
     bir xil imkoniyat — /{slug} ommaviy sahifasida ko'rinadigan
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

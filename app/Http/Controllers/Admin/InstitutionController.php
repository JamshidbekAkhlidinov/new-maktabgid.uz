<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Institution;
use App\Models\Specialization;
use App\Models\User;
use App\Support\MaktabgidData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InstitutionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:institutions.view', only: ['index', 'show']),
            new Middleware('permission:institutions.create', only: ['create', 'store']),
            new Middleware('permission:institutions.update', only: ['edit', 'update']),
            new Middleware('permission:institutions.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $institutions = Institution::query()
            ->with(['district', 'owner'])
            // 'name' endi JSON (uch tillilik) — matn qidiruvi xom JSON qatori ichida
            // ishlaydi (uz/ru/en, qaysi tilda kiritilgan bo'lsa ham topadi).
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', "%{$request->string('q')}%"))
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->string('type')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.institutions.index', compact('institutions'));
    }

    public function create(): View
    {
        return view('admin.institutions.create', $this->formData());
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);

        $institution = Institution::create($this->prepare($data));
        $institution->specializations()->sync($data['specializations'] ?? []);
        $this->syncPrices($institution, $data['prices'] ?? []);

        return redirect()->route('admin.institutions.index')->with('status', 'Tashkilot yaratildi.');
    }

    public function edit(Institution $institution): View
    {
        $institution->load(['specializations', 'prices']);

        return view('admin.institutions.edit', $this->formData($institution));
    }

    public function update(Request $request, Institution $institution): RedirectResponse
    {
        $data = $this->validateData($request, $institution);

        $institution->update($this->prepare($data));
        $institution->specializations()->sync($data['specializations'] ?? []);
        $this->syncPrices($institution, $data['prices'] ?? []);

        return redirect()->route('admin.institutions.edit', $institution)->with('status', 'Tashkilot yangilandi.');
    }

    public function destroy(Institution $institution): RedirectResponse
    {
        $institution->delete();

        return redirect()->route('admin.institutions.index')->with('status', 'Tashkilot o\'chirildi.');
    }

    /** @return array<string, mixed> */
    private function formData(?Institution $institution = null): array
    {
        return [
            'institution' => $institution,
            'districts' => District::orderBy('name')->get(),
            // 'label' JSON (uch tillilik) — DB darajasida saralanmaydi, xotirada saralaymiz.
            'specializations' => Specialization::all()->sortBy(fn (Specialization $s) => $s->label)->values(),
            'owners' => User::whereIn('role', [User::ROLE_INSTITUTION, User::ROLE_ADMIN])->orderBy('name')->get(),
            'facilityCatalog' => MaktabgidData::facilityCatalog(),
        ];
    }

    /** @return array<string, mixed> */
    private function validateData(Request $request, ?Institution $institution = null): array
    {
        return $request->validate([
            // Uch tillilik (2026-08-06) — har biri _uz/_ru/_en shaklida keladi,
            // prepare() ularni {"uz":..,"ru":..,"en":..} massiviga yig'ib beradi.
            // O'zbekcha har doim majburiy (fallback zanjirining boshlanishi), ru/en ixtiyoriy.
            'name_uz' => ['required', 'string', 'max:255'],
            'name_ru' => ['nullable', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'about_uz' => ['nullable', 'string'],
            'about_ru' => ['nullable', 'string'],
            'about_en' => ['nullable', 'string'],
            'address_uz' => ['nullable', 'string', 'max:255'],
            'address_ru' => ['nullable', 'string', 'max:255'],
            'address_en' => ['nullable', 'string', 'max:255'],
            'grades_uz' => ['nullable', 'string', 'max:100'],
            'grades_ru' => ['nullable', 'string', 'max:100'],
            'grades_en' => ['nullable', 'string', 'max:100'],
            'work_hours_uz' => ['nullable', 'string', 'max:100'],
            'work_hours_ru' => ['nullable', 'string', 'max:100'],
            'work_hours_en' => ['nullable', 'string', 'max:100'],
            'badge_uz' => ['nullable', 'string', 'max:100'],
            'badge_ru' => ['nullable', 'string', 'max:100'],
            'badge_en' => ['nullable', 'string', 'max:100'],
            'refer_point_uz' => ['nullable', 'string', 'max:255'],
            'refer_point_ru' => ['nullable', 'string', 'max:255'],
            'refer_point_en' => ['nullable', 'string', 'max:255'],

            'type' => ['required', 'string', 'in:maktab,bogcha,markaz,mutaxassis'],
            'owner_user_id' => ['nullable', 'exists:users,id'],
            'district_id' => ['nullable', 'exists:districts,id'],
            'lang' => ['nullable', 'string', 'max:100'],
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lng' => ['nullable', 'numeric', 'between:-180,180'],
            'monthly_price' => ['nullable', 'integer', 'min:0'],
            'works_saturday' => ['nullable', 'boolean'],
            'accepting' => ['nullable', 'boolean'],
            'rating' => ['nullable', 'numeric', 'between:0,5'],
            'review_count' => ['nullable', 'integer', 'min:0'],
            'specializations' => ['nullable', 'array'],
            'specializations.*' => ['exists:specializations,id'],

            // Eski (Yii2) `telegram_object`dan import qilinadigan maydonlar (2026-08-06,
            // LegacyInstitutionSeeder) — admin panelda ham qo'lda tahrirlanadi.
            'location_url' => ['nullable', 'string', 'max:1000'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('institutions', 'slug')->ignore($institution)],
            'is_active' => ['nullable', 'boolean'],

            'phone_numbers' => ['nullable', 'array'],
            'phone_numbers.*' => ['nullable', 'string', 'max:50'],

            'social_links' => ['nullable', 'array'],
            'social_links.instagram' => ['nullable', 'string', 'max:255'],
            'social_links.facebook' => ['nullable', 'string', 'max:255'],
            'social_links.telegram' => ['nullable', 'string', 'max:255'],
            'social_links.website' => ['nullable', 'string', 'max:255'],

            // Institution kabinetidagi "Muassasa profili" bilan bir xil imkoniyat (2026-07-15):
            // qulayliklar, o'qituvchilar, dastur, darslar, qabul bosqichlari, ko'rsatkichlar, narxlar.
            'facilities' => ['nullable', 'array'],
            'facilities.*' => ['string'],

            'teachers' => ['nullable', 'array'],
            'teachers.*.n' => ['nullable', 'string', 'max:255'],
            'teachers.*.role' => ['nullable', 'string', 'max:255'],
            'teachers.*.exp' => ['nullable', 'string', 'max:100'],

            'programs' => ['nullable', 'array'],
            'programs.*.t' => ['nullable', 'string', 'max:255'],
            'programs.*.d' => ['nullable', 'string', 'max:500'],

            'lessons' => ['nullable', 'array'],
            'lessons.*' => ['nullable', 'string', 'max:255'],

            'admission_steps' => ['nullable', 'array'],
            'admission_steps.*.t' => ['nullable', 'string', 'max:255'],
            'admission_steps.*.d' => ['nullable', 'string', 'max:500'],

            'stat_class_size' => ['nullable', 'string', 'max:50'],
            'stat_experience_years' => ['nullable', 'string', 'max:50'],
            'stat_admission_rate' => ['nullable', 'string', 'max:50'],
            'stat_first_grade_seats' => ['nullable', 'string', 'max:50'],

            'prices' => ['nullable', 'array'],
            'prices.*.grade' => ['nullable', 'string', 'max:100'],
            'prices.*.lang' => ['nullable', 'string', 'max:100'],
            'prices.*.price' => ['nullable', 'integer', 'min:0'],
            'prices.*.discount' => ['nullable', 'string', 'max:100'],
        ]);
    }

    /** @return array<string, mixed> */
    private function prepare(array $data): array
    {
        // Uch tillilik (2026-08-06): forma'dan kelgan name_uz/name_ru/name_en kabi
        // yassi maydonlarni {"uz":..,"ru":..,"en":..} massiviga yig'amiz (bo'sh
        // qiymatlar tashlab yuboriladi). Institution modelidagi 'array' cast +
        // HasTranslatable trait shu formatni to'g'ridan-to'g'ri qabul qiladi.
        foreach (['name', 'about', 'address', 'grades', 'work_hours', 'badge', 'refer_point'] as $field) {
            $data[$field] = collect(['uz', 'ru', 'en'])
                ->mapWithKeys(fn ($locale) => [$locale => trim((string) ($data["{$field}_{$locale}"] ?? ''))])
                ->filter(fn ($v) => $v !== '')
                ->all();
            unset($data["{$field}_uz"], $data["{$field}_ru"], $data["{$field}_en"]);
        }

        $data['works_saturday'] = (bool) ($data['works_saturday'] ?? false);
        $data['accepting'] = (bool) ($data['accepting'] ?? false);
        $data['is_active'] = (bool) ($data['is_active'] ?? false);
        // 'rating'/'review_count' ustunlari bazada NOT NULL (default 0) — forma bo'sh
        // qoldirilsa, ConvertEmptyStringsToNull "" ni null'ga aylantiradi va shu null
        // to'g'ridan-to'g'ri create()/update()'ga tushib, NOT NULL cheklovini buzadi.
        $data['rating'] = $data['rating'] ?? 0;
        $data['review_count'] = $data['review_count'] ?? 0;

        $data['facilities'] = array_values(array_filter($data['facilities'] ?? []));

        $data['slug'] = filled($data['slug'] ?? null) ? $data['slug'] : null;

        $data['phone_numbers'] = collect($data['phone_numbers'] ?? [])
            ->map(fn ($p) => trim((string) $p))
            ->filter()
            ->values()->all() ?: null;

        $data['social_links'] = collect($data['social_links'] ?? [])
            ->map(fn ($v) => trim((string) $v))
            ->filter()
            ->all() ?: null;

        $data['teachers'] = collect($data['teachers'] ?? [])
            ->filter(fn ($t) => filled($t['n'] ?? null) || filled($t['role'] ?? null) || filled($t['exp'] ?? null))
            ->map(fn ($t) => ['n' => $t['n'] ?? '', 'role' => $t['role'] ?? '', 'exp' => $t['exp'] ?? ''])
            ->values()->all();

        $data['programs'] = collect($data['programs'] ?? [])
            ->filter(fn ($p) => filled($p['t'] ?? null) || filled($p['d'] ?? null))
            ->map(fn ($p) => ['t' => $p['t'] ?? '', 'd' => $p['d'] ?? ''])
            ->values()->all();

        $data['lessons'] = collect($data['lessons'] ?? [])
            ->map(fn ($l) => trim((string) $l))
            ->filter(fn ($l) => $l !== '')
            ->values()->all();

        $data['admission_steps'] = collect($data['admission_steps'] ?? [])
            ->filter(fn ($s) => filled($s['t'] ?? null) || filled($s['d'] ?? null))
            ->map(fn ($s) => ['t' => $s['t'] ?? '', 'd' => $s['d'] ?? ''])
            ->values()->all();

        // 'prices' Institution jadvalining ustuni emas — alohida InstitutionPrice jadvaliga
        // syncPrices() orqali yoziladi (Institution\ProfileController bilan bir xil qoida).
        unset($data['specializations'], $data['prices']);

        return $data;
    }

    /**
     * "Narxlar" — Institution\ProfileController::syncPrices() bilan bir xil qoida: har
     * saqlashda butun ro'yxat almashtiriladi, so'ng institutions.monthly_price shular
     * ichidan ENG KICHIGI bilan yangilanadi. Bo'sh qatorlar (grade/price kiritilmagan)
     * e'tiborga olinmaydi.
     */
    private function syncPrices(Institution $institution, array $rows): void
    {
        $rows = collect($rows)
            ->filter(fn ($r) => filled($r['grade'] ?? null) && filled($r['price'] ?? null))
            ->values();

        $institution->prices()->delete();

        foreach ($rows as $i => $row) {
            $institution->prices()->create([
                'grade' => $row['grade'],
                'lang' => $row['lang'] ?? null,
                'monthly_price' => $row['price'],
                'discount' => $row['discount'] ?? null,
                'sort_order' => $i,
            ]);
        }

        if ($rows->isNotEmpty()) {
            $institution->monthly_price = $institution->prices()->min('monthly_price');
            $institution->save();
        }
    }
}

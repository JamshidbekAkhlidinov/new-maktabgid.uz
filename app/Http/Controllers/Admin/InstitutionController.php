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
            'specializations' => Specialization::orderBy('label')->get(),
            'owners' => User::whereIn('role', [User::ROLE_INSTITUTION, User::ROLE_ADMIN])->orderBy('name')->get(),
            'facilityCatalog' => MaktabgidData::facilityCatalog(),
        ];
    }

    /** @return array<string, mixed> */
    private function validateData(Request $request, ?Institution $institution = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:maktab,bogcha,markaz,mutaxassis'],
            'owner_user_id' => ['nullable', 'exists:users,id'],
            'district_id' => ['nullable', 'exists:districts,id'],
            'about' => ['nullable', 'string'],
            'lang' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:255'],
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lng' => ['nullable', 'numeric', 'between:-180,180'],
            'monthly_price' => ['nullable', 'integer', 'min:0'],
            'grades' => ['nullable', 'string', 'max:100'],
            'work_hours' => ['nullable', 'string', 'max:100'],
            'works_saturday' => ['nullable', 'boolean'],
            'accepting' => ['nullable', 'boolean'],
            'badge' => ['nullable', 'string', 'max:100'],
            'rating' => ['nullable', 'numeric', 'between:0,5'],
            'review_count' => ['nullable', 'integer', 'min:0'],
            'specializations' => ['nullable', 'array'],
            'specializations.*' => ['exists:specializations,id'],

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
        $data['works_saturday'] = (bool) ($data['works_saturday'] ?? false);
        $data['accepting'] = (bool) ($data['accepting'] ?? false);
        // 'rating'/'review_count' ustunlari bazada NOT NULL (default 0) — forma bo'sh
        // qoldirilsa, ConvertEmptyStringsToNull "" ni null'ga aylantiradi va shu null
        // to'g'ridan-to'g'ri create()/update()'ga tushib, NOT NULL cheklovini buzadi.
        $data['rating'] = $data['rating'] ?? 0;
        $data['review_count'] = $data['review_count'] ?? 0;

        $data['facilities'] = array_values(array_filter($data['facilities'] ?? []));

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

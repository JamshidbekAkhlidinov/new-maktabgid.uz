<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Requests\Institution\UpdateProfileRequest;
use App\Http\Resources\InstitutionResource;
use App\Models\District;
use App\Models\Institution;
use App\Models\Specialization;
use App\Services\Geo\TwoGisGeocodingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $institution = $this->activeInstitutionOrFail($request, ['district', 'specializations', 'media']);

        $this->authorize('view', $institution);

        return response()->json(['institution' => new InstitutionResource($institution)]);
    }

    public function update(UpdateProfileRequest $request, TwoGisGeocodingService $geocoder): JsonResponse
    {
        $institution = $this->activeInstitutionOrFail($request);

        $this->authorize('update', $institution);

        $data = $request->validated();

        $district = isset($data['district'])
            ? District::firstOrCreate(['name' => $data['district']])
            : null;

        $institution->fill(array_filter([
            'name' => $data['name'] ?? null,
            'type' => $data['type'] ?? null,
            'about' => $data['about'] ?? null,
            'lang' => $data['lang'] ?? null,
            'district_id' => $district?->id,
            'address' => $data['address'] ?? null,
            'grades' => $data['grades'] ?? null,
            'work_hours' => $data['work_hours'] ?? null,
        ], fn ($v) => $v !== null));

        if (array_key_exists('works_saturday', $data)) {
            $institution->works_saturday = (bool) $data['works_saturday'];
        }

        if (isset($data['facilities'])) {
            $institution->facilities = array_values($data['facilities']);
        }

        if (array_key_exists('teachers_text', $data)) {
            $institution->teachers = self::parsePipeLines($data['teachers_text'], ['n', 'role', 'exp']);
        }

        if (array_key_exists('programs_text', $data)) {
            $institution->programs = self::parsePipeLines($data['programs_text'], ['t', 'd']);
        }

        if (array_key_exists('lessons_text', $data)) {
            $institution->lessons = self::parsePlainLines($data['lessons_text']);
        }

        if (array_key_exists('videos_text', $data)) {
            $institution->videos = self::parsePipeLines($data['videos_text'], ['title', 'dur', 'sub']);
        }

        if (array_key_exists('admission_steps_text', $data)) {
            $institution->admission_steps = self::parsePipeLines($data['admission_steps_text'], ['t', 'd']);
        }

        foreach (['stat_class_size', 'stat_experience_years', 'stat_admission_rate', 'stat_first_grade_seats'] as $statField) {
            if (array_key_exists($statField, $data)) {
                $institution->{$statField} = $data[$statField];
            }
        }

        // Manzil o'zgargan yoki koordinata hali yo'q bo'lsa — 2GIS orqali avtomatik geocoding
        if ($institution->isDirty('address') && filled($institution->address)) {
            $coords = $geocoder->geocode($institution->address, $institution->district?->name);
            if ($coords) {
                $institution->lat = $coords['lat'];
                $institution->lng = $coords['lng'];
            }
        }

        $institution->save();

        if (isset($data['specializations'])) {
            $ids = Specialization::whereIn('key', $data['specializations'])->pluck('id');
            $institution->specializations()->sync($ids);
        }

        if (isset($data['prices'])) {
            $this->syncPrices($institution, $data['prices']);
        }

        return response()->json([
            'institution' => new InstitutionResource($institution->fresh(['district', 'specializations', 'media', 'prices'])),
        ]);
    }

    /**
     * "Narxlar" bo'limi — har bir saqlashda butun ro'yxat almashtiriladi (o'chirish +
     * qayta yaratish, xuddi shu bo'limning o'zi kabi "eng kichik narx" tez-tez o'zgarib
     * turmasligi kutiladi). Saqlangach `institutions.monthly_price` shular ichidan ENG
     * KICHIGI bilan yangilanadi — katalog ro'yxati/filtri/kartochkasi shu ustunni
     * ishlatib kelgan, hech qanday boshqa joyni o'zgartirish shart emas (2026-07-15).
     */
    private function syncPrices(Institution $institution, array $rows): void
    {
        $institution->prices()->delete();

        foreach (array_values($rows) as $i => $row) {
            $institution->prices()->create([
                'grade' => $row['grade'],
                'lang' => $row['lang'] ?? null,
                'monthly_price' => $row['price'],
                'discount' => $row['discount'] ?? null,
                'sort_order' => $i,
            ]);
        }

        $institution->monthly_price = $institution->prices()->min('monthly_price');
        $institution->save();
    }

    /**
     * Har bir qator "qism1 | qism2 | ..." formatida — muassasa kabinetidagi
     * oddiy textarea inputlarini strukturaga aylantiradi (masalan: "Aziz Karimov | Matematika | 10 yil").
     */
    private static function parsePipeLines(?string $text, array $keys): array
    {
        if (blank($text)) {
            return [];
        }

        $rows = [];
        foreach (preg_split('/\r\n|\r|\n/', $text) as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            $parts = array_map('trim', explode('|', $line));
            $row = [];
            foreach ($keys as $i => $key) {
                $row[$key] = $parts[$i] ?? '';
            }
            $rows[] = $row;
        }

        return $rows;
    }

    /** Har bir qator — bitta oddiy matn elementi (masalan: dars lavhasi nomi). */
    private static function parsePlainLines(?string $text): array
    {
        if (blank($text)) {
            return [];
        }

        return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $text)), fn ($l) => $l !== ''));
    }
}

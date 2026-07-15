<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Models\District;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Muassasa kabineti — "Yangi muassasa qo'shish" va tepadagi tashkilot select'i
 * (bir foydalanuvchi bir nechta filialga ega bo'lishi, 2026-07-15). Yangi
 * qo'shilgan/almashtirilgan muassasa darhol "faol" bo'ladi — qolgan barcha
 * kabinet bo'limlari shu ID bilan ishlay boshlaydi (ResolvesActiveInstitution).
 */
class OrganizationController extends Controller
{
    private const TYPES = ['maktab', 'bogcha', 'markaz', 'mutaxassis'];

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(self::TYPES)],
            'district' => ['sometimes', 'nullable', 'string', 'max:150'],
        ]);

        $district = filled($data['district'] ?? null)
            ? District::firstOrCreate(['name' => $data['district']])
            : null;

        $institution = $request->user()->institutions()->create([
            'name' => $data['name'],
            'type' => $data['type'],
            'district_id' => $district?->id,
            'accepting' => true,
            'works_saturday' => false,
            'rating' => 0,
            'review_count' => 0,
        ]);

        $request->session()->put('active_institution_id', $institution->id);

        return response()->json(['institution' => $institution], 201);
    }

    public function activate(Request $request): JsonResponse
    {
        $data = $request->validate([
            'institution_id' => ['required', 'integer'],
        ]);

        $institution = $this->setActiveInstitution($request, (int) $data['institution_id']);

        return response()->json(['institution' => $institution]);
    }
}

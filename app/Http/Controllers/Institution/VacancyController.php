<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Models\Vacancy;
use App\Models\VacancyApplication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Muassasa kabineti — o'z e'loni CRUD'i (real Vacancy). To'lov tizimi
 * (Payme/Click) hali ulanmagani uchun joylashtirish hozircha bepul —
 * ADR-0002 rejasiga ko'ra keyinchalik to'lov bosqichi qo'shiladi.
 */
class VacancyController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $institution = $this->activeInstitutionOrFail($request);
        $this->authorize('update', $institution);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'employment_type' => ['required', 'string', Rule::in(['full', 'part', 'hourly'])],
            'salary_range' => ['nullable', 'string', 'max:100'],
            'requirements' => ['nullable', 'string', 'max:2000'],
            'expires_at' => ['nullable', 'date'],
        ]);

        $vacancy = $institution->vacancies()->create($data + [
            'org_name' => $institution->name,
            'posted_by_user_id' => $request->user()->id,
        ]);

        return response()->json(['vacancy' => $vacancy], 201);
    }

    public function destroy(Request $request, Vacancy $vacancy): JsonResponse
    {
        $institution = $this->activeInstitutionOrFail($request);

        abort_unless($vacancy->institution_id === $institution->id, 403);

        $vacancy->delete();

        return response()->json(['ok' => true]);
    }

    /** Nomzod arizasi holatini o'zgartirish (qabul/rad) — real VacancyApplication (ADR-0002, Faza 2). */
    public function updateApplicationStatus(Request $request, VacancyApplication $application): JsonResponse
    {
        $institution = $this->activeInstitutionOrFail($request);

        abort_unless($application->vacancy->institution_id === $institution->id, 403);

        $data = $request->validate([
            'status' => ['required', 'string', Rule::in(['pending', 'accepted', 'rejected'])],
        ]);

        $application->update($data);

        return response()->json(['application' => $application]);
    }
}

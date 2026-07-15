<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Models\Vacancy;
use App\Models\VacancyApplication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Muassasa kabineti — o'z e'lonini o'chirish (real Vacancy). Yaratish/tahrirlash
 * hali pullik-demo bo'lib qoladi (ADR-0002), lekin o'chirish oddiy va xavfsiz
 * bo'lgani uchun real ulandi.
 */
class VacancyController extends Controller
{
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

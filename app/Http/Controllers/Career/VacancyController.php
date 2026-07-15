<?php

namespace App\Http\Controllers\Career;

use App\Http\Controllers\Controller;
use App\Http\Requests\Career\VacancyStoreRequest;
use App\Models\Vacancy;
use Illuminate\Http\JsonResponse;

/**
 * Ommaviy `/vakansiyalar` sahifasidagi "Vakansiya e'lon qilish" modali — real
 * yozish yo'li (backend.md §6: `POST /ajax/vacancies (auth)`). Muassasa kabineti
 * ichidagi "Vakansiya ochish" formasi bundan farqli o'laroq ataylab pullik-demo
 * bo'lib qoladi (ADR-0002) — bu ikkalasi hozircha ataylab ikkita boshqa oqim.
 */
class VacancyController extends Controller
{
    public function store(VacancyStoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = $request->user();
        $institution = $user->isInstitution() ? $user->institution()->first() : null;

        $vacancy = Vacancy::create([
            'title' => $data['title'],
            'institution_id' => $institution?->id,
            // Foydalanuvchi muassasa bo'lsa haqiqiy nomi ustun turadi (forma matnidan
            // ko'ra ishonchli manba); aks holda forma orqali kiritilgan nom saqlanadi.
            'org_name' => $institution?->name ?? $data['org'],
            'salary_range' => $data['salary_range'] ?? null,
            'employment_type' => $data['employment_type'],
            'posted_by_user_id' => $user->id,
        ]);

        return response()->json(['vacancy' => $vacancy], 201);
    }
}

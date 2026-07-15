<?php

namespace App\Http\Controllers\Career;

use App\Http\Controllers\Controller;
use App\Http\Requests\Career\VacancyApplicationStoreRequest;
use App\Models\Vacancy;
use Illuminate\Http\JsonResponse;

/**
 * Vakansiyaga ariza yuborish (backend.md andozasidagi ApplicationController bilan
 * bir xil qoida: mehmon ham yuborishi mumkin, ro'yxatdan o'tgan ustoz yuborsa
 * haqiqiy hisobiga bog'lanadi — ADR-0002, Faza 2).
 */
class VacancyApplicationController extends Controller
{
    public function store(VacancyApplicationStoreRequest $request, Vacancy $vacancy): JsonResponse
    {
        $data = $request->validated();

        $application = $vacancy->applications()->create([
            'teacher_user_id' => $request->user()?->id,
            'full_name' => $data['full_name'],
            'phone' => $data['phone'],
            'note' => $data['note'] ?? null,
            'status' => 'pending',
        ]);

        return response()->json(['application' => $application], 201);
    }
}

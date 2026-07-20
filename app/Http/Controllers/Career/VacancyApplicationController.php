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

        $resumeDisk = config('filesystems.media_disk', 'public');
        $resumePath = null;
        $resumeOriginalName = null;

        if ($request->hasFile('resume')) {
            $file = $request->file('resume');
            $resumePath = $file->store('vacancy-applications/'.$vacancy->id, $resumeDisk);
            $resumeOriginalName = $file->getClientOriginalName();
        }

        $application = $vacancy->applications()->create([
            'teacher_user_id' => $request->user()?->id,
            'full_name' => $data['full_name'],
            'phone' => $data['phone'],
            'note' => $data['note'] ?? null,
            'resume_path' => $resumePath,
            'resume_disk' => $resumePath ? $resumeDisk : null,
            'resume_original_name' => $resumeOriginalName,
            'status' => 'pending',
        ]);

        return response()->json(['application' => $application], 201);
    }
}

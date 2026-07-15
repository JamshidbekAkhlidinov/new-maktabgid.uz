<?php

namespace App\Http\Controllers\Career;

use App\Http\Controllers\Controller;
use App\Http\Requests\Career\ResumeStoreRequest;
use App\Models\Resume;
use Illuminate\Http\JsonResponse;

/**
 * Ommaviy `/vakansiyalar` sahifasidagi "Rezyume joylash" modali — real yozish
 * yo'li (backend.md §6: `POST /ajax/resumes (auth)`). Ustoz kabineti ichidagi
 * "Yangi rezyume" formasi bundan farqli o'laroq ataylab pullik-demo bo'lib
 * qoladi (ADR-0002) — bu ikkalasi hozircha ataylab ikkita boshqa oqim.
 */
class ResumeController extends Controller
{
    public function store(ResumeStoreRequest $request): JsonResponse
    {
        $data = $request->validated();

        $resume = Resume::create([
            'full_name' => $data['full_name'],
            'role_title' => $data['role_title'],
            'experience' => $data['experience'],
            'specialization_key' => $data['specialization_key'] ?? null,
            'salary_expectation' => $data['salary_expectation'] ?? null,
            'owner_user_id' => $request->user()->id,
        ]);

        return response()->json(['resume' => $resume], 201);
    }
}

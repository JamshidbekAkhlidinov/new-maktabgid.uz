<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\Specialization;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class VacancyController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:vacancies.view', only: ['index', 'show']),
            new Middleware('permission:vacancies.create', only: ['create', 'store']),
            new Middleware('permission:vacancies.update', only: ['edit', 'update']),
            new Middleware('permission:vacancies.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $vacancies = Vacancy::query()
            ->with('institution')
            ->when($request->filled('q'), fn ($q) => $q->where('title', 'like', "%{$request->string('q')}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.vacancies.index', compact('vacancies'));
    }

    public function create(): View
    {
        return view('admin.vacancies.create', $this->formData());
    }

    public function store(Request $request): RedirectResponse
    {
        Vacancy::create($this->validateData($request));

        return redirect()->route('admin.vacancies.index')->with('status', 'Vakansiya yaratildi.');
    }

    public function edit(Vacancy $vacancy): View
    {
        return view('admin.vacancies.edit', $this->formData($vacancy));
    }

    public function update(Request $request, Vacancy $vacancy): RedirectResponse
    {
        $vacancy->update($this->validateData($request, $vacancy));

        return redirect()->route('admin.vacancies.index')->with('status', 'Vakansiya yangilandi.');
    }

    public function destroy(Vacancy $vacancy): RedirectResponse
    {
        $vacancy->delete();

        return redirect()->route('admin.vacancies.index')->with('status', 'Vakansiya o\'chirildi.');
    }

    /** @return array<string, mixed> */
    private function formData(?Vacancy $vacancy = null): array
    {
        return [
            'vacancy' => $vacancy,
            // 'name'/'label' JSON (uch tillilik) — xotirada, joriy tildagi matn bo'yicha saralanadi.
            'institutions' => Institution::all()->sortBy(fn ($i) => $i->name)->values(),
            'specializations' => Specialization::all()->sortBy(fn ($s) => $s->label)->values(),
            'users' => User::orderBy('name')->get(),
        ];
    }

    /** @return array<string, mixed> */
    private function validateData(Request $request, ?Vacancy $vacancy = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'institution_id' => ['nullable', 'exists:institutions,id'],
            'org_name' => ['required', 'string', 'max:255'],
            'salary_range' => ['nullable', 'string', 'max:100'],
            'employment_type' => ['required', 'string', 'in:full,part,hourly'],
            'specialization_key' => ['nullable', 'string', 'max:100'],
            'posted_by_user_id' => ['nullable', 'exists:users,id'],
            'expires_at' => ['nullable', 'date'],
        ]);
    }
}

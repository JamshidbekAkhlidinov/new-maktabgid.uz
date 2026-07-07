<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Resume;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class ResumeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:resumes.view', only: ['index']),
            new Middleware('permission:resumes.create', only: ['create', 'store']),
            new Middleware('permission:resumes.update', only: ['edit', 'update']),
            new Middleware('permission:resumes.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $resumes = Resume::query()
            ->with(['district', 'owner'])
            ->when($request->filled('q'), fn ($q) => $q->where('full_name', 'like', "%{$request->string('q')}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.resumes.index', compact('resumes'));
    }

    public function create(): View
    {
        return view('admin.resumes.create', $this->formData());
    }

    public function store(Request $request): RedirectResponse
    {
        Resume::create($this->validateData($request));

        return redirect()->route('admin.resumes.index')->with('status', 'Rezyume yaratildi.');
    }

    public function edit(Resume $resume): View
    {
        return view('admin.resumes.edit', $this->formData($resume));
    }

    public function update(Request $request, Resume $resume): RedirectResponse
    {
        $resume->update($this->validateData($request));

        return redirect()->route('admin.resumes.index')->with('status', 'Rezyume yangilandi.');
    }

    public function destroy(Resume $resume): RedirectResponse
    {
        $resume->delete();

        return redirect()->route('admin.resumes.index')->with('status', 'Rezyume o\'chirildi.');
    }

    /** @return array<string, mixed> */
    private function formData(?Resume $resume = null): array
    {
        return [
            'resume' => $resume,
            'districts' => District::orderBy('name')->get(),
            'specializations' => Specialization::orderBy('label')->get(),
            'users' => User::orderBy('name')->get(),
        ];
    }

    /** @return array<string, mixed> */
    private function validateData(Request $request): array
    {
        return $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'role_title' => ['required', 'string', 'max:255'],
            'experience' => ['required', 'string', 'max:100'],
            'specialization_key' => ['nullable', 'string', 'max:100'],
            'salary_expectation' => ['nullable', 'string', 'max:100'],
            'district_id' => ['nullable', 'exists:districts,id'],
            'languages' => ['nullable', 'string', 'max:255'],
            'owner_user_id' => ['nullable', 'exists:users,id'],
        ]);
    }
}

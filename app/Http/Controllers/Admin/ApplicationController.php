<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Institution;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

/** Arizalar ("formlar") — ota-onalar tashkilotlarga yuboradigan ekskursiya/joylashtirish arizalari. */
class ApplicationController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:applications.view', only: ['index', 'show']),
            new Middleware('permission:applications.create', only: ['create', 'store']),
            new Middleware('permission:applications.update', only: ['edit', 'update']),
            new Middleware('permission:applications.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $applications = Application::query()
            ->with(['institution', 'parent'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('q'), fn ($q) => $q->where('child_name', 'like', "%{$request->string('q')}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.applications.index', compact('applications'));
    }

    public function create(): View
    {
        return view('admin.applications.create', $this->formData());
    }

    public function store(Request $request): RedirectResponse
    {
        Application::create($this->validateData($request));

        return redirect()->route('admin.applications.index')->with('status', 'Ariza yaratildi.');
    }

    public function edit(Application $application): View
    {
        return view('admin.applications.edit', $this->formData($application));
    }

    public function update(Request $request, Application $application): RedirectResponse
    {
        $application->update($this->validateData($request, $application));

        return redirect()->route('admin.applications.index')->with('status', 'Ariza yangilandi.');
    }

    public function destroy(Application $application): RedirectResponse
    {
        $application->delete();

        return redirect()->route('admin.applications.index')->with('status', 'Ariza o\'chirildi.');
    }

    /** @return array<string, mixed> */
    private function formData(?Application $application = null): array
    {
        return [
            'application' => $application,
            'institutions' => Institution::orderBy('name')->get(),
            'parents' => User::where('role', User::ROLE_PARENT)->orderBy('name')->get(),
        ];
    }

    /** @return array<string, mixed> */
    private function validateData(Request $request, ?Application $application = null): array
    {
        return $request->validate([
            'institution_id' => ['required', 'exists:institutions,id'],
            'parent_user_id' => ['nullable', 'exists:users,id'],
            'type' => ['required', 'string', 'in:excursion,enrollment'],
            'child_name' => ['required', 'string', 'max:255'],
            'child_birth_date' => ['nullable', 'date'],
            'child_age' => ['nullable', 'integer', 'min:0', 'max:25'],
            'current_grade' => ['nullable', 'string', 'max:50'],
            'target_grade' => ['nullable', 'string', 'max:50'],
            'previous_school' => ['nullable', 'string', 'max:255'],
            'parent_name' => ['required', 'string', 'max:255'],
            'parent_phone' => ['required', 'string', 'max:32'],
            'preferred_start' => ['nullable', 'string', 'max:100'],
            'scheduled_at' => ['nullable', 'date'],
            'note' => ['nullable', 'string'],
            'status' => ['required', 'string', 'in:pending,confirmed,rejected,completed'],
        ]);
    }
}

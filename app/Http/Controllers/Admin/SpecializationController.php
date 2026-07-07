<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specialization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/** Kategoriyalar — muassasa/vakansiya yo'nalishlari (masalan: "Ingliz tili", "Matematika"). */
class SpecializationController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:specializations.view', only: ['index']),
            new Middleware('permission:specializations.create', only: ['create', 'store']),
            new Middleware('permission:specializations.update', only: ['edit', 'update']),
            new Middleware('permission:specializations.delete', only: ['destroy']),
        ];
    }

    public function index(): View
    {
        $specializations = Specialization::orderBy('label')->paginate(20);

        return view('admin.specializations.index', compact('specializations'));
    }

    public function create(): View
    {
        return view('admin.specializations.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Specialization::create($this->validateData($request));

        return redirect()->route('admin.specializations.index')->with('status', 'Kategoriya yaratildi.');
    }

    public function edit(Specialization $specialization): View
    {
        return view('admin.specializations.edit', compact('specialization'));
    }

    public function update(Request $request, Specialization $specialization): RedirectResponse
    {
        $specialization->update($this->validateData($request, $specialization));

        return redirect()->route('admin.specializations.index')->with('status', 'Kategoriya yangilandi.');
    }

    public function destroy(Specialization $specialization): RedirectResponse
    {
        $specialization->delete();

        return redirect()->route('admin.specializations.index')->with('status', 'Kategoriya o\'chirildi.');
    }

    /** @return array<string, mixed> */
    private function validateData(Request $request, ?Specialization $specialization = null): array
    {
        return $request->validate([
            'key' => ['required', 'string', 'max:100', Rule::unique('specializations', 'key')->ignore($specialization?->id)],
            'label' => ['required', 'string', 'max:255'],
            'icon' => ['required', 'string', 'max:100'],
        ]);
    }
}

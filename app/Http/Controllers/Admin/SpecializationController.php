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
        // 'label' endi JSON (uch tillilik) — DB darajasida saralab bo'lmaydi,
        // shu sababli joriy tildagi (fallback bilan) matn bo'yicha xotirada saralaymiz.
        $specializations = Specialization::all()->sortBy(fn (Specialization $s) => $s->label)->values();

        return view('admin.specializations.index', compact('specializations'));
    }

    public function create(): View
    {
        return view('admin.specializations.create');
    }

    public function store(Request $request): RedirectResponse
    {
        [$data, $labels] = $this->validateData($request);

        $specialization = Specialization::create($data);
        $specialization->setTranslations('label', $labels)->save();

        return redirect()->route('admin.specializations.index')->with('status', 'Kategoriya yaratildi.');
    }

    public function edit(Specialization $specialization): View
    {
        return view('admin.specializations.edit', compact('specialization'));
    }

    public function update(Request $request, Specialization $specialization): RedirectResponse
    {
        [$data, $labels] = $this->validateData($request, $specialization);

        $specialization->update($data);
        $specialization->setTranslations('label', $labels)->save();

        return redirect()->route('admin.specializations.index')->with('status', 'Kategoriya yangilandi.');
    }

    public function destroy(Specialization $specialization): RedirectResponse
    {
        $specialization->delete();

        return redirect()->route('admin.specializations.index')->with('status', 'Kategoriya o\'chirildi.');
    }

    /** @return array{0: array<string, mixed>, 1: array<string, string>} [DB uchun boshqa maydonlar, label tarjimalari] */
    private function validateData(Request $request, ?Specialization $specialization = null): array
    {
        $validated = $request->validate([
            'key' => ['required', 'string', 'max:100', Rule::unique('specializations', 'key')->ignore($specialization?->id)],
            'label_uz' => ['required', 'string', 'max:255'],
            'label_ru' => ['nullable', 'string', 'max:255'],
            'label_en' => ['nullable', 'string', 'max:255'],
            'icon' => ['required', 'string', 'max:100'],
        ]);

        $labels = [
            'uz' => $validated['label_uz'],
            'ru' => $validated['label_ru'] ?? null,
            'en' => $validated['label_en'] ?? null,
        ];

        return [
            ['key' => $validated['key'], 'icon' => $validated['icon']],
            $labels,
        ];
    }
}

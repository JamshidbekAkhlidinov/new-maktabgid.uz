<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstitutionType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/** Muassasa turlari — "Asosiy ma'lumotlar > Turi" maydonining variantlari (masalan: "Maktab", "Bog'cha"). */
class InstitutionTypeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:institution-types.view', only: ['index']),
            new Middleware('permission:institution-types.create', only: ['create', 'store']),
            new Middleware('permission:institution-types.update', only: ['edit', 'update']),
            new Middleware('permission:institution-types.delete', only: ['destroy']),
        ];
    }

    public function index(): View
    {
        // 'label' endi JSON (uch tillilik) — DB darajasida saralab bo'lmaydi,
        // shu sababli joriy tildagi (fallback bilan) matn bo'yicha xotirada saralaymiz.
        $institutionTypes = InstitutionType::all()->sortBy(fn (InstitutionType $t) => $t->label)->values();

        return view('admin.institution-types.index', compact('institutionTypes'));
    }

    public function create(): View
    {
        return view('admin.institution-types.create');
    }

    public function store(Request $request): RedirectResponse
    {
        [$data, $labels] = $this->validateData($request);

        $institutionType = InstitutionType::create($data);
        $institutionType->setTranslations('label', $labels)->save();

        return redirect()->route('admin.institution-types.index')->with('status', 'Muassasa turi yaratildi.');
    }

    public function edit(InstitutionType $institutionType): View
    {
        return view('admin.institution-types.edit', compact('institutionType'));
    }

    public function update(Request $request, InstitutionType $institutionType): RedirectResponse
    {
        [$data, $labels] = $this->validateData($request, $institutionType);

        $institutionType->update($data);
        $institutionType->setTranslations('label', $labels)->save();

        return redirect()->route('admin.institution-types.index')->with('status', 'Muassasa turi yangilandi.');
    }

    public function destroy(InstitutionType $institutionType): RedirectResponse
    {
        $institutionType->delete();

        return redirect()->route('admin.institution-types.index')->with('status', 'Muassasa turi o\'chirildi.');
    }

    /** @return array{0: array<string, mixed>, 1: array<string, string>} [DB uchun boshqa maydonlar, label tarjimalari] */
    private function validateData(Request $request, ?InstitutionType $institutionType = null): array
    {
        $validated = $request->validate([
            'key' => ['required', 'string', 'max:100', Rule::unique('institution_types', 'key')->ignore($institutionType?->id)],
            'label_uz' => ['required', 'string', 'max:255'],
            'label_ru' => ['nullable', 'string', 'max:255'],
            'label_en' => ['nullable', 'string', 'max:255'],
            'icon' => ['required', 'string', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $labels = [
            'uz' => $validated['label_uz'],
            'ru' => $validated['label_ru'] ?? null,
            'en' => $validated['label_en'] ?? null,
        ];

        return [
            ['key' => $validated['key'], 'icon' => $validated['icon'], 'is_active' => (bool) ($validated['is_active'] ?? false)],
            $labels,
        ];
    }
}

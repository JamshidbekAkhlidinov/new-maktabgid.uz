<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DistrictController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:districts.view', only: ['index']),
            new Middleware('permission:districts.create', only: ['create', 'store']),
            new Middleware('permission:districts.update', only: ['edit', 'update']),
            new Middleware('permission:districts.delete', only: ['destroy']),
        ];
    }

    public function index(): View
    {
        $districts = District::withCount('institutions')->orderBy('name')->paginate(20);

        return view('admin.districts.index', compact('districts'));
    }

    public function create(): View
    {
        return view('admin.districts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        District::create($this->validateData($request));

        return redirect()->route('admin.districts.index')->with('status', 'Tuman yaratildi.');
    }

    public function edit(District $district): View
    {
        return view('admin.districts.edit', compact('district'));
    }

    public function update(Request $request, District $district): RedirectResponse
    {
        $district->update($this->validateData($request, $district));

        return redirect()->route('admin.districts.index')->with('status', 'Tuman yangilandi.');
    }

    public function destroy(District $district): RedirectResponse
    {
        $district->delete();

        return redirect()->route('admin.districts.index')->with('status', 'Tuman o\'chirildi.');
    }

    /** @return array<string, mixed> */
    private function validateData(Request $request, ?District $district = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('districts', 'name')->ignore($district?->id)],
        ]);
    }
}

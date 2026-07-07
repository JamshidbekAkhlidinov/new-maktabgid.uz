<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Dinamik rollar — har bir admin har xil huquqlar to'plamiga ega rolga ega bo'lishi mumkin.
 * "Super Admin" rolini o'chirib bo'lmaydi va u har doim barcha huquqlarga ega bo'ladi
 * (AppServiceProvider'dagi Gate::before shuni kafolatlaydi, bu yerda esa syncPermissions
 * bilan qo'shimcha mustahkamlanadi).
 */
class RoleController extends Controller implements HasMiddleware
{
    public const PROTECTED_ROLE = 'Super Admin';

    public static function middleware(): array
    {
        return [
            new Middleware('permission:roles.view', only: ['index', 'show']),
            new Middleware('permission:roles.create', only: ['create', 'store']),
            new Middleware('permission:roles.update', only: ['edit', 'update']),
            new Middleware('permission:roles.delete', only: ['destroy']),
        ];
    }

    public function index(): View
    {
        $roles = Role::withCount(['permissions', 'users'])->orderBy('name')->paginate(20);

        return view('admin.roles.index', compact('roles'));
    }

    public function create(): View
    {
        return view('admin.roles.create', [
            'permissionGroups' => $this->groupedPermissions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ]);

        $role = Role::create(['name' => $data['name'], 'guard_name' => 'web']);
        $role->syncPermissions($data['permissions'] ?? []);

        return redirect()->route('admin.roles.index')->with('status', 'Rol yaratildi.');
    }

    public function edit(Role $role): View
    {
        $role->load('permissions');

        return view('admin.roles.edit', [
            'role' => $role,
            'permissionGroups' => $this->groupedPermissions(),
        ]);
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->ignore($role->id)],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ]);

        if ($role->name === self::PROTECTED_ROLE) {
            // Super Admin nomi va huquqlari o'zgartirilmaydi — u har doim to'liq huquqli bo'lishi shart.
            $role->syncPermissions(Permission::all());
        } else {
            $role->update(['name' => $data['name']]);
            $role->syncPermissions($data['permissions'] ?? []);
        }

        return redirect()->route('admin.roles.index')->with('status', 'Rol yangilandi.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        if ($role->name === self::PROTECTED_ROLE) {
            return back()->withErrors(['role' => 'Super Admin rolini o\'chirib bo\'lmaydi.']);
        }

        $role->delete();

        return redirect()->route('admin.roles.index')->with('status', 'Rol o\'chirildi.');
    }

    /** @return \Illuminate\Support\Collection<string, \Illuminate\Support\Collection> */
    private function groupedPermissions()
    {
        return Permission::orderBy('name')->get()->groupBy(function (Permission $permission) {
            return str($permission->name)->before('.');
        });
    }
}

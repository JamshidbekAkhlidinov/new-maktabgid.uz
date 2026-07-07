<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;

/**
 * Huquqlar (permissions) — faqat ko'rish uchun. Ro'yxat kodda (PermissionSeeder)
 * belgilanadi, chunki har bir huquq controller ichidagi haqiqiy middleware bilan
 * bog'liq; runtime'da ixtiyoriy nom qo'shish tekshiruvlarni chalkashtirib yuboradi.
 */
class PermissionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:permissions.view'),
        ];
    }

    public function index(): View
    {
        $groups = Permission::orderBy('name')->get()->groupBy(function (Permission $permission) {
            return str($permission->name)->before('.');
        });

        return view('admin.permissions.index', compact('groups'));
    }
}

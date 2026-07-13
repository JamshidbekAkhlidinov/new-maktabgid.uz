<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Tizimdagi aniq va tiniq 4 ta rol (Spatie\Permission) shu yerda tuziladi:
 *   - Super Admin        — barcha huquqlarga ega (AppServiceProvider'dagi Gate::before
 *                           orqali ham kafolatlanadi, bu yerda syncPermissions bilan
 *                           qo'shimcha mustahkamlanadi).
 *   - Institution Admin   — muassasa kabineti (role=institution — "manager")
 *   - Teacher              — ustoz kabineti (role=teacher)
 *   - Parent                — ota-ona kabineti (role=parent)
 *
 * Huquqlar ikki guruhga bo'linadi:
 *   1) RESOURCES — /admin panelidagi resurslar uchun view/create/update/delete
 *      (faqat Super Admin foydalanadi, chunki /admin darvozasi — EnsureAdmin —
 *      hozircha faqat Super Admin'ga ochiq).
 *   2) CABINET_RESOURCES — saytdagi shaxsiy kabinetlar (institution/teacher/parent)
 *      uchun view/manage — shu orqali har bir rol o'z kabinetiga tegishli huquqqa
 *      ega bo'ladi (Register*Controller'lar ro'yxatdan o'tishda shu rolni avtomatik
 *      biriktiradi — RegisterParentController va h.k.).
 */
class PermissionSeeder extends Seeder
{
    /** Rol nomlari — butun ilova bo'ylab shu konstantalar orqali ishlatiladi. */
    public const ROLE_SUPER_ADMIN = 'Super Admin';

    public const ROLE_INSTITUTION_ADMIN = 'Institution Admin';

    public const ROLE_TEACHER = 'Teacher';

    public const ROLE_PARENT = 'Parent';

    /** @var array<int, string> */
    public const RESOURCES = [
        'users',
        'roles',
        'permissions',
        'institutions',
        'vacancies',
        'applications',
        'specializations',
        'districts',
        'news',
        'articles',
        'reviews',
        'resumes',
    ];

    /** @var array<int, string> */
    public const ACTIONS = ['view', 'create', 'update', 'delete'];

    /** Saytdagi shaxsiy kabinetlar — har biri uchun view/manage huquqi. */
    /** @var array<int, string> */
    public const CABINET_RESOURCES = [
        'institution-cabinet',
        'teacher-cabinet',
        'parent-cabinet',
    ];

    /** @var array<int, string> */
    public const CABINET_ACTIONS = ['view', 'manage'];

    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::firstOrCreate(['name' => 'dashboard.view', 'guard_name' => 'web']);

        foreach (self::RESOURCES as $resource) {
            foreach (self::ACTIONS as $action) {
                Permission::firstOrCreate([
                    'name' => "{$resource}.{$action}",
                    'guard_name' => 'web',
                ]);
            }
        }

        foreach (self::CABINET_RESOURCES as $resource) {
            foreach (self::CABINET_ACTIONS as $action) {
                Permission::firstOrCreate([
                    'name' => "{$resource}.{$action}",
                    'guard_name' => 'web',
                ]);
            }
        }

        // 'permissions' resursi uchun faqat ko'rish beriladi — huquqlar ro'yxati
        // kodda (shu seeder) belgilanadi, runtime'da yaratib/o'chirib bo'lmaydi.
        Permission::whereIn('name', ['permissions.create', 'permissions.update', 'permissions.delete'])->delete();

        // Loyihada aniq va tiniq 4 ta roldan tashqarisi bo'lmasligi kerak — eski
        // "Moderator" (agar mavjud bo'lsa) o'chiriladi.
        Role::where('name', 'Moderator')->delete();

        $superAdmin = Role::firstOrCreate(['name' => self::ROLE_SUPER_ADMIN, 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());

        $institutionAdmin = Role::firstOrCreate(['name' => self::ROLE_INSTITUTION_ADMIN, 'guard_name' => 'web']);
        $institutionAdmin->syncPermissions(Permission::whereIn('name', [
            'institution-cabinet.view',
            'institution-cabinet.manage',
        ])->get());

        $teacher = Role::firstOrCreate(['name' => self::ROLE_TEACHER, 'guard_name' => 'web']);
        $teacher->syncPermissions(Permission::whereIn('name', [
            'teacher-cabinet.view',
            'teacher-cabinet.manage',
        ])->get());

        $parent = Role::firstOrCreate(['name' => self::ROLE_PARENT, 'guard_name' => 'web']);
        $parent->syncPermissions(Permission::whereIn('name', [
            'parent-cabinet.view',
            'parent-cabinet.manage',
        ])->get());

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}

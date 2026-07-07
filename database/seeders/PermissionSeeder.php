<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Admin panelidagi har bir resurs uchun view/create/update/delete huquqlari.
 * Rollar (Super Admin, Moderator, ...) shu huquqlardan dinamik tuziladi —
 * yangi rol admin panelning o'zidan (Rollar bo'limi) yaratilishi mumkin.
 */
class PermissionSeeder extends Seeder
{
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

        // 'permissions' resursi uchun faqat ko'rish beriladi — huquqlar ro'yxati
        // kodda (shu seeder) belgilanadi, runtime'da yaratib/o'chirib bo'lmaydi.
        Permission::whereIn('name', ['permissions.create', 'permissions.update', 'permissions.delete'])->delete();

        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());

        $moderator = Role::firstOrCreate(['name' => 'Moderator', 'guard_name' => 'web']);
        $moderator->syncPermissions(Permission::whereIn('name', [
            'dashboard.view',
            'institutions.view', 'institutions.update',
            'vacancies.view', 'vacancies.create', 'vacancies.update', 'vacancies.delete',
            'applications.view', 'applications.update',
            'reviews.view', 'reviews.delete',
            'specializations.view',
            'districts.view',
        ])->get());

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}

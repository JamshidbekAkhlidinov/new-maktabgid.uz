<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DistrictSeeder::class,
            SpecializationSeeder::class,
            // PermissionSeeder — rollar (Super Admin/Institution Admin/Teacher/Parent)
            // UserSeeder'dan OLDIN ishga tushishi shart, chunki UserSeeder demo
            // foydalanuvchilarga shu rollarni syncRoles() bilan biriktiradi.
            PermissionSeeder::class,
            UserSeeder::class,
            AdminUserSeeder::class,
            InstitutionSeeder::class,
            ReviewSeeder::class,
            ForumSeeder::class,
            ContentSeeder::class,
            CareerSeeder::class,
        ]);
    }
}

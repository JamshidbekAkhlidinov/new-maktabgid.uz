<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Super admin uchun alohida seed.
 *
 * Ishga tushirish:
 *   php artisan db:seed --class=AdminUserSeeder
 *
 * Kirish (login /admin/login — email + parol, yoki "Google bilan kirish"
 * agar shu email Google hisobga bog'langan bo'lsa):
 *   Email:  superadmin@maktabgid.uz
 *   Parol:  SuperAdmin!2026
 *
 * Ishga tushirilgandan so'ng birinchi navbatda parolni admin panelning
 * "Foydalanuvchilar" bo'limidan almashtiring.
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Rollar/permissionlar mavjud bo'lishini kafolatlaydi (idempotent — firstOrCreate).
        $this->call(PermissionSeeder::class);

        $admin = User::updateOrCreate(
            ['phone' => '+998900000000'],
            [
                'name' => 'Super Admin',
                'email' => 'admin@ustadev.uz',
                'role' => User::ROLE_ADMIN,
                'password' => Hash::make('admin@ustadev.uz'),
                'phone_verified_at' => now(),
            ]
        );

        $admin->syncRoles(['Super Admin']);

        $this->command?->info('Super admin tayyor: admin@ustadev.uz/admin@ustadev.uz');
    }
}

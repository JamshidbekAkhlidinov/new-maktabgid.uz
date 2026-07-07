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
 * Kirish (login /admin/login):
 *   Telefon:  +998900000001
 *   Parol:    SuperAdmin!2026
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
            ['phone' => '+998900000001'],
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@maktabgid.uz',
                'role' => User::ROLE_ADMIN,
                'password' => Hash::make('SuperAdmin!2026'),
                'phone_verified_at' => now(),
            ]
        );

        $admin->syncRoles(['Super Admin']);

        $this->command?->info('Super admin tayyor: +998900000001 / SuperAdmin!2026');
    }
}

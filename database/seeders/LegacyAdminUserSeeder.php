<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Eski (Yii2) CMS foydalanuvchilarini (`user` + `user_profile`, 5 ta) admin
 * sifatida import qiladi. Eski `password_hash` bcrypt formatda ($2y$...) —
 * User::password cast('hashed') Hash::isHashed() orqali buni aniqlab qayta
 * hash qilmaydi, ya'ni eski parol bilan kirish davom etadi.
 *
 * PermissionSeeder oldin ishga tushgan bo'lishi kerak (DatabaseSeeder tartibi) —
 * "Super Admin" roli shu yerda syncRoles() bilan biriktiriladi.
 */
class LegacyAdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = $this->loadJson(__DIR__.'/legacy_fixtures/user.json');
        $profiles = collect($this->loadJson(__DIR__.'/legacy_fixtures/user_profile.json'))->keyBy('user_id');

        if (empty($users)) {
            $this->command?->warn('LegacyAdminUserSeeder: legacy_fixtures/user.json topilmadi — o\'tkazib yuborildi.');

            return;
        }

        $imported = 0;

        foreach ($users as $u) {
            $legacyId = (int) $u['id'];
            /** @var array<string, mixed> $profile */
            $profile = $profiles->get($legacyId) ?? [];

            $name = trim(implode(' ', array_filter([$profile['firstname'] ?? null, $profile['lastname'] ?? null])));
            if ($name === '') {
                $name = (string) ($u['username'] ?? "Admin #{$legacyId}");
            }

            $email = filled($u['email'] ?? null) ? $u['email'] : "legacy-admin-{$legacyId}@maktabgid.uz";
            $phone = sprintf('+998900000%03d', $legacyId);

            $admin = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'phone' => $phone,
                    'role' => User::ROLE_ADMIN,
                    // Eski bcrypt hash to'g'ridan-to'g'ri saqlanadi — Hash::isHashed() buni
                    // aniqlab qayta hash qilmaydi (Illuminate 'hashed' cast xatti-harakati),
                    // ya'ni foydalanuvchi eski paroli bilan kira oladi.
                    'password' => (string) $u['password_hash'],
                ]
            );

            // email_verified_at/phone_verified_at User'ning #[Fillable(...)] ro'yxatida yo'q
            // (faqat OTP/email-tasdiqlash oqimi orqali to'ldiriladi) — mass-assignment ularni
            // jimgina e'tiborsiz qoldiradi, shu sababli forceFill bilan alohida belgilanadi.
            $admin->forceFill([
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
            ])->save();

            $admin->syncRoles([PermissionSeeder::ROLE_SUPER_ADMIN]);

            $imported++;
        }

        $this->command?->info("LegacyAdminUserSeeder: {$imported} ta eski admin foydalanuvchi import qilindi (eski parollari ishlaydi).");
    }

    /** @return array<int, array<string, mixed>> */
    private function loadJson(string $path): array
    {
        if (! is_file($path)) {
            return [];
        }

        $decoded = json_decode((string) file_get_contents($path), true);

        return is_array($decoded) ? $decoded : [];
    }
}

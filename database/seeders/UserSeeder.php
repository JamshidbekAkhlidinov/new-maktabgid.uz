<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Demo foydalanuvchilar — tizimdagi 3 ta oddiy rolning har biridan 3 tadan
 * (Parent/Institution Admin/Teacher). Super Admin yagona va alohida
 * AdminUserSeeder orqali yaratiladi (bu yerda takrorlanmaydi).
 *
 * Har bir user `role` ustuni (parent|institution|teacher — saytdagi
 * kabinet/redirect mantiqi shunga qaraydi) BILAN BIRGA mos Spatie rolini ham
 * (`syncRoles`) oladi — PermissionSeeder shu rollarni oldindan yaratgan
 * bo'lishi shart (DatabaseSeeder'da PermissionSeeder UserSeeder'dan oldin
 * chaqiriladi).
 *
 * Diqqat: +998901234503 va +998901234504 (institution) raqamlari
 * InstitutionSeeder'da aniq shu qiymatlar bilan izlanadi (demo maktab
 * egalari) — bu raqamlarni o'zgartirmang.
 *
 * Parol barchasida: "password"
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        $yunusobod = District::where('name', 'Yunusobod')->first();
        $chilonzor = District::where('name', 'Chilonzor')->first();
        $sergeli = District::where('name', 'Sergeli')->first();

        // ---- Ota-onalar (parent) — 3 ta ----
        $parents = [
            ['phone' => '+998901234501', 'name' => 'Dilnoza Murodova', 'age' => 34, 'district' => $yunusobod],
            ['phone' => '+998901234502', 'name' => 'Sardor Tursunov', 'age' => 39, 'district' => $chilonzor],
            ['phone' => '+998901234507', 'name' => 'Malika Yoqubova', 'age' => 31, 'district' => $sergeli],
        ];

        foreach ($parents as $p) {
            $user = User::updateOrCreate(['phone' => $p['phone']], [
                'name' => $p['name'],
                'email' => $this->syntheticEmail($p['phone']),
                'role' => User::ROLE_PARENT,
                'age' => $p['age'],
                'district_id' => $p['district']?->id,
                'password' => Hash::make('password'),
                'phone_verified_at' => now(),
            ]);

            $user->syncRoles([PermissionSeeder::ROLE_PARENT]);
        }

        // ---- Muassasa egalari / manager (institution) — 3 ta ----
        $institutions = [
            ['phone' => '+998901234503', 'name' => 'Aziz Karimov'],
            ['phone' => '+998901234504', 'name' => 'Nodira Yusupova'],
            ['phone' => '+998901234508', 'name' => 'Otabek Rashidov'],
        ];

        foreach ($institutions as $i) {
            $user = User::updateOrCreate(['phone' => $i['phone']], [
                'name' => $i['name'],
                'email' => $this->syntheticEmail($i['phone']),
                'role' => User::ROLE_INSTITUTION,
                'password' => Hash::make('password'),
                'phone_verified_at' => now(),
            ]);

            $user->syncRoles([PermissionSeeder::ROLE_INSTITUTION_ADMIN]);
        }

        // ---- Ustozlar (teacher) — 3 ta ----
        $teachers = [
            ['phone' => '+998901234509', 'name' => 'Kamola Rahimova', 'age' => 28, 'district' => $yunusobod],
            ['phone' => '+998901234510', 'name' => 'Jasur Aliyev', 'age' => 33, 'district' => $chilonzor],
            ['phone' => '+998901234511', 'name' => 'Sevara Nazarova', 'age' => 26, 'district' => $sergeli],
        ];

        foreach ($teachers as $t) {
            $user = User::updateOrCreate(['phone' => $t['phone']], [
                'name' => $t['name'],
                'email' => $this->syntheticEmail($t['phone']),
                'role' => User::ROLE_TEACHER,
                'age' => $t['age'],
                'district_id' => $t['district']?->id,
                'password' => Hash::make('password'),
                'phone_verified_at' => now(),
            ]);

            $user->syncRoles([PermissionSeeder::ROLE_TEACHER]);
        }
    }

    /** email hali majburiy (§background: doctrine/dbal talab qilinmasligi uchun) — telefon asosida sintetik email */
    protected function syntheticEmail(string $phone): string
    {
        return str($phone)->replace('+', '')->append('@maktabgid.test')->value();
    }
}

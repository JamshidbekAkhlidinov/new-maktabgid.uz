<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /** Har bir rol uchun 2 tadan demo user. Parol barchasida: "password" */
    public function run(): void
    {
        $yunusobod = District::where('name', 'Yunusobod')->first();
        $chilonzor = District::where('name', 'Chilonzor')->first();

        // ---- Ota-onalar (parent) ----
        User::updateOrCreate(['phone' => '+998901234501'], [
            'name' => 'Dilnoza Murodova',
            'email' => 'dilnoza.murodova@maktabgid.test',
            'role' => User::ROLE_PARENT,
            'age' => 34,
            'district_id' => $yunusobod?->id,
            'password' => Hash::make('password'),
            'phone_verified_at' => now(),
        ]);

        User::updateOrCreate(['phone' => '+998901234502'], [
            'name' => 'Sardor Tursunov',
            'email' => 'sardor.tursunov@maktabgid.test',
            'role' => User::ROLE_PARENT,
            'age' => 39,
            'district_id' => $chilonzor?->id,
            'password' => Hash::make('password'),
            'phone_verified_at' => now(),
        ]);

        // ---- Muassasa egalari (institution) ----
        User::updateOrCreate(['phone' => '+998901234503'], [
            'name' => 'Aziz Karimov',
            'email' => 'aziz.karimov@maktabgid.test',
            'role' => User::ROLE_INSTITUTION,
            'password' => Hash::make('password'),
            'phone_verified_at' => now(),
        ]);

        User::updateOrCreate(['phone' => '+998901234504'], [
            'name' => 'Nodira Yusupova',
            'email' => 'nodira.yusupova@maktabgid.test',
            'role' => User::ROLE_INSTITUTION,
            'password' => Hash::make('password'),
            'phone_verified_at' => now(),
        ]);

        // ---- Adminlar ----
        User::updateOrCreate(['phone' => '+998901234505'], [
            'name' => 'Bekzod Administrator',
            'email' => 'bekzod.admin@maktabgid.test',
            'role' => User::ROLE_ADMIN,
            'password' => Hash::make('password'),
            'phone_verified_at' => now(),
        ]);

        User::updateOrCreate(['phone' => '+998901234506'], [
            'name' => 'Kamola Administrator',
            'email' => 'kamola.admin@maktabgid.test',
            'role' => User::ROLE_ADMIN,
            'password' => Hash::make('password'),
            'phone_verified_at' => now(),
        ]);
    }
}

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
            UserSeeder::class,
            InstitutionSeeder::class,
            ReviewSeeder::class,
            ForumSeeder::class,
            ContentSeeder::class,
            CareerSeeder::class,
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    public static function names(): array
    {
        return [
            'Yunusobod', 'Mirzo Ulug\'bek', 'Mirobod', 'Shayxontohur', 'Olmazor',
            'Sergeli', 'Yakkasaroy', 'Yashnobod', 'Yangihayot', 'Chilonzor', 'Uchtepa',
        ];
    }

    public function run(): void
    {
        foreach (self::names() as $name) {
            District::firstOrCreate(['name' => $name]);
        }
    }
}

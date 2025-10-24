<?php

namespace Database\Seeders;

use App\Models\Building;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BuildingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Building::insert([
            ['address' => 'ул. Вишняки, 34', 'latitude' => 55.7558000, 'longitude' => 37.6173000, 'created_at' => now(), 'updated_at' => now()],
            ['address' => 'пр. Мира, 10', 'latitude' => 55.7615000, 'longitude' => 37.6210000, 'created_at' => now(), 'updated_at' => now()],
            ['address' => 'ул. Ленина, 5', 'latitude' => 55.7520000, 'longitude' => 37.6150000, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

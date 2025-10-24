<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Organization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $buildings = Building::all();

        Organization::insert([
            ['name' => 'ООО Рога и Копыта', 'building_id' => $buildings[0]->id, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ЗАО АвтоМир', 'building_id' => $buildings[1]->id, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ИП Петров', 'building_id' => $buildings[2]->id, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

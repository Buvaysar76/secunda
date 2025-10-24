<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Organization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationActivitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $org1 = Organization::where('name', 'ООО Рога и Копыта')->first();
        $org2 = Organization::where('name', 'ЗАО АвтоМир')->first();
        $org3 = Organization::where('name', 'ИП Петров')->first();

        $food = Activity::where('name', 'Еда')->first();
        $auto = Activity::where('name', 'Автомобили')->first();
        $auto2 = Activity::where('name', 'Автомобили 2')->first();
        $construction = Activity::where('name', 'Строительство')->first();
        $materials = Activity::where('name', 'Стройматериалы')->first();

        $org1->activities()->attach([$food->id]);
        $org2->activities()->attach([$auto->id, $auto2->id]);
        $org3->activities()->attach([$construction->id, $materials->id]);
    }
}

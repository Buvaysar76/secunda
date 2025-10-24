<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $food = Activity::create(['name' => 'Еда']);

        $auto = Activity::create(['name' => 'Автомобили']);
        $auto2 = Activity::create(['name' => 'Автомобили 2', 'parent_id' => $auto->id]);
        Activity::create(['name' => 'Автомобили 3', 'parent_id' => $auto2->id]);

        $construction = Activity::create(['name' => 'Строительство']);
        $materials = Activity::create(['name' => 'Стройматериалы', 'parent_id' => $construction->id]);
        Activity::create(['name' => 'Инструменты', 'parent_id' => $construction->id]);
    }
}

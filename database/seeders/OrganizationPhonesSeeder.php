<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationPhonesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $phones = [
            '+7 926 777-77-77',
            '+7 926 888-88-88',
            '+7 926 999-99-99',
        ];

        foreach (Organization::all() as $org) {
            foreach ($phones as $phone) {
                $org->phones()->create(['phone' => $phone]);
            }
        }
    }
}

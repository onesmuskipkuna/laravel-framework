<?php

namespace Database\Seeders;

use App\Models\DamageCode;
use Illuminate\Database\Seeder;

class DamageCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $damageCodes = [
            ['code' => 'D001', 'description' => 'Door hinge broken', 'component' => 'door'],
            ['code' => 'D002', 'description' => 'Door lock damaged', 'component' => 'door'],
            ['code' => 'F001', 'description' => 'Floor board cracked', 'component' => 'floor'],
            ['code' => 'F002', 'description' => 'Floor water damage', 'component' => 'floor'],
            ['code' => 'R001', 'description' => 'Roof dent', 'component' => 'roof'],
            ['code' => 'R002', 'description' => 'Roof leak', 'component' => 'roof'],
            ['code' => 'S001', 'description' => 'Side panel dent', 'component' => 'side'],
            ['code' => 'S002', 'description' => 'Side panel rust', 'component' => 'side'],
            ['code' => 'S003', 'description' => 'Side panel hole', 'component' => 'side'],
            ['code' => 'C001', 'description' => 'Corner post damaged', 'component' => 'corner'],
        ];

        foreach ($damageCodes as $code) {
            DamageCode::create($code);
        }
    }
}

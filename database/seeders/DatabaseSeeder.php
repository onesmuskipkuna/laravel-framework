<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@cargodepot.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create gate officer user
        User::create([
            'name' => 'Gate Officer',
            'email' => 'gate@cargodepot.com',
            'password' => Hash::make('password'),
            'role' => 'gate_officer',
        ]);

        // Create surveyor user
        User::create([
            'name' => 'Yard Surveyor',
            'email' => 'surveyor@cargodepot.com',
            'password' => Hash::make('password'),
            'role' => 'surveyor',
        ]);

        // Create office staff user
        User::create([
            'name' => 'Office Staff',
            'email' => 'office@cargodepot.com',
            'password' => Hash::make('password'),
            'role' => 'office_staff',
        ]);

        // Call other seeders
        $this->call([
            ShippingLineSeeder::class,
            DamageCodeSeeder::class,
        ]);
    }
}

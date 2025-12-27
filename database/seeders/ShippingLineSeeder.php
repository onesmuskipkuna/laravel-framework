<?php

namespace Database\Seeders;

use App\Models\ShippingLine;
use Illuminate\Database\Seeder;

class ShippingLineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shippingLines = [
            [
                'name' => 'Maersk Line',
                'code' => 'MAEU',
                'email' => 'depot@maersk.com',
                'phone' => '+1234567890',
                'address' => '123 Shipping St, Port City',
            ],
            [
                'name' => 'MSC Mediterranean Shipping Company',
                'code' => 'MSCU',
                'email' => 'depot@msc.com',
                'phone' => '+1234567891',
                'address' => '456 Harbor Rd, Port City',
            ],
            [
                'name' => 'CMA CGM',
                'code' => 'CMDU',
                'email' => 'depot@cma-cgm.com',
                'phone' => '+1234567892',
                'address' => '789 Ocean Ave, Port City',
            ],
        ];

        foreach ($shippingLines as $line) {
            ShippingLine::create($line);
        }
    }
}

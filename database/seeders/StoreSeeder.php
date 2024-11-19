<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Store::factory()->create(
            ['name'         => 'Tienda 1',
            'code'          => '001',
            'phone'         => '123456789',
            'address'       => 'Calle 1',
            'city'          => 'Ciudad 1',
            'country'       => 'Pais 1',
            'postal_code'   => '12345',
            'state'         => 'Estado 1',
            'email'         => 'test@example.com',
            'currency_code' => 'USD',
        ],
        );
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Fuel;

class DummyFuelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Fuel::factory()->count(300)->create();
    }
}

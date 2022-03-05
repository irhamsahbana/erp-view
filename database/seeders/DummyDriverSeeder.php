<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Driver;

class DummyDriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Driver::factory()->count(200)->create();
    }
}

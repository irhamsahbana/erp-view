<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Solar;

class DummySolarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Solar::factory()->count(300)->create();
    }
}

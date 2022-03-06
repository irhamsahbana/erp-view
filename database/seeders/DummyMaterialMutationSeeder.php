<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\MaterialMutation;

class DummyMaterialMutationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MaterialMutation::factory()->count(300)->create();
    }
}

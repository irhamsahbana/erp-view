<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\DebtMutation;

class DummyDebtMutationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DebtMutation::factory()->count(10)->create();
    }
}

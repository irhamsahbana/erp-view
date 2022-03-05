<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Branch;

class DummyBranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Branch::factory()->count(2)->create();
    }
}

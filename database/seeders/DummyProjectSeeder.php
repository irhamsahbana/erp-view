<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Project;

class DummyProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Project::factory()->count(300)->create();
    }
}

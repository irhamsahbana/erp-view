<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Material;

class DummyMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Material::factory()->count(100)->create();
    }
}

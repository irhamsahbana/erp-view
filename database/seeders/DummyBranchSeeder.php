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
        $data = [
            ['id' => 1, 'name' => "Kurnia Jaya Karya"],
            ['id' => 2, 'name' => "PT. Delta"],
            ['id' => 3, 'name' => "Kurnia Makmur Karya"],

        ];
        foreach ($data as $item) {
            Branch::create($item);
        }
        // Branch::factory()->count(2)->create();
    }
}

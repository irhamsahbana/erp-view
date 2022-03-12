<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Vendor;

class DummyVendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Vendor::factory()->count(10)->create();
    }
}

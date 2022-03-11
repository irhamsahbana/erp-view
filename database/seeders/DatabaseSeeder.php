<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        // $this->call(DummyKendaraanSeeder::class);
        // $this->call(DummySolarSeeder::class);
        $this->call(DummyBranchSeeder::class);
        $this->call(DummyUserSeeder::class);
        $this->call(DummyDriverSeeder::class);
        $this->call(DummyMaterialSeeder::class);
        $this->call(DummyProjectSeeder::class);
        $this->call(DummyVehicleSeeder::class);
        $this->call(DummyVendorSeeder::class);

        // $this->call(DummyMaterialMutationSeeder::class);
        // $this->call(DummyDebtMutationSeeder::class);
        $this->call(DummyFuelSeeder::class);
        $this->call(DummyOrderSeeder::class);
    }
}

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
        $this->call(CategorySeeder::class);

        // $this->dummy();
        $this->call(BudgetItemGroupSeeder::class);
        $this->call(BudgetItemSeeder::class);
        $this->call(SubBudgetItemSeeder::class);
    }

    public function dummy()
    {
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
        $this->call(DummyVoucherSeeder::class);
    }
}

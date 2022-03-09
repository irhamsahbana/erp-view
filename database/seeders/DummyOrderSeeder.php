<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Order;

class DummyOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Order::factory()->count(200)->create();
    }
}

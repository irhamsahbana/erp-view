<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Voucher as Model;

class DummyVoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::factory()->count(150)->create();
    }
}

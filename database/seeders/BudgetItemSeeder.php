<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\BudgetItem as Model;
use App\Models\BudgetItemGroup;

class BudgetItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $big = BudgetItemGroup::all();

        // kelompok mata anggaran
        $pendapatan = $big->where('name', 'Pendapatan')->first();
        $hpp = $big->where('name', 'HPP')->first();
        $biaya = $big->where('name', 'Biaya')->first();
        $aktiva = $big->where('name', 'Aktiva')->first();
        $passiva = $big->where('name', 'Passiva')->first();

        //mata anggaran
        $data = [
            ['id' => 1, 'report_category_id' => $pendapatan->report_category_id, 'budget_item_group_id' => $pendapatan->id, 'name' => 'Pendapatan Operasi'],
            ['id' => 2, 'report_category_id' => $pendapatan->report_category_id, 'budget_item_group_id' => $pendapatan->id, 'name' => 'Pendapatan Non Operasi'],

            ['id' => 3, 'report_category_id' => $hpp->report_category_id, 'budget_item_group_id' => $hpp->id, 'name' => 'HPP Material'],
            ['id' => 4, 'report_category_id' => $hpp->report_category_id, 'budget_item_group_id' => $hpp->id, 'name' => 'HPP Tenaga Kerja'],

            ['id' => 5, 'report_category_id' => $biaya->report_category_id, 'budget_item_group_id' => $biaya->id, 'name' => 'Biaya Operasional'],

            ['id' => 6, 'report_category_id' => $aktiva->report_category_id, 'budget_item_group_id' => $aktiva->id, 'name' => 'Aktiva Lancar'],
            ['id' => 7, 'report_category_id' => $aktiva->report_category_id, 'budget_item_group_id' => $aktiva->id, 'name' => 'Aktiva Tetap'],

            ['id' => 8, 'report_category_id' => $passiva->report_category_id, 'budget_item_group_id' => $passiva->id, 'name' => 'Hutang Lancar'],
            ['id' => 9, 'report_category_id' => $passiva->report_category_id, 'budget_item_group_id' => $passiva->id, 'name' => 'Modal'],
        ];

        foreach ($data as $item) {
            Model::create($item);
        }
    }
}

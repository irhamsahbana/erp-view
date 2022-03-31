<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

use App\Models\BudgetItemGroup as Model;

class BudgetItemGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = Category::where('group_by', 'report_categories')->get();

        $data = [
            ['id' => 1, 'report_category_id' => $categories->where('slug', 'laba-rugi')->first()->id, 'name' => 'Pendapatan'],
            ['id' => 2, 'report_category_id' => $categories->where('slug', 'laba-rugi')->first()->id, 'name' => 'HPP'],
            ['id' => 3, 'report_category_id' => $categories->where('slug', 'laba-rugi')->first()->id, 'name' => 'Biaya'],

            ['id' => 4, 'report_category_id' => $categories->where('slug', 'neraca')->first()->id, 'name' => 'Aktiva'],
            ['id' => 5, 'report_category_id' => $categories->where('slug', 'neraca')->first()->id, 'name' => 'Passiva'],
        ];

        foreach ($data as $item) {
            Model::create($item);
        }
    }
}

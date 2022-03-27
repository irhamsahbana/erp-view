<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['id' => 1,  'category_id' => null, 'disabled' => false, 'group_by' => 'report_categories', 'slug' => 'neraca', 'label' => 'Neraca', 'notes' => null],
            ['id' => 2,  'category_id' => null, 'disabled' => false, 'group_by' => 'report_categories', 'slug' => 'laba-rugi', 'label' => 'Laba/Rugi', 'notes' => null],

            ['id' => 11, 'category_id' => null, 'disabled' => false, 'group_by' => 'normal_balances', 'slug' => 'debit', 'label' => 'Debit', 'notes' => null],
            ['id' => 12, 'category_id' => null, 'disabled' => false, 'group_by' => 'normal_balances', 'slug' => 'kredit', 'label' => 'Kredit', 'notes' => null],

            ['id' => 21, 'category_id' => null, 'disabled' => false, 'group_by' => 'journal_categories', 'slug' => 'penerimaan-kas', 'label' => 'Penerimaan Kas', 'notes' => null],
            ['id' => 22, 'category_id' => null, 'disabled' => false, 'group_by' => 'journal_categories', 'slug' => 'pengeluaran-kas', 'label' => 'Pengeluaran Kas', 'notes' => null],
            ['id' => 23, 'category_id' => null, 'disabled' => false, 'group_by' => 'journal_categories', 'slug' => 'hutang', 'label' => 'Hutang', 'notes' => null],
            ['id' => 24, 'category_id' => null, 'disabled' => false, 'group_by' => 'journal_categories', 'slug' => 'material', 'label' => 'Material', 'notes' => null],
            ['id' => 25, 'category_id' => null, 'disabled' => false, 'group_by' => 'journal_categories', 'slug' => 'purchasing', 'label' => 'Purchasing', 'notes' => null],
            ['id' => 26, 'category_id' => null, 'disabled' => false, 'group_by' => 'journal_categories', 'slug' => 'umum', 'label' => 'Umum', 'notes' => null],

            ['id' => 27, 'category_id' => null, 'disabled' => false, 'group_by' => 'debt_types', 'slug' => 'hutang', 'label' => 'Hutang', 'notes' => null],
            ['id' => 28, 'category_id' => null, 'disabled' => false, 'group_by' => 'debt_types', 'slug' => 'piutang', 'label' => 'Piutang', 'notes' => null],

            // ['id' => 31, 'category_id' => null, 'disabled' => false, 'group_by' => 'budget_item_group', 'slug' => 'aset', 'label' => 'Aset', 'notes' => null],
            // ['id' => 32, 'category_id' => null, 'disabled' => false, 'group_by' => 'budget_item_group', 'slug' => 'kewajiban', 'label' => 'Kewajiban', 'notes' => null],
            // ['id' => 33, 'category_id' => null, 'disabled' => false, 'group_by' => 'budget_item_group', 'slug' => 'ekuitas', 'label' => 'Ekuitas', 'notes' => null],
            // ['id' => 34, 'category_id' => null, 'disabled' => false, 'group_by' => 'budget_item_group', 'slug' => 'pendapatan-usaha', 'label' => 'Pendapatan Usaha', 'notes' => null],
            // ['id' => 35, 'category_id' => null, 'disabled' => false, 'group_by' => 'budget_item_group', 'slug' => 'beban-usaha', 'label' => 'Beban Usaha', 'notes' => null],

            // ['id' => 41, 'category_id' => null, 'disabled' => false, 'group_by' => 'budget_item', 'slug' => 'kas', 'label' => 'Kas', 'notes' => null],
            // ['id' => 42, 'category_id' => null, 'disabled' => false, 'group_by' => 'budget_item', 'slug' => 'setara-kas', 'label' => 'Setara Kas', 'notes' => null],
            // ['id' => 43, 'category_id' => null, 'disabled' => false, 'group_by' => 'budget_item', 'slug' => 'bank', 'label' => 'Bank', 'notes' => null],
            // ['id' => 44, 'category_id' => null, 'disabled' => false, 'group_by' => 'budget_item', 'slug' => 'piutang-anggota', 'label' => 'Piutang Anggota', 'notes' => null],
            // ['id' => 45, 'category_id' => null, 'disabled' => false, 'group_by' => 'budget_item', 'slug' => 'listrik', 'label' => 'Listrik', 'notes' => null],

            // ['id' => 27, 'disabled' => false, 'group_by' => 'sub_budget_item', 'category_id' => null, 'slug' => 'umum', 'label' => 'Umum', 'notes' => null],
        ];

        foreach ($data as $item) {
            Category::create($item);
        }
    }
}

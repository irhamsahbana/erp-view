<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\SubBudgetItem as Model;
use App\Models\BudgetItem;
use App\Models\Category;

class SubBudgetItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bi = BudgetItem::all();
        $nb = Category::where('group_by', 'normal_balances')->get();

        $nbK = $nb->where('slug', 'kredit')->first()->id;
        $nbD = $nb->where('slug', 'debit')->first()->id;

        $pendapatanOperasi = $bi->where('name', 'Pendapatan Operasi')->first();
        $pendapatanNonOperasi = $bi->where('name', 'Pendapatan Non Operasi')->first();
        $hppMaterial = $bi->where('name', 'HPP Material')->first();
        $hppTenagaKerja = $bi->where('name', 'HPP Tenaga Kerja')->first();
        $biayaOperasional = $bi->where('name', 'Biaya Operasional')->first();
        $aktivaLancar = $bi->where('name', 'Aktiva Lancar')->first();
        $aktivaTetap = $bi->where('name', 'Aktiva Tetap')->first();
        $hutangLancar = $bi->where('name', 'Hutang Lancar')->first();
        $modal = $bi->where('name', 'Modal')->first();

        $sbi1 = ['Pendapatan Termin', 'Pendapatan Sewa Alat', 'Pendapatan Penyaluran Tenaga Kerja',];
        $sbi2 = ['Pendapatan Jasa Bank', 'Pendapatan Lain-lain',];

        $sbi3 = ['LPA', 'Abu Batu', '0.5', '3/4', 'Aspal', 'Pasir', 'Kayu', 'Semen', 'Emulsi'];

        $sbi4 = ['Tenaga Kerja Langsung'];

        $sbi5 = ['Biaya Promosi dan Perizinan', 'Biaya Konsumsi Pegawai', 'Biaya Gaji', 'Biaya Listrik', 'Biaya Telepon',
                 'Biaya Perlengkapan/Administrasi', 'Biaya Perjalanan Dinas', 'Biaya Pajak', 'Biaya Peralatan',
                 'Biaya Penyusutan', 'Biaya Pemeliharaan', 'Biaya Lain-lain', 'Biaya Subkon'];

        $sbi6 = ['Kas', 'Bank BPD Timika', 'Bank BPD Mandiri Bisnis', 'Bank Mandiri Giro', 'Bank CIMB Niaga', 'Perlengkapan', 'Piutang DT', 'Piutang Karyawan', 'UM PPh 23', 'UM PPh 25'];

        $sbi7 = ['Tanah', 'Bangunan', 'Kendaraan', 'Akum. Penyusutan Aktiva Tetap'];

        $sbi8 = ['Hutang Usaha', 'Hutang Subkon', 'Hutang Retase', 'Hutang Material'];

        $sbi9 = ['Modal Usaha', 'Modal Ditahan', 'Modal Tahun 2020', 'Modal Berjalan'];

        foreach ($sbi1 as $item) {
            Model::create([
                'report_category_id' => $pendapatanOperasi->report_category_id,
                'budget_item_group_id' => $pendapatanOperasi->budget_item_group_id,
                'budget_item_id' => $pendapatanOperasi->id,
                'name' => $item,
                'normal_balance_id' => $nbK,
            ]);
        }

        foreach ($sbi2 as $item) {
            Model::create([
                'report_category_id' => $pendapatanNonOperasi->report_category_id,
                'budget_item_group_id' => $pendapatanOperasi->budget_item_group_id,
                'budget_item_id' => $pendapatanNonOperasi->id,
                'name' => $item,
                'normal_balance_id' => $nbK,
            ]);
        }

        foreach ($sbi3 as $item) {
            Model::create([
                'report_category_id' => $hppMaterial->report_category_id,
                'budget_item_group_id' => $hppMaterial->budget_item_group_id,
                'budget_item_id' => $hppMaterial->id,
                'name' => $item,
                'normal_balance_id' => $nbD
            ]);
        }

        foreach ($sbi4 as $item) {
            Model::create([
                'report_category_id' => $hppTenagaKerja->report_category_id,
                'budget_item_group_id' => $hppTenagaKerja->budget_item_group_id,
                'budget_item_id' => $hppTenagaKerja->id,
                'name' => $item,
                'normal_balance_id' => $nbD
            ]);
        }

        foreach ($sbi5 as $item) {
            Model::create([
                'report_category_id' => $biayaOperasional->report_category_id,
                'budget_item_group_id' => $biayaOperasional->budget_item_group_id,
                'budget_item_id' => $biayaOperasional->id,
                'name' => $item,
                'normal_balance_id' => $nbD
            ]);
        }

        foreach ($sbi6 as $item) {
            Model::create([
                'report_category_id' => $aktivaLancar->report_category_id,
                'budget_item_group_id' => $aktivaLancar->budget_item_group_id,
                'budget_item_id' => $aktivaLancar->id,
                'name' => $item,
                'normal_balance_id' => $nbD
            ]);
        }

        foreach ($sbi7 as $item) {
            Model::create([
                'report_category_id' => $aktivaTetap->report_category_id,
                'budget_item_group_id' => $aktivaTetap->budget_item_group_id,
                'budget_item_id' => $aktivaTetap->id,
                'name' => $item,
                'normal_balance_id' => $nbD
            ]);
        }

        foreach ($sbi8 as $item) {
            Model::create([
                'report_category_id' => $hutangLancar->report_category_id,
                'budget_item_group_id' => $hutangLancar->budget_item_group_id,
                'budget_item_id' => $hutangLancar->id,
                'name' => $item,
                'normal_balance_id' => $nbK
            ]);
        }

        foreach ($sbi9 as $item) {
            Model::create([
                'report_category_id' => $modal->report_category_id,
                'budget_item_group_id' => $modal->budget_item_group_id,
                'budget_item_id' => $modal->id,
                'name' => $item,
                'normal_balance_id' => $nbK
            ]);
        }
    }
}

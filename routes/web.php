<?php

use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    AjaxController,
    CategoryController,
    AuthController,
    BranchController,
    BudgetController,
    FuelController,
    UserController,
    ProjectController,
    VehicleController,
    MaterialController,
    DriverController,
    MaterialMutationController,
    DebtMutationController,
    RitMutationController,
    OrderController,
    PurchaseController,
    PurchaseDetailController,
    VendorController,
    VoucherController,
    ReceivableController,
    //journal
    BudgetItemGroupController,
    BudgetItemController,
    SubBudgetItemController,
    JournalController,
    NeracaController,
    ProfitLossController,
    ReportController,
    GeneralLedgerController,
    DashboardController,
    MailController,
    BillController
};

use App\Models\{
    DebtMutation,
    DebtBalance,
    Journals,
    MaterialMutation,
    MaterialBalance,
    PurchaseDetail
};
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' =>['guest']], function() {
    Route::view('/login', 'pages.Login')->name('login');
    Route::post('login', [AuthController::class, 'attempt'])->name('login.attempt');
});
Route::get('/dashboard', [DashboardController::class, 'dashboard']) -> name('dashboard.view');
Route::group(['middleware' => ['auth']], function(){
    Route::view('/', 'pages.Dashboard')->name('app');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::group(['prefix' => 'master-data'], function() {
        Route::get('/', function() {
            return redirect('/');
        });

        Route::get('cabang', [BranchController::class, 'index'])->name('branch.index');
        Route::post('cabang', [BranchController::class, 'store'])->name('branch.store');
        Route::get('cabang/{id}', [BranchController::class, 'show'])->name('branch.show');
        Route::delete('cabang/{id}', [BranchController::class, 'destroy'])->name('branch.destroy');

        Route::get('pengguna/edit-password', [UserController::class, 'edit'])->name('user.edit-password');
        Route::put('pengguna/update-password', [UserController::class, 'update'])->name('user.update');
        Route::get('pengguna/{id}', [UserController::class, 'show'])->name('user.show');
        Route::delete('pengguna/{id}', [UserController::class, 'destroy'])->name('user.destroy');
        Route::get('pengguna', [UserController::class, 'index'])->name('user.index');
        Route::post('pengguna', [UserController::class, 'store'])->name('user.store');

        Route::get('proyek', [ProjectController::class, 'index'])->name('project.index');
        Route::post('proyek', [ProjectController::class, 'store'])->name('project.store');
        Route::get('proyek/{id}', [ProjectController::class, 'show'])->name('project.show');
        Route::delete('proyek/{id}', [ProjectController::class, 'destroy'])->name('project.destroy');

        Route::get('kendaraan', [VehicleController::class, 'index'])->name('vehicle.index');
        Route::post('kendaraan', [VehicleController::class, 'store'])->name('vehicle.store');
        Route::get('kendaraan/{id}', [VehicleController::class, 'show'])->name('vehicle.show');
        Route::delete('kendaraan/{id}', [VehicleController::class, 'destroy'])->name('vehicle.destroy');

        Route::get('pengendara', [DriverController::class, 'index'])->name('driver.index');
        Route::post('pengendara', [DriverController::class, 'store'])->name('driver.store');
        Route::get('pengendara/{id}', [DriverController::class, 'show'])->name('driver.show');
        Route::delete('pengendara/{id}', [DriverController::class, 'destroy'])->name('driver.destroy');

        Route::get('material', [MaterialController::class, 'index'])->name('material.index');
        Route::post('material', [MaterialController::class, 'store'])->name('material.store');
        Route::get('material/{id}', [MaterialController::class, 'show'])->name('material.show');
        Route::delete('material/{id}', [MaterialController::class, 'destroy'])->name('material.destroy');

        Route::get('vendor', [VendorController::class, 'index'])->name('vendor.index');
        Route::post('vendor', [VendorController::class, 'store'])->name('vendor.store');
        Route::get('vendor/{id}', [VendorController::class, 'show'])->name('vendor.show');
        Route::delete('vendor/{id}', [VendorController::class, 'destroy'])->name('vendor.destroy');

        Route::get('kategori/list', [CategoryController::class, 'list'])->name('category.list');
        Route::get('kategori/{id}', [CategoryController::class, 'show'])->name('category.show');
        Route::get('kategori', [CategoryController::class, 'index'])->name('category.index');
        Route::post('kategori', [CategoryController::class, 'store'])->name('category.store');
        Route::delete('kategori/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');

        Route::get('kelompok-mata-anggaran', [BudgetItemGroupController::class, 'index'])->name('big.index');
        Route::post('kelompok-mata-anggaran', [BudgetItemGroupController::class, 'store'])->name('big.store');
        Route::get('kelompok-mata-anggaran/{id}', [BudgetItemGroupController::class, 'show'])->name('big.show');
        Route::delete('kelompok-mata-anggaran/{id}', [BudgetItemGroupController::class, 'destroy'])->name('big.destroy');

        Route::get('mata-anggaran', [BudgetItemController::class, 'index'])->name('bi.index');
        Route::post('mata-anggaran', [BudgetItemController::class, 'store'])->name('bi.store');
        Route::get('mata-anggaran/{id}', [BudgetItemController::class, 'show'])->name('bi.show');
        Route::delete('mata-anggaran/{id}', [BudgetItemController::class, 'destroy'])->name('bi.destroy');

        Route::get('sub-mata-anggaran', [SubBudgetItemController::class, 'index'])->name('sbi.index');
        Route::post('sub-mata-anggaran', [SubBudgetItemController::class, 'store'])->name('sbi.store');
        Route::get('sub-mata-anggaran/{id}', [SubBudgetItemController::class, 'show'])->name('sbi.show');
        Route::delete('sub-mata-anggaran/{id}', [SubBudgetItemController::class, 'destroy'])->name('sbi.destroy');
    });

    Route::group(['prefix' => 'journal'], function() {
        Route::get('/', [JournalController::class, 'index'])->name('journal.index');
        Route::get('/buat-jurnal', [JournalController::class, 'create'])->name('add.journal');
        Route::post('/simpan-jurnal', [JournalController::class, 'save'])->name('save.journal');
        Route::get('/hapus-jurnal/{id}', [JournalController::class, 'delete'])->name('delete.journal');
        Route::get('/ubah-jurnal/{journal:id}', [JournalController::class, 'edit'])->name('edit.journal');
        Route::post('/ganti-jurnal/{journal:id}', [JournalController::class, 'update'])->name('update.journal');
        Route::post('/tambah-sub-jurnal', [JournalController::class, 'postSubJournal'])->name('post-sub-journal');
        Route::post('/update-sub-jounal', [JournalController::class, 'updateSubJournal'])->name('edit.sub.journal');
        Route::get('/rincian-jurnal/{journal:id}', [JournalController::class, 'detail'])->name('detail.journal');
        Route::post('/simpan-sub-jurnal-sementara', [JournalController::class, 'saveSubJournalTemporaryToSubJournal'])->name('save-sub-journal-temporary');
        Route::get('/hapus-sub-jurnal', [JournalController::class, 'deleteSubJournal'])->name('delete-sub-journal');
        Route::get('/hapus-sub-jurnal-sementara', [JournalController::class, 'deleteSubJournalTemp'])->name('delete-sub-journal-temp');

        Route::get('/get-budget-item', [AjaxController::class, 'getBudgetItem'])->name('get-budget-item');
        Route::get('/get-sub-budget-item', [AjaxController::class, 'getSubBudgetItem'])->name('get-sub-budget-item');
        Route::get('/get-budget-item-group', [AjaxController::class, 'getBudgetItemGroup'])->name('get-budget-item-group');
        Route::get('/get-normal-balances', [AjaxController::class, 'getNormalBalance'])->name('get-normal-balance');
    });

    Route::group(['prefix' => 'neraca'], function() {
        Route::get('/', [ReportController::class, 'balancesheet'])->name('balance.index');
        Route::get('/export-data-balance', [ReportController::class, 'exportBalance'])->name('export.balance');
    });
    Route::group(['prefix' => 'tagihan'], function () {
        Route::get('/', [ReceivableController::class, 'index'])->name('receivable-index');
    });
    Route::group(['prefix' => 'laba-rugi'], function() {
        Route::get('/', [ReportController::class, 'incomeStatement'])->name('income.statement.index');
        Route::get('/export-data-income-statement', [ReportController::class, 'exportIncomeStatement'])->name('export.income.statement');
    });
    Route::group(['prefix' => 'buku-besar'], function() {
        Route::get('/', [GeneralLedgerController::class, 'index'])->name('general.ledger.index');
    });

    Route::group(['prefix' => 'transaksi'], function() {
        Route::get('/', function() {
            return redirect('/');
        });

        Route::get('order/{id}', [OrderController::class, 'show'])->name('order.show');
        Route::get('order', [OrderController::class, 'index'])->name('order.index');
        Route::post('order', [OrderController::class, 'store'])->name('order.store');
        Route::delete('order/{id}', [OrderController::class, 'destroy'])->name('order.destroy');
        Route::put('order/ubah-status-order/{id}', [OrderController::class, 'changeStatus'])->name('order.change-order-status');
        Route::put('order/ubah-status/{id}', [OrderController::class, 'changeIsOpen'])->name('order.change-status');

        Route::get('voucher/{id}', [VoucherController::class, 'show'])->name('voucher.show');
        Route::get('voucher', [VoucherController::class, 'index'])->name('voucher.index');
        Route::post('voucher', [VoucherController::class, 'store'])->name('voucher.store');
        Route::delete('voucher/{id}', [VoucherController::class, 'destroy'])->name('voucher.destroy');
        Route::put('voucher/ubah-status/{id}', [VoucherController::class, 'changeIsOpen'])->name('voucher.change-status');
        Route::get('voucher/cetak/{id}', [VoucherController::class, 'print'])->name('voucher.print');

        Route::get('solar', [FuelController::class, 'index'])->name('fuel.index');
        Route::post('solar', [FuelController::class, 'store'])->name('fuel.store');
        Route::get('solar/{id}', [FuelController::class, 'show'])->name('fuel.show');
        Route::delete('solar/{id}', [FuelController::class, 'destroy'])->name('fuel.destroy');
        Route::put('solar/ubah-status/{id}', [FuelController::class, 'changeIsOpen'])->name('fuel.change-status');
        Route::get('solar/cetak/{id}', [FuelController::class, 'print'])->name('fuel.print');

        Route::get('mutasi-material/saldo', [MaterialMutationController::class, 'balance'])->name('material-mutation.balance');
        Route::get('mutasi-material', [MaterialMutationController::class, 'index'])->name('material-mutation.index');
        Route::post('mutasi-material', [MaterialMutationController::class, 'store'])->name('material-mutation.store');
        Route::get('mutasi-material/{id}', [MaterialMutationController::class, 'show'])->name('material-mutation.show');
        Route::delete('mutasi-material/{id}', [MaterialMutationController::class, 'destroy'])->name('material-mutation.destroy');
        Route::put('mutasi-material/ubah-status/{id}', [MaterialMutationController::class, 'changeIsOpen'])->name('material-mutation.change-status');
        Route::get('mutasi-material/saldo', [MaterialMutationController::class, 'balance'])->name('material-mutation.balance');
        Route::get('mutasi-material/cetak/{id}', [MaterialMutationController::class, 'print'])->name('mutasi-material.print');

        Route::get('mutasi-hutang/saldo', [DebtMutationController::class, 'balance'])->name('debt-mutation.balance');
        Route::get('mutasi-hutang', [DebtMutationController::class, 'index'])->name('debt-mutation.index');
        Route::post('mutasi-hutang', [DebtMutationController::class, 'store'])->name('debt-mutation.store');
        Route::get('mutasi-hutang/{id}', [DebtMutationController::class, 'show'])->name('debt-mutation.show');
        Route::delete('mutasi-hutang/{id}', [DebtMutationController::class, 'destroy'])->name('debt-mutation.destroy');
        Route::put('mutasi-hutang/ubah-status/{id}', [DebtMutationController::class, 'changeIsOpen'])->name('debt-mutation.change-status');
        Route::get('mutasi-hutang/cetak/{id}', [DebtMutationController::class, 'print'])->name('debt-mutation.print');

        Route::get('mutasi-hutang-ritase/saldo', [RitMutationController::class, 'balance'])->name('rit-mutation.balance');
        Route::get('mutasi-hutang-ritase', [RitMutationController::class, 'index'])->name('rit-mutation.index');
        Route::post('mutasi-hutang-ritase', [RitMutationController::class, 'store'])->name('rit-mutation.store');
        Route::get('mutasi-hutang-ritase/{id}', [RitMutationController::class, 'show'])->name('rit-mutation.show');
        Route::delete('mutasi-hutang-ritase/{id}', [RitMutationController::class, 'destroy'])->name('rit-mutation.destroy');
        Route::put('mutasi-hutang-ritase/ubah-status/{id}', [RitMutationController::class, 'changeIsOpen'])->name('rit-mutation.change-status');
        Route::put('mutasi-hutang-ritase/ubah-status-bayar/{id}', [RitMutationController::class, 'changeIsPaid'])->name('rit-mutation.change-status-paid');
        Route::get('mutasi-hutang-ritase/cetak/{id}', [RitMutationController::class, 'print'])->name('rit-mutation.print');
    });

    Route::group(['prefix' => 'laporan'], function() {
        Route::get('penggunaan-solar', [FuelController::class, 'fuelReport'])->name('fuel.report');
    });
    Route::group(['prefix' => 'transaksi-pembelian'], function () {
        Route::get('/', function () {
            return redirect('/');
        });

        Route::get('pembelian', [PurchaseController::class, 'index'])->name('purchasing.index');
        Route::post('pembelian', [PurchaseController::class, 'store'])->name('purchase.store');
        Route::put('pembelian/ubah-status/{id}', [PurchaseController::class, 'changeIsOpen'])->name('purchase.change-status');
        Route::put('pembelian/ubah-status-bayar/{id}', [PurchaseController::class, 'changeIsPaid'])->name('purchase.change-status-paid');
        Route::put('pembelian/ubah-status-accepted/{id}', [PurchaseController::class, 'changeIsAccepted'])->name('purchase.change-status-accept');
        Route::delete('pembelian/{id}', [PurchaseController::class, 'destroy'])->name('purchase.destroy');
        Route::get('pembelian/{id}', [PurchaseController::class, 'show'])->name('purchase.show');
        Route::post('pembelian-detail', [PurchaseDetailController::class, 'store'])->name('purchase-detail.store');
        Route::put('pembelian-detail/ubah-harga/{id}', [PurchaseDetailController::class, 'update'])->name('purchase-detail.update-price');
        Route::delete('pembelian-detail/{id}', [PurchaseDetailController::class, 'destroy'])->name('purchase-detail.destroy');
        Route::get('pembelian-detail/cetak/{id}', [PurchaseDetailController::class, 'print'])->name('purchase-detail.print');
    });
    Route::group(['prefix' => 'anggaran'], function() {
        Route::get('anggaran/{id}', [BudgetController::class, 'show'])->name('budget.show');
        Route::get('anggaran', [BudgetController::class, 'index'])->name('budget.index');
        Route::post('anggaran', [BudgetController::class, 'store'])->name('budget.store');
        Route::put('anggaran/{id}', [BudgetController::class, 'changeIsOpen'])->name('budget.change-status');
        Route::put('anggaran-ubah/{id}', [BudgetController::class, 'update'])->name('budget.update');
        Route::delete('anggaran/{id}', [BudgetController::class, 'destroy'])->name('budget.destroy');
    });
});
// if (Auth::user()->role == 'Account Receivable' || Auth::user()->role == 'owner' || Auth::user()->role == 'admin') {

    Route::group(['prefix' => 'tagihan'], function () {
        // tagihan
        Route::get('/', [ReceivableController::class, 'index'])->name('receivable.index');
        Route::delete('/{id}', [ReceivableController::class, 'destroy'])->name('receivable.delete');
        Route::post('/status/{id}', [ReceivableController::class, 'changeIsPaid'])->name('receivable-statuspaid.post');
        Route::post('/', [ReceivableController::class, 'addReceivable'])->name('receivable.add');
        Route::get('receivable/print/{id}', [ReceivableController::class, 'print'])->name('receivable.print');

        // saldo
        Route::get('/saldo', [ReceivableController::class, 'balanceIndex'])->name('receivable-balance.index');
        // vendor
        Route::get('/vendor', [ReceivableController::class, 'vendorReceivable'])->name('receivable-vendor.index');
        Route::get('/vendor/{id}', [ReceivableController::class, 'showVendor'])->name('receivable-vendor.detail');
        Route::delete('/vendor/{id}', [ReceivableController::class, 'deleteVendor'])->name('receivable-vendor.destroy');
        Route::post('/vendor', [ReceivableController::class, 'VendorStore'])->name('receivable-vendor.store');

    });
    Route::group(['prefix' => 'bill'], function () {
        Route::get('/', [BillController::class, 'indexBill'])->name('bill.index');
        Route::get('/create', [BillController::class, 'createBill'])->name('bill.create');
        Route::post('/', [BillController::class, 'addBill'])->name('bill.store');
        Route::delete('/{id}', [BillController::class, 'deleteBill'])->name('bill.destroy');
        Route::get('/detail/{id}', [BillController::class, 'detailBill'])->name('bill.detail');
        Route::post('/paid/{id}', [BillController::class, 'changeIsPaid'])->name('bill.change-status');


        // Subbill
        Route::post('/subbill', [BillController::class, 'addSubBill'])->name('subbill.add');
        Route::delete('/subbill/{id}', [BillController::class, 'deleteSubBill'])->name('subbill.delete');


        // Vendor
        Route::get('/vendor', [BillController::class, 'indexVendor'])->name('bill-vendor.index');
        Route::post('/vendor', [BillController::class, 'addVendor'])->name('bill-vendor.add');
        Route::delete('/vendor/{id}', [BillController::class, 'deleteVendor'])->name('bill-vendor.destroy');

        // item
        Route::get('/item', [BillController::class, 'indexItem'])->name('bill-item.index');
        Route::post('/item', [BillController::class, 'addItem'])->name('bill-item.add');
        Route::delete('/item/{id}', [BillController::class, 'deleteItem'])->name('bill-item.destroy');



        // Balance
        Route::get('/balance', [BillController::class, 'indexBalance'])->name('bill-balance.index');
    });


    Route::get('send-email-all',[MailController::class, 'index'])->name('email-idex');
// }
Route::get('/test', [TestController::class, 'test']);


Route::get('/delete-debt', function() {
    $debtBalance = DebtBalance::all();
    foreach ($debtBalance as $db) {
        $db->delete();
    }

    $debtMutation = DebtMutation::all();
    foreach ($debtMutation as $dm) {
        $dm->delete();
    }

    echo "success delete debt";
});

Route::get('delete-material', function() {
    $materialBalance = MaterialBalance::all();
    foreach ($materialBalance as $db) {
        $db->delete();
    }

    $materialMutation = MaterialMutation::all();
    foreach ($materialMutation as $dm) {
        $dm->delete();
    }

    echo "success delete material";
});


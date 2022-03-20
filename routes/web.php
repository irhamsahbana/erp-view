<?php

use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    AuthController,
    BranchController,
    FuelController,
    UserController,
    ProjectController,
    VehicleController,
    MaterialController,
    DriverController,
    MaterialMutationController,
    DebtMutationController,
    OrderController,
    VendorController,
    VoucherController,
};

use App\Models\{
    DebtMutation,
    DebtBalance,
    MaterialMutation,
    MaterialBalance
};

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

Route::group(['middleware' => ['auth']], function(){
    Route::view('/', 'App')->name('app');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::group(['prefix' => 'master-data'], function() {
        Route::get('/', function() {
            return redirect('/');
        });

        Route::get('cabang', [BranchController::class, 'index'])->name('branch.index');
        Route::post('cabang', [BranchController::class, 'store'])->name('branch.store');
        Route::get('cabang/{id}', [BranchController::class, 'show'])->name('branch.show');
        Route::delete('cabang/{id}', [BranchController::class, 'destroy'])->name('branch.destroy');

        Route::get('pengguna', [UserController::class, 'index'])->name('user.index');
        Route::post('pengguna', [UserController::class, 'store'])->name('user.store');
        Route::get('pengguna/{id}', [UserController::class, 'show'])->name('user.show');
        Route::delete('pengguna/{id}', [UserController::class, 'destroy'])->name('user.destroy');

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

        // Route::view('vendor', 'pages.VendorIndex');
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

        Route::get('mutasi-hutang/saldo', [DebtMutationController::class, 'balance'])->name('debt-mutation.balance');
        Route::get('mutasi-hutang', [DebtMutationController::class, 'index'])->name('debt-mutation.index');
        Route::post('mutasi-hutang', [DebtMutationController::class, 'store'])->name('debt-mutation.store');
        Route::get('mutasi-hutang/{id}', [DebtMutationController::class, 'show'])->name('debt-mutation.show');
        Route::delete('mutasi-hutang/{id}', [DebtMutationController::class, 'destroy'])->name('debt-mutation.destroy');
        Route::put('mutasi-hutang/ubah-status/{id}', [DebtMutationController::class, 'changeIsOpen'])->name('debt-mutation.change-status');
        Route::get('mutasi-hutang/cetak/{id}', [DebtMutationController::class, 'print'])->name('debt-mutation.print');
    });
});


Route::get('/test', [TestController::class, 'test']);

Route::get('/delete-debt', function() {
//delete all
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
    // delete balance
    $materialBalance = MaterialBalance::all();
    foreach ($materialBalance as $db) {
        $db->delete();
    }

    // delete mutation
    $materialMutation = MaterialMutation::all();
    foreach ($materialMutation as $dm) {
        $dm->delete();
    }

    echo "success delete material";
});
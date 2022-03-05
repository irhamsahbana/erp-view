<?php

use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    BranchController,
    FuelController,
    UserController,
    ProjectController,
    VehicleController,
    MaterialController,
    DriverController
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

Route::view('/', 'app');
Route::view('/login', 'Pages.Login')->name('login');

// Route::group(['middleware' => ['auth']], function(){
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

        // Route::view('pengendara', 'Pages.DriverIndex');
        // Route::view('material', 'Pages.MaterialIndex');
        // Route::view('vendor', 'Pages.VendorIndex');
        // Route::view('jenis-mutasi-hutang', 'Pages.DebtFlowCategoryIndex');
    });

    Route::group(['prefix' => 'transaksi'], function() {
        Route::get('/', function() {
            return redirect('/');
        });

        Route::get('solar', [FuelController::class, 'index'])->name('fuel.index');
        Route::post('solar', [FuelController::class, 'store'])->name('fuel.store');
        Route::get('solar/{id}', [FuelController::class, 'show'])->name('fuel.show');
        Route::delete('solar/{id}', [FuelController::class, 'destroy'])->name('fuel.destroy');
        Route::put('solar/ubah-status/{id}', [FuelController::class, 'changeIsOpen'])->name('fuel.change-status');

        // Route::view('solar', 'Pages.HSDIndex');
        // Route::view('mutasi-hutang', 'Pages.DebtTransactionIndex');
    });

// });


Route::get('/test', [TestController::class, 'test']);
<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    BranchController,
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

        // Route::view('cabang', 'Pages.BranchIndex');
        Route::get('cabang', [BranchController::class, 'index'])->name('branch.index');
        Route::post('cabang', [BranchController::class, 'store'])->name('branch.store');
        Route::view('pengguna', 'Pages.UserIndex');
        Route::view('proyek', 'Pages.ProjectIndex');
        Route::view('kendaraan', 'Pages.VehicleIndex');
        Route::view('pengendara', 'Pages.DriverIndex');
        Route::view('material', 'Pages.MaterialIndex');
        Route::view('vendor', 'Pages.VendorIndex');
        Route::view('jenis-mutasi-hutang', 'Pages.DebtFlowCategoryIndex');
    });

    Route::group(['prefix' => 'transaksi'], function() {
        Route::get('/', function() {
            return redirect('/');
        });

        Route::view('solar', 'Pages.HSDIndex');
        Route::view('mutasi-hutang', 'Pages.DebtTransactionIndex');
    });

// });


Route::get('/test', [TestController::class, 'test']);
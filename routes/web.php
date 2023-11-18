<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/login')->with('status', 'Please login to continue.');
});

Auth::routes([
    'register' => false,
]);

Route::group(['middleware' => ['auth']], function() {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    // ROUTE PENGATURAN
    Route::view('pengaturan', 'pengaturan.index')->name('pengaturan');
    Route::prefix('pengaturan')->group(function () {
        Route::get('/wa', [App\Http\Controllers\WaController::class, 'index'])->name('pengaturan.wa');
        Route::get('/wa/get-wa-group', [App\Http\Controllers\WaController::class, 'get_group_wa'])->name('pengaturan.wa.get-group-wa');
        Route::patch('/wa/{group_wa}/update', [App\Http\Controllers\WaController::class, 'update'])->name('pengaturan.wa.update');

        Route::get('/akun', [App\Http\Controllers\PengaturanController::class, 'index'])->name('pengaturan.akun');
        Route::post('/akun/store', [App\Http\Controllers\PengaturanController::class, 'store'])->name('pengaturan.akun.store');
        Route::patch('/akun/{akun}/update', [App\Http\Controllers\PengaturanController::class, 'update'])->name('pengaturan.akun.update');
        Route::delete('/akun/{akun}/delete', [App\Http\Controllers\PengaturanController::class, 'destroy'])->name('pengaturan.akun.delete');
    });
    // END ROUTE PENGATURAN

    // ROUTE DB
    Route::view('db', 'db.index')->name('db');
    Route::prefix('db')->group(function () {
        Route::get('/customer', [App\Http\Controllers\CustomerController::class, 'index'])->name('db.customer');
        Route::post('/customer/store', [App\Http\Controllers\CustomerController::class, 'store'])->name('db.customer.store');
        Route::patch('/customer/{customer}/update', [App\Http\Controllers\CustomerController::class, 'update'])->name('db.customer.update');
        Route::patch('/customer/{customer}/update-harga', [App\Http\Controllers\CustomerController::class, 'update_harga'])->name('db.customer.update-harga');
        Route::delete('/customer/{customer}/delete', [App\Http\Controllers\CustomerController::class, 'destroy'])->name('db.customer.delete');

        Route::get('/investor', [App\Http\Controllers\InvestorController::class, 'index'])->name('db.investor');
        Route::post('/investor/store', [App\Http\Controllers\InvestorController::class, 'store'])->name('db.investor.store');
        Route::patch('/investor/{investor}/update', [App\Http\Controllers\InvestorController::class, 'update'])->name('db.investor.update');
        Route::delete('/investor/{investor}/delete', [App\Http\Controllers\InvestorController::class, 'destroy'])->name('db.investor.delete');

        Route::get('/supplier', [App\Http\Controllers\SupplierController::class, 'index'])->name('db.supplier');
        Route::post('/supplier/store', [App\Http\Controllers\SupplierController::class, 'store'])->name('db.supplier.store');
        Route::patch('/supplier/{supplier}/update', [App\Http\Controllers\SupplierController::class, 'update'])->name('db.supplier.update');
        Route::delete('/supplier/{supplier}/delete', [App\Http\Controllers\SupplierController::class, 'destroy'])->name('db.supplier.delete');

        Route::get('/rekening', [App\Http\Controllers\RekeningController::class, 'index'])->name('db.rekening');
        Route::patch('/rekening/{rekening}/update', [App\Http\Controllers\RekeningController::class, 'update'])->name('db.rekening.update');
    });

    // END ROUTE DB

    // ROUTE REKAP
    Route::view('rekap', 'rekap.index')->name('rekap');
    Route::prefix('rekap')->group(function() {
        Route::get('/kas-besar', [App\Http\Controllers\RekapController::class, 'kas_besar'])->name('rekap.kas-besar');
    });

    // END ROUTE REKAP

    // ROUTE BILLING
    Route::view('billing','billing.index')->name('billing');
    Route::prefix('billing')->group(function() {
        Route::get('/form-deposit/masuk', [App\Http\Controllers\FormDepositController::class, 'masuk'])->name('form-deposit.masuk');
        Route::post('/form-deposit/masuk/store', [App\Http\Controllers\FormDepositController::class, 'masuk_store'])->name('form-deposit.masuk.store');
        Route::get('/form-deposit/keluar', [App\Http\Controllers\FormDepositController::class, 'keluar'])->name('form-deposit.keluar');
        Route::post('/form-deposit/keluar/store', [App\Http\Controllers\FormDepositController::class, 'keluar_store'])->name('form-deposit.keluar.store');

        Route::get('billing/deviden', [App\Http\Controllers\FormDevidenController::class, 'index'])->name('billing.deviden.index');
        Route::post('billing/deviden/store', [App\Http\Controllers\FormDevidenController::class, 'store'])->name('billing.deviden.store');

        Route::get('/form-lain/masuk', [App\Http\Controllers\FormLainController::class, 'masuk'])->name('form-lain.masuk');
        Route::post('/form-lain/masuk/store', [App\Http\Controllers\FormLainController::class, 'masuk_store'])->name('form-lain.masuk.store');
        Route::get('/form-lain/keluar', [App\Http\Controllers\FormLainController::class, 'keluar'])->name('form-lain.keluar');
        Route::post('/form-lain/keluar/store', [App\Http\Controllers\FormLainController::class, 'keluar_store'])->name('form-lain.keluar.store');
    });

    // END ROUTE BILLING
});

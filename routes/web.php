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
        Route::get('/akun', [App\Http\Controllers\PengaturanController::class, 'index'])->name('pengaturan.akun');
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
    });

    // END ROUTE DB

    // ROUTE REKAP
    Route::view('rekap', 'rekap.index')->name('rekap');

    // END ROUTE REKAP

    // ROUTE BILLING
    Route::view('billing','billing.index')->name('billing');

    // END ROUTE BILLING
});

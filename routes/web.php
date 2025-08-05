<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\TransaksiController;

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

Route::prefix('barang')->name('barang.')->group(function () {
    Route::get('/', [BarangController::class, 'index'])->name('index');
    Route::get('/history', [BarangController::class, 'history'])->name('history');
    Route::post('/store', [BarangController::class, 'store'])->name('store');
    Route::delete('/{id}', [BarangController::class, 'destroy'])->name('destroy');
    Route::get('/restore/{id}', [BarangController::class, 'restore'])->name('restore');
    Route::delete('/forceDelete/{id}', [BarangController::class, 'forceDelete'])->name('forceDelete');
});

Route::prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/', [KasirController::class, 'index'])->name('index');
    Route::post('/store', [KasirController::class, 'store'])->name('store');
});

Route::prefix('transaksi')->name('transaksi.')->group(function () {
    Route::get('/', [TransaksiController::class, 'index'])->name('index');
    Route::post('/store', [TransaksiController::class, 'store'])->name('store');
    Route::delete('/{id}', [TransaksiController::class, 'destroy'])->name('destroy');
    Route::get('/history', [TransaksiController::class, 'history'])->name('history');
    Route::get('/restore/{id}', [TransaksiController::class, 'restore'])->name('restore');
    Route::delete('/forceDelete/{id}', [TransaksiController::class, 'forceDelete'])->name('forceDelete');
});

Route::get('/', function () {
    return view('Dashboard');
});

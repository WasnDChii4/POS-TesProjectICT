<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;

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

Route::get('/', function () {
    return view('Kasir');
});

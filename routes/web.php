<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return view('login');
})->name('login');

Route::post('/', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    // Kriteria Management
    Route::get('/admin/kriteria', [\App\Http\Controllers\KriteriaController::class, 'index'])->name('admin.kriteria');
    Route::post('/admin/kriteria/{id}', [\App\Http\Controllers\KriteriaController::class, 'update'])->name('admin.kriteria.update');
    Route::post('/admin/kriteria/skala/toggle', [\App\Http\Controllers\KriteriaController::class, 'toggleSkala'])->name('admin.kriteria.toggle-skala');
    Route::post('/admin/kriteria/skala/update', [\App\Http\Controllers\KriteriaController::class, 'updateSkala'])->name('admin.kriteria.update-skala');
    
    // User Management
    Route::get('/admin/user', [\App\Http\Controllers\UserController::class, 'index'])->name('admin.user');
    Route::post('/admin/user', [\App\Http\Controllers\UserController::class, 'store'])->name('admin.user.store');
    Route::post('/admin/user/{id}/update', [\App\Http\Controllers\UserController::class, 'update'])->name('admin.user.update');
    Route::post('/admin/user/{id}/delete', [\App\Http\Controllers\UserController::class, 'destroy'])->name('admin.user.delete');

    // Produk Management
    Route::get('/admin/produk', [\App\Http\Controllers\ProdukController::class, 'index'])->name('admin.produk');
    Route::post('/admin/produk', [\App\Http\Controllers\ProdukController::class, 'store'])->name('admin.produk.store');
    Route::post('/admin/produk/{id}/update', [\App\Http\Controllers\ProdukController::class, 'update'])->name('admin.produk.update');
    Route::post('/admin/produk/{id}/delete', [\App\Http\Controllers\ProdukController::class, 'destroy'])->name('admin.produk.delete');
    Route::post('/admin/produk/{id}/legalitas', [\App\Http\Controllers\ProdukController::class, 'updateLegalitas'])->name('admin.produk.legalitas');
});
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
});
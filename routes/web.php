<?php

use App\Http\Controllers\PosterController;
use Illuminate\Support\Facades\Route;

// --- Admin Panel (CRUD) ---
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/posters', [PosterController::class, 'index'])->name('posters.index');
    Route::post('/posters', [PosterController::class, 'store'])->name('posters.store');
    Route::delete('/posters', [PosterController::class, 'destroy'])->name('posters.destroy');
});

// --- Halaman Utama Papan Informasi ---
Route::get('/display', [PosterController::class, 'display'])->name('display.board');

// Redirect root ke display
Route::get('/', function () {
    return redirect()->route('display.board');
});
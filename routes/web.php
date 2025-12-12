<?php

use App\Http\Controllers\PosterController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Route yang dibuat oleh php artisan ui:auth (untuk /login, /register, dll)
Auth::routes(); 

// 1. --- Route DEFAULT /home ---
// Route ini harus tetap ada, dialihkan ke display setelah login.
Route::get('/home', function () {
    return redirect()->route('display.board');
})->name('home');


// 2. --- Admin Panel (CRUD) ---
// Hanya bisa diakses oleh user yang sudah login dan role-based redirect mengarahkan admin ke sini.
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/posters', [PosterController::class, 'index'])->name('posters.index');
    Route::post('/posters', [PosterController::class, 'store'])->name('posters.store');
    Route::delete('/posters', [PosterController::class, 'destroy'])->name('posters.destroy');
});


// 3. --- Halaman Utama Papan Informasi (Wajib Login) ---
// TAMBAHKAN MIDDLEWARE 'auth' DI SINI
Route::get('/display', [PosterController::class, 'display'])->name('display.board')->middleware('auth');


// 4. Route default: arahkan root (/) ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});
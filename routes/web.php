<?php

use App\Http\Controllers\PosterController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// 1. AUTHENTICATION ROUTES (Login, Logout, Register)
Auth::routes(); 

// 2. HOME ROUTE (Override Laravel Default)
// Diarahkan ke /display setelah login.
Route::get('/home', function () {
    return redirect()->route('display.board');
})->name('home');


// 3. ADMIN PANEL (Protected & Role-Based redirect target)
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/posters', [PosterController::class, 'index'])->name('posters.index');
    Route::post('/posters', [PosterController::class, 'store'])->name('posters.store');
    Route::delete('/posters', [PosterController::class, 'destroy'])->name('posters.destroy');
});


// 4. HALAMAN UTAMA DISPLAY BOARD (Wajib Login)
Route::get('/display', [PosterController::class, 'display'])->name('display.board')->middleware('auth');


// 5. ROUTE ROOT (/)
Route::get('/', function () {
    return redirect()->route('login');
});
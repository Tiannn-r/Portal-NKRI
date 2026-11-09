<?php

use App\Http\Controllers\AppLauncherController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\LandingPageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Portal Sistem Digital (Portal NKRI)
|--------------------------------------------------------------------------
*/

// Halaman Beranda / Landing Page Utama (Satu Halaman Scroll Terpadu)
Route::get('/', [LandingPageController::class, 'index'])->name('home');

// Autentikasi Tamu (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

// Fitur Pengguna Terautentikasi (Auth)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => redirect()->route('home'))->name('dashboard');
    Route::get('/apps/{slug}/open', [AppLauncherController::class, 'open'])->name('apps.open');
    Route::post('/apps/{application}/favorite', [FavoriteController::class, 'toggle'])->name('apps.favorite');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

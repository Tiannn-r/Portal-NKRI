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

// Autentikasi Tamu (Guest) - Satu-satunya Gerbang Masuk Sebelum Login
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

// Portal Intranet Terpadu Seluruh Divisi - Khusus Pengguna Terautentikasi (Opsi A: Kerahasiaan Penuh)
Route::middleware('auth')->group(function () {
    Route::get('/', [LandingPageController::class, 'index'])->name('home');
    Route::get('/dashboard', fn () => redirect()->route('home'))->name('dashboard');
    Route::get('/apps/{slug}/open', [AppLauncherController::class, 'open'])->name('apps.open');
    Route::post('/apps/{application}/favorite', [FavoriteController::class, 'toggle'])->name('apps.favorite');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

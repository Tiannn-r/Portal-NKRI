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

// Route Optimasi Kecepatan Produksi (Akses 1x di Browser)
Route::get('/speed-up', function () {
    \Illuminate\Support\Facades\Artisan::call('config:cache');
    \Illuminate\Support\Facades\Artisan::call('route:cache');
    \Illuminate\Support\Facades\Artisan::call('view:cache');
    \Illuminate\Support\Facades\Artisan::call('filament:cache-components');
    \Illuminate\Support\Facades\Artisan::call('icons:cache');
    return response('<h1>✅ Kecepatan Server Sukses Dioptimalkan!</h1><p>Cache konfigurasi, rute, template blade, dan komponen Filament telah dikunci di RAM server. Silakan kembali ke <a href="/login">Halaman Login</a>.</p>');
});

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

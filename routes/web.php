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

    // Coba buat symlink otomatis ke webroot portal jika memungkinkan di cPanel
    $target = storage_path('app/public');
    $link = base_path('../public_html/portal/storage');
    if (!file_exists($link) && file_exists(dirname($link))) {
        @symlink($target, $link);
    }

    return response('<h1>✅ Kecepatan Server Sukses Dioptimalkan!</h1><p>Cache konfigurasi, rute, template blade, dan komponen Filament telah dikunci di RAM server. Silakan kembali ke <a href="/login">Halaman Login</a>.</p>');
});

// Route Pelayan File Upload Storage (Otomatis & Kompatibel cPanel Shared Hosting Tanpa Symlink)
Route::get('/storage/{path}', function (string $path) {
    $filePath = storage_path('app/public/' . $path);
    if (!file_exists($filePath)) {
        abort(404);
    }

    $mimeType = match (strtolower(pathinfo($filePath, PATHINFO_EXTENSION))) {
        'png' => 'image/png',
        'jpg', 'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'webp' => 'image/webp',
        'ico' => 'image/x-icon',
        'pdf' => 'application/pdf',
        default => @mime_content_type($filePath) ?: 'application/octet-stream',
    };

    return response()->file($filePath, [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'public, max-age=604800, immutable',
    ]);
})->where('path', '.*')->name('storage.local');

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

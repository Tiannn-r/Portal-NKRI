<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AppLauncherController;
use App\Models\AppCategory;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman Beranda / Landing Page Utama (Presisi sesuai Desain UI Referensi)
Route::get('/', function () {
    $categories = AppCategory::orderBy('sort_order')->get(['id', 'name', 'slug']);
    
    $featuredApps = Application::with('category')
        ->where('status', 'active')
        ->orderBy('sort_order')
        ->take(3)
        ->get();

    return Inertia::render('Welcome', [
        'auth' => [
            'user' => Auth::user() ? [
                'id' => Auth::user()->id,
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ] : null,
        ],
        'categories' => $categories,
        'featuredApps' => $featuredApps,
    ]);
})->name('home');

// Autentikasi Tamu (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

// Fitur Pengguna Terautentikasi (Auth)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/apps/{slug}/open', [AppLauncherController::class, 'open'])->name('apps.open');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

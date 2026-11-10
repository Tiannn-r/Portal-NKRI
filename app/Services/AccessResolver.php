<?php

namespace App\Services;

use App\Models\Application;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class AccessResolver
{
    /**
     * Waktu simpan cache daftar aplikasi berizin (dalam detik).
     */
    protected const CACHE_TTL = 3600;

    /**
     * Evaluasi apakah seorang pengguna memiliki izin untuk mengakses sebuah aplikasi.
     *
     * Aturan Inti:
     * 1. Super Admin berhak mengakses semua sistem aktif.
     * 2. Sistem berstatus 'inactive' tidak boleh diakses oleh pengguna biasa.
     * 3. Pengguna memiliki akses jika terdaftar langsung pada sistem terkait (application_user / allowedUsers).
     * 4. Otorisasi kini langsung ditentukan per sistem (data sistem), bukan berdasarkan role atau departemen.
     */
    public function canAccess(User $user, Application $application): bool
    {
        // Sistem inactive tidak pernah boleh diakses user biasa
        if ($application->status === 'inactive' && !$user->hasRole('Super Admin')) {
            return false;
        }

        // Aturan 1: Super Admin boleh semua sistem
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Cek izin langsung User <-> Sistem (data sistem)
        $hasDirectAccess = $application->relationLoaded('allowedUsers')
            ? $application->allowedUsers->contains('id', $user->id)
            : $application->allowedUsers()->where('users.id', $user->id)->exists();

        return $hasDirectAccess;
    }

    /**
     * Ambil seluruh aplikasi berizin yang dapat diakses oleh user (untuk Dashboard "My Apps").
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, Application>
     */
    public function getAccessibleApps(User $user): Collection
    {
        $cacheKey = $this->getCacheKey($user->id);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($user) {
            // Super Admin melihat semua aplikasi yang tidak berstatus inactive
            if ($user->hasRole('Super Admin')) {
                return Application::with(['category', 'pic'])
                    ->where('status', '!=', 'inactive')
                    ->orderBy('sort_order')
                    ->get();
            }

            // Ambil semua aplikasi non-inactive beserta relasi pengguna berizin
            $apps = Application::with(['category', 'allowedUsers'])
                ->where('status', '!=', 'inactive')
                ->orderBy('sort_order')
                ->get();

            // Filter menggunakan logika AccessResolver canAccess
            return $apps->filter(fn (Application $app) => $this->canAccess($user, $app))->values();
        });
    }

    /**
     * Ambil seluruh katalog sistem aktif untuk ditampilkan di halaman Katalog.
     * Aplikasi yang belum berizin akan ditandai dengan flag is_locked = true.
     * Sistem inactive tidak pernah disertakan.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, Application>
     */
    public function getCatalogApps(User $user): Collection
    {
        $apps = Application::with(['category', 'allowedUsers'])
            ->where('status', '!=', 'inactive')
            ->orderBy('sort_order')
            ->get();

        return $apps->map(function (Application $app) use ($user) {
            $canAccess = $this->canAccess($user, $app);

            // Set atribut dinamis untuk konsumsi Vue Inertia
            $app->can_access = $canAccess;
            $app->is_locked = !$canAccess;

            return $app;
        });
    }

    /**
     * Hapus cache daftar aplikasi berizin milik pengguna (misal saat matriks akses diperbarui).
     */
    public function invalidateUserCache(int $userId): void
    {
        Cache::forget($this->getCacheKey($userId));
    }

    /**
     * Format nama key cache per user.
     */
    protected function getCacheKey(int $userId): string
    {
        return "user_accessible_apps_{$userId}";
    }
}

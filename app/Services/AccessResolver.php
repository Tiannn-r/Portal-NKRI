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
     * 1. Super Admin boleh semua sistem.
     * 2. Pengecualian application_user dengan type = 'deny' SELALU mengalahkan aturan lain (Deny Wins).
     * 3. Izin diberikan jika:
     *    - Ada pengecualian application_user dengan type = 'allow', ATAU
     *    - User memiliki minimal satu Role yang terhubung ke aplikasi, ATAU
     *    - Departemen user terhubung ke aplikasi.
     * 4. Sistem berstatus 'inactive' tidak boleh diakses oleh user biasa.
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

        // Load relasi pengecualian user jika belum di-load
        $userException = $application->userExceptions
            ? $application->userExceptions->firstWhere('id', $user->id)
            : $application->userExceptions()->where('users.id', $user->id)->first();

        // Aturan 3: Deny Wins - jika ada deny khusus, langsung tolak mutlak
        if ($userException && $userException->pivot->type === 'deny') {
            return false;
        }

        // Aturan 2a: Jika ada allow khusus perorangan
        if ($userException && $userException->pivot->type === 'allow') {
            return true;
        }

        // Aturan 2b: Cek apakah role user terhubung ke aplikasi
        $userRoleNames = $user->getRoleNames()->toArray();
        $hasMatchingRole = $application->roles
            ? $application->roles->contains(fn ($role) => in_array($role->name, $userRoleNames))
            : $application->roles()->whereIn('name', $userRoleNames)->exists();

        if ($hasMatchingRole) {
            return true;
        }

        // Aturan 2c: Cek apakah departemen user terhubung ke aplikasi
        if ($user->department_id) {
            $hasMatchingDept = $application->departments
                ? $application->departments->contains('id', $user->department_id)
                : $application->departments()->where('departments.id', $user->department_id)->exists();

            if ($hasMatchingDept) {
                return true;
            }
        }

        return false;
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

            // Ambil semua aplikasi non-inactive beserta relasi hak aksesnya
            $apps = Application::with(['category', 'roles', 'departments', 'userExceptions'])
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
        $apps = Application::with(['category', 'roles', 'departments', 'userExceptions'])
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

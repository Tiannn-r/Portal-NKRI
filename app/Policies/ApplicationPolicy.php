<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\User;

class ApplicationPolicy
{
    /**
     * Tentukan apakah user dapat melihat daftar sistem aplikasi.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Admin Sistem']);
    }

    /**
     * Tentukan apakah user dapat melihat detail sistem aplikasi.
     */
    public function view(User $user, Application $application): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Admin Sistem']);
    }

    /**
     * Tentukan apakah user dapat membuat sistem aplikasi baru.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Admin Sistem']);
    }

    /**
     * Tentukan apakah user dapat memperbarui data sistem aplikasi.
     */
    public function update(User $user, Application $application): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Admin Sistem']);
    }

    /**
     * Tentukan apakah user dapat menghapus sistem aplikasi.
     */
    public function delete(User $user, Application $application): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Admin Sistem']);
    }
}


<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    /**
     * Tentukan apakah user dapat melihat daftar role.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Admin Sistem']);
    }

    /**
     * Tentukan apakah user dapat melihat detail role.
     */
    public function view(User $user, Role $role): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Admin Sistem']);
    }

    /**
     * Tentukan apakah user dapat membuat role baru.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('Super Admin');
    }

    /**
     * Tentukan apakah user dapat memperbarui nama role.
     */
    public function update(User $user, Role $role): bool
    {
        // Role Super Admin tidak boleh diubah namanya
        if ($role->name === 'Super Admin') {
            return false;
        }

        return $user->hasRole('Super Admin');
    }

    /**
     * Tentukan apakah user dapat menghapus role.
     */
    public function delete(User $user, Role $role): bool
    {
        // Role Super Admin dilindungi dan tidak boleh dihapus
        if ($role->name === 'Super Admin') {
            return false;
        }

        return $user->hasRole('Super Admin');
    }
}


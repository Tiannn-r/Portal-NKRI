<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Admin Sistem']);
    }

    public function view(User $user, User $model): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Admin Sistem']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Admin Sistem']);
    }

    public function update(User $user, User $model): bool
    {
        // Cegah admin biasa mengubah status/role Super Admin lain jika bukan Super Admin
        if ($model->hasRole('Super Admin') && !$user->hasRole('Super Admin')) {
            return false;
        }

        return $user->hasAnyRole(['Super Admin', 'Admin Sistem']);
    }

    public function delete(User $user, User $model): bool
    {
        // Cegah menghapus diri sendiri atau Super Admin
        if ($model->id === $user->id || $model->hasRole('Super Admin')) {
            return false;
        }

        return $user->hasRole('Super Admin');
    }
}


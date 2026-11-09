<?php

namespace App\Policies;

use App\Models\AppCategory;
use App\Models\User;

class AppCategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Admin Sistem']);
    }

    public function view(User $user, AppCategory $category): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Admin Sistem']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Admin Sistem']);
    }

    public function update(User $user, AppCategory $category): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Admin Sistem']);
    }

    public function delete(User $user, AppCategory $category): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Admin Sistem']);
    }
}


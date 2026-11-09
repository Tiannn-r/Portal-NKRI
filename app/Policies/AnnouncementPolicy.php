<?php

namespace App\Policies;

use App\Models\Announcement;
use App\Models\User;

class AnnouncementPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Admin Konten', 'Admin Sistem']);
    }

    public function view(User $user, Announcement $announcement): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Admin Konten', 'Admin Sistem']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Admin Konten']);
    }

    public function update(User $user, Announcement $announcement): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Admin Konten']);
    }

    public function delete(User $user, Announcement $announcement): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Admin Konten']);
    }
}


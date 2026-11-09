<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman utama dashboard pengguna.
     */
    public function index(Request $request): Response
    {
        $user = $request->user()->load(['department', 'roles']);

        return Inertia::render('Dashboard', [
            'auth' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'department' => $user->department?->name ?? 'Lintas Departemen',
                    'department_code' => $user->department?->code ?? '-',
                    'roles' => $user->getRoleNames(),
                    'can_access_admin' => $user->hasAnyRole(['Super Admin', 'Admin Sistem', 'Admin Konten']),
                ],
            ],
        ]);
    }
}

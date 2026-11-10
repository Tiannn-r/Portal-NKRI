<?php

namespace App\Http\Controllers;

use App\Models\AppLaunchLog;
use App\Models\Application;
use App\Services\AccessResolver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AppLauncherController extends Controller
{
    public function __construct(
        protected AccessResolver $accessResolver
    ) {}

    /**
     * Memvalidasi hak akses, mencatat log peluncuran, dan mengarahkan pengguna ke sistem target.
     */
    public function open(Request $request, string $slug): RedirectResponse
    {
        $user = $request->user();

        // Cari aplikasi berdasarkan slug atau id
        $application = Application::where('slug', $slug)
            ->orWhere('id', $slug)
            ->firstOrFail();

        // 1. Verifikasi hak akses via AccessResolver tunggal
        if (!$this->accessResolver->canAccess($user, $application)) {
            abort(403, 'Anda tidak memiliki hak akses ke sistem ini. Silakan hubungi Administrator.');
        }

        // 2. Cek apakah sistem sedang dalam masa pemeliharaan (maintenance)
        if ($application->status === 'maintenance') {
            return redirect()->back()->with('error', "Sistem '{$application->name}' sedang dalam masa pemeliharaan (maintenance). Silakan coba lagi nanti.");
        }

        // 3. Catat riwayat peluncuran ke app_launch_logs
        AppLaunchLog::create([
            'user_id' => $user->id,
            'application_id' => $application->id,
            'launched_at' => now(),
        ]);

        // 4. Redirect aman ke URL sistem target (302)
        return redirect()->away($application->url);
    }
}

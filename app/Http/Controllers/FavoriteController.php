<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * Menambahkan atau menghapus aplikasi dari daftar favorit pengguna.
     */
    public function toggle(Request $request, Application $application): JsonResponse|RedirectResponse
    {
        $user = $request->user();

        $result = $user->favoriteApplications()->toggle($application->id);
        $isFavorited = count($result['attached']) > 0;

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_favorite' => $isFavorited,
                'application_id' => $application->id,
            ]);
        }

        return redirect()->back()->with('success', $isFavorited ? 'Aplikasi ditambahkan ke favorit.' : 'Aplikasi dihapus dari favorit.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\AppCategory;
use App\Models\Application;
use App\Models\Setting;
use App\Services\AccessResolver;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LandingPageController extends Controller
{
    public function __construct(
        protected AccessResolver $accessResolver
    ) {}

    /**
     * Tampilkan landing page terpadu satu halaman scroll.
     */
    public function index(Request $request): Response
    {
        $user = $request->user();

        // 1. Ambil Pengaturan Portal (Hero & Footer)
        $settings = [
            'hero_title' => Setting::get('hero_title', 'Sistem Informasi Terpadu Seluruh Divisi'),
            'hero_description' => Setting::get('hero_description', 'Gerbang peluncur tunggal instrumen kerja internal PT Nanggala Kencana Rekatama Indonesia. Akses terproteksi dan terenkripsi khusus karyawan terdaftar.'),
            'footer_text' => Setting::get('footer_text', 'PT Nanggala Kencana Rekatama Indonesia. Sistem Internal — Klasifikasi: Terbatas & Rahasia.'),
        ];

        // 2. Ambil Pengumuman Aktif yang Terbit
        $announcements = Announcement::where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->latest()
            ->get(['id', 'title', 'body', 'created_at']);

        // 3. Ambil Seluruh Sistem Digital Aktif
        $allApps = Application::with(['category', 'allowedUsers'])
            ->where('status', '!=', 'inactive')
            ->orderBy('sort_order')
            ->get();

        $favoriteIds = $user
            ? $user->favoriteApplications()->pluck('applications.id')->toArray()
            : [];

        // 4. Filter Otorisasi: HANYA sistem yang diberi hak akses yang dikirim & ditampilkan ke pengguna
        $accessibleApps = $allApps->filter(function (Application $app) use ($user) {
            return $user ? $this->accessResolver->canAccess($user, $app) : false;
        })->values();

        $systems = $accessibleApps->map(function (Application $app) use ($favoriteIds) {
            return [
                'id' => $app->id,
                'name' => $app->name,
                'slug' => $app->slug,
                'description' => $app->description,
                'icon' => $app->icon,
                'status' => $app->status,
                'login_hint' => $app->login_hint,
                'open_in_new_tab' => $app->open_in_new_tab,
                'category' => $app->category ? [
                    'id' => $app->category->id,
                    'name' => $app->category->name,
                    'slug' => $app->category->slug,
                ] : null,
                'can_access' => true,
                'is_locked' => false,
                'is_favorite' => in_array($app->id, $favoriteIds),
            ];
        });

        // 5. Kategori Sistem: Hanya tampilkan kategori yang memiliki sistem berizin bagi pengguna
        $accessibleCategoryIds = $accessibleApps->pluck('app_category_id')->unique()->filter();
        $categories = AppCategory::whereIn('id', $accessibleCategoryIds)
            ->orderBy('sort_order')
            ->get(['id', 'name', 'slug']);

        // 6. Statistik: Jumlah sistem yang tersedia bagi pengguna
        $accessibleCount = $systems->count();
        $totalActiveCount = $allApps->count();

        return Inertia::render('Welcome', [
            'auth' => [
                'user' => $user ? [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'department' => $user->department?->name ?? 'Lintas Departemen',
                    'department_code' => $user->department?->code ?? '-',
                    'roles' => $user->getRoleNames(),
                    'is_super_admin' => $user->hasRole('Super Admin'),
                ] : null,
            ],
            'settings' => $settings,
            'announcements' => $announcements,
            'categories' => $categories,
            'systems' => $systems,
            'stats' => [
                'accessible_count' => $accessibleCount,
                'total_count' => $totalActiveCount,
            ],
        ]);
    }
}


# Arsitektur Sistem & Alur Kerja (System Architecture)

Dokumen ini menjelaskan struktur teknis, arsitektur data, dan alur request pada **Portal Sistem Digital (Portal NKRI)**.

---

## 1. Diagram Arsitektur Tingkat Tinggi

```
+-----------------------------------------------------------------------------------+
|                                 CLIENT BROWSER                                    |
|                                                                                   |
|  +----------------------------------+     +------------------------------------+  |
|  |     Halaman User (Inertia + Vue) |     |      Panel Admin (Filament PHP)    |  |
|  |     - Dashboard "My Apps"        |     |      - Kelola Sistem & Kategori    |  |
|  |     - Katalog Sistem             |     |      - Matriks Akses (Role/Dept)   |  |
|  |     - Buka Aplikasi (/apps/... ) |     |      - Pengguna & Departemen       |  |
|  |     - Banner Pengumuman          |     |      - Audit Log & Pengumuman      |  |
|  +------------------+---------------+     +-----------------+------------------+  |
+---------------------|---------------------------------------|---------------------+
                      |                                       |
                      | HTTP / Inertia Protocol               | HTTP Livewire
                      v                                       v
+---------------------+---------------------------------------+---------------------+
|                                 LARAVEL BACKEND                                   |
|                                                                                   |
|  [ Middleware Pipeline ] -> Authenticate, RateLimiter, Spatie Role/Permission     |
|                                                                                   |
|  +-------------------------------------+  +------------------------------------+  |
|  | User Controllers (Inertia Response) |  | Filament Admin Resources           |  |
|  | - DashboardController               |  | - ApplicationResource              |  |
|  | - CatalogController                 |  | - UserResource / DepartmentResource|  |
|  | - AppLauncherController             |  | - AnnouncementResource             |  |
|  +------------------+------------------+  +-----------------+------------------+  |
|                     |                                       |                     |
|                     v                                       v                     |
|  +-----------------------------------------------------------------------------+  |
|  |                       SERVICES & BUSINESS LOGIC LAYER                       |  |
|  |                                                                             |  |
|  |   * App\Services\AccessResolver                                             |  |
|  |     -> canAccess(User $user, Application $app): bool                        |  |
|  |     -> getAccessibleApps(User $user): Collection                            |  |
|  |     -> getCatalogApps(User $user): Collection                               |  |
|  |                                                                             |  |
|  |   * App\Services\AppLauncherService                                         |  |
|  |     -> launch(User $user, string $slug): string (Target URL)                |  |
|  |     -> recordLog(User $user, Application $app): void                        |  |
|  +-----------------------------------------------------------------------------+  |
|                                         |                                         |
|                                         v                                         |
|  +-----------------------------------------------------------------------------+  |
|  |                           DATA & PERSISTENCE LAYER                          |  |
|  |                                                                             |  |
|  |   * Eloquent Models: User, Department, Application, AppCategory,            |  |
|  |     Announcement, AppLaunchLog, UserFavorite                                |  |
|  |   * Cache Layer: Cache tags / keys for user app lists (Redis/Database)      |  |
|  |   * MySQL Database: Foreign key indexing, compound keys, soft deletes       |  |
|  +-----------------------------------------------------------------------------+  |
+-----------------------------------------------------------------------------------+
```

---

## 2. Alur Peluncuran Aplikasi (App Launch Flow)

Ketika pengguna mengklik kartu sistem di dashboard:

1. **Trigger UI:** Tombol *"Buka"* memanggil tautan `GET /apps/{slug}/open` dengan atribut `target="_blank" rel="noopener noreferrer"`.
2. **Routing & Middleware:**
   * Masuk ke route `apps.open` yang dilindungi middleware `auth`.
3. **Controller Execution (`AppLauncherController@open`):**
   * Mengambil model `Application` berdasarkan `slug` (aktif atau maintenance).
   * Memanggil `AccessResolver::canAccess(Auth::user(), $application)`.
4. **Validasi Hak Akses:**
   * Jika user **Super Admin**, akses otomatis diberikan (`true`).
   * Jika ada baris di `application_user` dengan `type = 'deny'`, akses langsung diblokir (`false`).
   * Jika user memiliki Role yang berelasi dengan aplikasi, akses diberikan (`true`).
   * Jika Departemen user berelasi dengan aplikasi, akses diberikan (`true`).
   * Jika ada izin perorangan `type = 'allow'`, akses diberikan (`true`).
   * Jika seluruh kondisi di atas tidak terpenuhi, akses ditolak (`false`).
5. **Handling Hasil:**
   * **Jika Ditolak:** Lempar `Abort(403, 'Anda tidak memiliki hak akses ke sistem ini.')`.
   * **Jika Sedang Maintenance:** Redirect kembali dengan pesan notifikasi error *"Sistem sedang dalam masa pemeliharaan"*.
   * **Jika Disetujui:**
     1. Simpan baris baru pada tabel `app_launch_logs` (`user_id`, `application_id`, `launched_at = now()`).
     2. Update timestamp cache atau data terakhir dibuka.
     3. Lakukan `Redirect::away($application->url)`.
6. **Sistem Tujuan:** Browser membuka tab baru menuju URL aplikasi tujuan. Pengguna login ke aplikasi tujuan secara independen.

---

## 3. Strategi Caching & Invalidasi Real-Time
Untuk menjaga performa tetap ringan dan bebas lag:
* Daftar aplikasi berizin milik pengguna di-cache dengan key: `user_apps_{user_id}`.
* Invalidasi cache dilakukan otomatis melalui Observer / Event Listener pada model:
  * `Application` (saat nama, url, status, atau kategori berubah).
  * `ApplicationRole`, `ApplicationDepartment`, `ApplicationUser` (saat matriks hak akses dimodifikasi).
  * `User` (saat departemen atau role user diperbarui).

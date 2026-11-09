# Portal NKRI — Portal Sistem Digital Terpadu
**PT Nanggala Kencana Rekatama Indonesia**

Sistem intranet portal internal korporat yang menampung seluruh instrumen digital perusahaan untuk **seluruh divisi**.
Berperan sebagai **LAUNCHER** resmi (tanpa SSO). Karyawan masuk ke portal terproteksi, melihat sistem yang memiliki izin akses sesuai divisinya, mengklik tombol *"Buka Sistem"*, lalu sistem target terbuka di tab baru (`target="_blank" rel="noopener noreferrer"`). Karyawan login secara mandiri di sistem tujuan untuk menjaga integritas keamanan per sistem.

---

## 🛠️ Tech Stack Mandat
* **Backend:** Laravel 11.x, PHP 8.4+, MySQL (Collation: `utf8mb4_unicode_ci`)
* **Frontend User:** Vue 3 (Composition API, `<script setup>`) + Inertia.js + Tailwind CSS + Vite
* **Admin CMS:** Filament 3.x (terpisah total di `/admin` dengan auth guard terisolasi)
* **Role & Permission:** `spatie/laravel-permission`
* **Audit Logging:** `spatie/laravel-activitylog`
* **Icons & Typography:** `lucide-vue-next` & Plus Jakarta Sans (`@fontsource/plus-jakarta-sans`)
* **Design Token:** Merah Putih Korporat (`primary: #B91C1C`, `primary-hover: #991B1B`, `primary-soft: #FEF2F2`, `primary-ring: #FCA5A5`)

---

## 📚 Dokumentasi Arsitektur
* [`docs/DECISIONS.md`](./docs/DECISIONS.md) — Keputusan produk, ruang lingkup MVP, peran, token warna, anti-pattern.
* [`docs/ARCHITECTURE.md`](./docs/ARCHITECTURE.md) — Alur peluncuran aplikasi, AccessResolver, strategi cache.
* [`docs/DATABASE_SCHEMA.md`](./docs/DATABASE_SCHEMA.md) — Struktur tabel, indeks, foreign keys, relasi matriks akses.
* [`docs/DEPLOYMENT_CHECKLIST.md`](./docs/DEPLOYMENT_CHECKLIST.md) — Panduan deploy produksi, konfigurasi Nginx HTTPS, dan checklist go-live.
* [`AGENTS.md`](./AGENTS.md) — Panduan operasional dan arsitektur pengembangan bertahap.

---

## 🔐 Model Otorisasi Inti (`AccessResolver`)
Seluruh pengecekan hak akses sistem terpusat pada satu class tunggal `App\Services\AccessResolver`:
1. **Super Admin:** Berhak mengakses seluruh sistem digital.
2. **Izin Akses:** Diberikan jika `(User memiliki role yang terhubung) OR (Departemen user terhubung) OR (Pengecualian application_user type = 'allow')`.
3. **Deny Wins:** Pengecualian `application_user` dengan `type = 'deny'` **SELALU** membatalkan semua izin lain secara mutlak.
4. **Sistem Inactive:** Tidak pernah dikirimkan atau ditampilkan kepada pengguna biasa.
5. **Sistem Maintenance:** Tampil dengan tombol dinonaktifkan (*Sedang Maintenance*).
6. **Peluncuran Aman:** Rute `GET /apps/{slug}/open` memverifikasi otorisasi di sisi backend, mencatat riwayat ke `app_launch_logs`, dan mengarahkan ke URL target.

---

## 🚀 Memulai (Local Development)

### 1. Prasyarat
* PHP 8.4+ dengan ekstensi `pdo_mysql`, `intl`, `exif`, `gd`, `zip`
* Composer 2.x
* Node.js 20+ & npm
* MySQL 8.0+

### 2. Instalasi & Konfigurasi
```bash
# Clone repository
git clone https://github.com/Tiannn-r/Portal-NKRI.git
cd Portal-NKRI

# Install dependensi
composer install
npm install

# Environment
cp .env.example .env
php artisan key:generate

# Migrasi database & seeding data awal
php artisan migrate --seed

# Kompilasi frontend
npm run build
```

### 3. Akun Pengujian (Seeded Accounts)
| Peran (Role) | Alamat Email | Kata Sandi |
|---|---|---|
| **Super Admin** | `admin@nkri.co.id` | `PortalPassword123!` |
| **Admin Sistem** | `budi.it@nkri.co.id` | `UserPassword123!` |
| **Admin Konten** | `siti.hr@nkri.co.id` | `UserPassword123!` |
| **User Keuangan** | `andi.fin@nkri.co.id` | `UserPassword123!` |
| **User Operasional** | `dewi.ops@nkri.co.id` | `UserPassword123!` |
| **User Pemasaran** | `eko.mkt@nkri.co.id` | `UserPassword123!` |

> **Catatan Akses Admin:** Panel CMS Admin hanya dapat diakses melalui URL `/admin` dan wajib login menggunakan akun ber-role `Super Admin`.

---

## 🧪 Pengujian Otomatis (Automated Tests)
Jalankan seluruh rangkaian test suite (32 pengujian, 93 assertions):
```bash
php artisan test
```

Database pengujian telah terisolasi menggunakan SQLite in-memory, sehingga eksekusi testing tidak akan menghapus atau mengubah data pada MySQL lokal Anda.

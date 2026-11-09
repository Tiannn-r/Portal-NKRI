# Portal NKRI - Portal Sistem Digital Perusahaan

Website portal internal korporat yang menampung seluruh sistem digital perusahaan.
Berperan sebagai **LAUNCHER** (tanpa SSO). Pengguna masuk ke portal, melihat kartu sistem yang berhak diakses, mengklik tombol *"Buka"*, lalu sistem target terbuka di tab baru (`target="_blank"`). Pengguna login secara mandiri di sistem tujuan.

---

## 🛠️ Tech Stack
* **Backend:** Laravel 11.x, PHP 8.4+, MySQL
* **Frontend User:** Vue 3 (Composition API, `<script setup>`) + Inertia.js + Tailwind CSS + Vite
* **Admin CMS:** Filament 3.x
* **Role & Permission:** `spatie/laravel-permission`
* **Audit Logging:** `spatie/laravel-activitylog`
* **Icons & Typography:** `lucide-vue-next` & Plus Jakarta Sans

---

## 📚 Dokumentasi
Seluruh spesifikasi dan keputusan arsitektur terdokumentasi pada folder `docs/`:
* [`docs/DECISIONS.md`](./docs/DECISIONS.md) — Keputusan produk, ruang lingkup MVP, peran, token warna, anti-pattern.
* [`docs/ARCHITECTURE.md`](./docs/ARCHITECTURE.md) — Alur peluncuran aplikasi, AccessResolver, strategi cache.
* [`docs/DATABASE_SCHEMA.md`](./docs/DATABASE_SCHEMA.md) — Struktur tabel, indeks, foreign keys, relasi.
* [`AGENTS.md`](./AGENTS.md) — Panduan bagi engineer / AI agen untuk pengembangan bertahap.

---

## 🚀 Memulai (Local Development)

### 1. Prasyarat
* PHP 8.2+ dengan ekstensi `pdo_mysql`, `intl`, `exif`, `gd`, `zip`
* Composer 2.x
* Node.js 18+ & npm
* MySQL / MariaDB

### 2. Instalasi
```bash
# Clone repository
git clone https://github.com/Tiannn-r/Portal-NKRI.git
cd Portal-NKRI

# Install dependensi backend
composer install

# Install dependensi frontend
npm install

# Konfigurasi environment
cp .env.example .env
php artisan key:generate

# Migrasi database
php artisan migrate

# Jalankan server
php artisan serve
npm run dev
```
Akses halaman user di `http://localhost:8000` dan panel admin di `http://localhost:8000/admin`.

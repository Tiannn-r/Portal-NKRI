# AGENTS.md - Portal Sistem Digital (Portal NKRI)

Panduan operasional dan arsitektur bagi agen AI dan developer yang bekerja pada repositori ini.

---

## 1. Identitas & Vibe Coding Rules
* **Tujuan Proyek:** Website portal internal korporat yang menampung seluruh sistem digital perusahaan.
* **Peran Sistem:** Sebagai **LAUNCHER**, **TANPA SSO**. Pengguna masuk ke portal, melihat kartu sistem yang memiliki izin akses, mengklik tombol *"Buka"*, lalu sistem terbuka di tab baru (`target="_blank"`). Pengguna login ulang pada sistem tersebut.
* **Prinsip Kode:** Sederhana, rapi, terstruktur, mudah dilacak (*traceable*). Jangan over-engineering. Logika bisnis di Service/Action, controller tetap tipis, validasi selalu lewat FormRequest.
* **Dokumentasi Lengkap:** Rujukan keputusan produk berada di folder `docs/`:
  - `docs/DECISIONS.md` (Keputusan arsitektur & batasan lingkup)
  - `docs/ARCHITECTURE.md` (Alur kerja sistem & security)
  - `docs/DATABASE_SCHEMA.md` (Rancangan skema database lengkap)

---

## 2. Tech Stack Mandat
- **Backend:** Laravel 11.x, PHP 8.4+, MySQL
- **Frontend User:** Vue 3 (Composition API, `<script setup>`) + Inertia.js + Tailwind CSS + Vite
- **Admin CMS:** Filament 3.x (terpisah total dari halaman user publik)
- **Role & Permission:** `spatie/laravel-permission`
- **Audit Logging:** `spatie/laravel-activitylog`
- **Cache / Session / Queue:** Redis (fallback database di lokal)
- **Ikon:** `lucide-vue-next` (Dilarang menggunakan emoji sebagai ikon UI)
- **Komponen:** `shadcn-vue` diimpor per komponen seperlunya

---

## 3. Logika Inti Akses (`AccessResolver`)
Seluruh pengecekan izin akses WAJIB melalui class tunggal `App\Services\AccessResolver`:
1. `Super Admin` boleh semua sistem.
2. Sistem boleh diakses jika: (User memiliki role yang terhubung) ATAU (Departemen user terhubung) ATAU (Ada record `application_user` dengan `type = 'allow'`).
3. Record `application_user` dengan `type = 'deny'` **SELALU** membatalkan semua izin lain (*Deny wins*).
4. Sistem berstatus `inactive` tidak pernah dikirimkan ke user biasa. Status `maintenance` tampil dengan tombol peluncuran dinonaktifkan (*Sedang maintenance*).
5. Semua verifikasi dilakukan di backend pada route `GET /apps/{slug}/open`. Tautan keluar menggunakan `rel="noopener noreferrer"`.

---

## 4. Standar Desain: Merah Putih Korporat & Elegan
- **Palet Warna Utama:**
  - `primary`: `#B91C1C`
  - `primary-hover`: `#991B1B`
  - `primary-soft`: `#FEF2F2`
  - `primary-ring`: `#FCA5A5`
  - `background`: `#FFFFFF`
  - `surface`: `#FAFAFA`
  - `border`: `#E5E7EB`
  - `text`: `#111827`
  - `text-muted`: `#4B5563`
  - `text-subtle`: `#6B7280`
- **Prinsip Warna:** Merah dipakai secara hemat dan selektif (aksen, tombol aksi utama, fokus keyboard). Dominan putih dan abu-abu netral.
- **Tipografi:** Plus Jakarta Sans (`@fontsource/plus-jakarta-sans`, bobot 400, 500, 600), fallback `system-ui`.
- **Anti-Pattern (Dilarang):**
  - Dilarang menggunakan gradasi warna ungu/biru atau dekorasi warna-warni (*AI slop*).
  - Dilarang efek glassmorphism atau blur tebal.
  - Dilarang emoji sebagai icon tombol atau judul.
  - Dilarang kartu statistik palsu dan kalimat marketing kosong.

---

## 5. Alur Kerja Bertahap (Step-by-Step Workflow)
Pengembangan dilakukan secara bertahap dan wajib berhenti untuk konfirmasi setelah setiap langkah:
* **Langkah 0:** Inisialisasi project, `/docs`, `AGENTS.md`, dan konfigurasi dasar.
* **Langkah 1:** Migration, model, relasi, seeder (role, super admin dari .env, data sistem realistis).
* **Langkah 2:** Autentikasi (Breeze/Inertia Vue), role & permission.
* **Langkah 3:** `AccessResolver` + automated testing (Pest/PHPUnit).
* **Langkah 4:** Panel admin Filament (Sistem, Kategori, User, Role, Departemen, Matriks Akses, Pengumuman, Audit Log).
* **Langkah 5:** Halaman user (Dashboard My Apps, Katalog, Favorit, Terakhir Dibuka, Route `/apps/{slug}/open`).
* **Langkah 6:** Pengumuman di dashboard, audit log, polesan UI.
* **Langkah 7:** Test akhir, evaluasi performa query (bebas N+1), checklist deploy.

Setiap selesai satu langkah, buat commit Git dengan pesan yang deskriptif dan buat laporan ringkas.

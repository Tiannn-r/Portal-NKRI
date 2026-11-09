# Ringkasan Keputusan & Spesifikasi Produk (Decisions Log)

Dokumen ini merangkum seluruh keputusan arsitektur, batasan lingkup (scope), aturan bisnis, dan panduan desain untuk pengembangan **Portal Sistem Digital Perusahaan (Portal NKRI)**.

---

## 1. Peran & Vibe Coding Principles
* **Filosofi Pengembangan:** Kode dibuat sederhana, terstruktur, rapi, dan mudah dilacak (*traceable*) agar pemilik proyek memahami gambaran besar arsitektur dan dapat mendeteksi atau menelusuri bug secara mandiri.
* **Komentar Kode:** Singkat, padat, dalam Bahasa Indonesia pada logika bisnis yang memerlukan konteks khusus. Penamaan class, method, variabel tetap menggunakan Bahasa Inggris standar.

---

## 2. Tujuan Produk & Model Bisnis
* **Peran Sistem:** Sebagai **LAUNCHER**, **TANPA SSO** (*Single Sign-On*).
* **Alur Pengguna:**
  1. Pengguna login ke Portal.
  2. Melihat kartu sistem digital yang memiliki izin akses (My Apps) atau seluruh katalog sistem.
  3. Mengklik tombol **"Buka"** pada sistem yang berizin.
  4. Portal memvalidasi hak akses di backend, mencatat riwayat peluncuran (*launch logs*), lalu membuka URL sistem terkait di tab baru (`target="_blank" rel="noopener noreferrer"`).
  5. Pengguna melakukan login ulang pada sistem tujuan sesuai kredensial masing-masing sistem (ini disengaja sesuai kebutuhan bisnis).
* **Peran Admin:** Mengatur konten portal, katalog sistem, kategori, data departemen, dan matriks hak akses pengguna via CMS panel Filament.

---

## 3. Technology Stack (Wajib)
| Layer | Teknologi & Versi | Catatan |
|---|---|---|
| **Backend** | Laravel 11.x, PHP 8.4+, MySQL | Arsitektur Service/Action class, Controller tipis, FormRequest |
| **User Frontend** | Vue 3 (`<script setup>`, Composition API) + Inertia.js | Render SPA via Inertia tanpa router terpisah |
| **Styling & Icons** | Tailwind CSS + `lucide-vue-next` | Token warna CSS variable khusus Merah-Putih korporat |
| **Font** | Plus Jakarta Sans (`@fontsource/plus-jakarta-sans`) | Bobot 400, 500, 600 saja |
| **Admin Panel** | Filament 3.x | Dipisah sepenuhnya dari halaman user publik |
| **Role & Permission** | `spatie/laravel-permission` | RBAC dengan granular model permissions |
| **Audit Log** | `spatie/laravel-activitylog` | Pencatatan otomatis aksi admin & perubahan matriks |
| **Cache & Queue** | Database (Lokal) / Redis (Produksi) | Cache daftar aplikasi per user dengan invalidasi real-time |

---

## 4. Ruang Lingkup (Scope)

### ✅ Dalam Lingkup (MVP)
1. **Autentikasi & Keamanan:** Login portal dengan rate limiting ketat dan kebijakan password aman.
2. **Dashboard "My Apps":** Kartu sistem yang boleh diakses, pencarian real-time, filter kategori, penanda favorit (*bookmarks*), dan daftar aplikasi yang terakhir dibuka (*recently opened*).
3. **Katalog Sistem:** Menampilkan semua sistem aktif perusahaan; sistem yang belum memiliki hak akses ditampilkan dengan status terkunci (ikon gembok, tanpa tombol buka, tanpa tombol permohonan akses).
4. **Admin CMS (Filament):** Manajemen Aplikasi, Kategori, Departemen, Pengguna, Roles, Matriks Akses, Pengumuman, dan Audit Log (Read-only).
5. **Pengumuman Internal:** Banner/komponen pengumuman sederhana di dashboard pengguna.
6. **Pelacakan Audit & Launch:** Pencatatan `app_launch_logs` saat aplikasi dibuka, serta pencatatan audit log Spatie untuk aktivitas admin.

### 🚫 Di Luar Lingkup (JANGAN Dibuat pada MVP)
* SSO / OAuth / integrasi token lintas sistem.
* Alur permohonan akses dan persetujuan (*request access & approval workflow*).
* Pusat bantuan (*help center* / ticketing).
* Pengecekan otomatis status uptime sistem (health check bot).
* Notifikasi push / email dinamis.
* Changelog sistem.
* Autentikasi dua faktor (2FA).
* Dashboard analitik metrik kompleks.

---

## 5. Aturan Akses Tunggal (`AccessResolver`)
Logika hak akses dipusatkan secara eksklusif pada class `App\Services\AccessResolver`:
1. **Super Admin:** Berhak mengakses seluruh aplikasi tanpa pengecualian.
2. **Aturan Dasar Izin:** Sebuah aplikasi diizinkan untuk dibuka jika:
   * Pengguna memiliki minimal satu Role yang terhubung ke aplikasi (`application_role`), **ATAU**
   * Departemen pengguna terhubung ke aplikasi (`application_department`), **ATAU**
   * Terdapat izin khusus perorangan dengan status `type = 'allow'` (`application_user`).
3. **Pengecualian Absolut (Deny Wins):** Jika terdapat data pada `application_user` dengan `type = 'deny'`, akses **SEKETIKA DITOLAK**, mengesampingkan izin dari Role maupun Departemen.
4. **Status Operasional Sistem:**
   * Status `inactive`: Tidak pernah dikirimkan atau ditampilkan kepada pengguna non-admin.
   * Status `maintenance`: Tampil pada katalog/dashboard, namun tombol peluncuran dinonaktifkan dengan label status *"Sedang maintenance"*.
5. **Enforcement Backend:** Endpoint `GET /apps/{slug}/open` menjalankan validasi `AccessResolver` sebelum mengarahkan pengguna (`302 Redirect`). Tombol tersembunyi di Vue semata-mata untuk UI/UX, bukan pengaman data.

---

## 6. Desain Visual: Merah Putih Korporat & Elegan
* **Karakter Visual:** Portal internal yang tenang, rapi, bersih, dan berwibawa. Bukan tampilan startup kasual.
* **Token Warna:**
  * `primary`: `#B91C1C` (Merah korporat elegan)
  * `primary-hover`: `#991B1B`
  * `primary-soft`: `#FEF2F2` (Background aksen tipis)
  * `primary-ring`: `#FCA5A5` (Focus outline ring)
  * `background`: `#FFFFFF`
  * `surface`: `#FAFAFA`
  * `border`: `#E5E7EB`
  * `text`: `#111827`
  * `text-muted`: `#4B5563`
  * `text-subtle`: `#6B7280`
* **Prinsip Warna:** Merah hanya digunakan sebagai aksen hemat (tombol utama, status aktif, fokus keyboard), bukan blok warna besar.
* **Anti-Pattern (Dilarang):** Tanpa gradasi ungu/pink, tanpa efek glassmorphism, tanpa bayangan tebal, tanpa emoji sebagai icon, tanpa kartu statistik palsu, dan tanpa teks marketing kosong.

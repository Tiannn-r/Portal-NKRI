# Skema Basis Data (Database Schema Blueprint)

Dokumen ini mendefinisikan seluruh tabel, tipe data, kunci primer, kunci asing, dan indeks untuk MVP **Portal Sistem Digital**.

---

## 1. Tabel Inti Pengguna & Organisasi

### `departments`
* `id` : BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
* `name` : VARCHAR(255) NOT NULL
* `code` : VARCHAR(50) UNIQUE NOT NULL (Contoh: 'IT', 'HRD', 'FIN')
* `description` : TEXT NULLABLE
* `created_at`, `updated_at` : TIMESTAMP

### `users`
* `id` : BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
* `name` : VARCHAR(255) NOT NULL
* `email` : VARCHAR(255) UNIQUE NOT NULL
* `password` : VARCHAR(255) NOT NULL
* `department_id` : BIGINT UNSIGNED NULLABLE, FOREIGN KEY REFERENCES `departments(id)` ON DELETE SET NULL
* `is_active` : BOOLEAN DEFAULT TRUE (indeks)
* `remember_token` : VARCHAR(100) NULLABLE
* `created_at`, `updated_at` : TIMESTAMP
* *Indeks:* `index(['department_id', 'is_active'])`

---

## 2. Tabel Katalog & Kategori Aplikasi

### `app_categories`
* `id` : BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
* `name` : VARCHAR(255) NOT NULL
* `slug` : VARCHAR(255) UNIQUE NOT NULL
* `sort_order` : INT UNSIGNED DEFAULT 0 (indeks)
* `created_at`, `updated_at` : TIMESTAMP

### `applications`
* `id` : BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
* `name` : VARCHAR(255) NOT NULL
* `slug` : VARCHAR(255) UNIQUE NOT NULL
* `description` : VARCHAR(500) NULLABLE
* `icon` : VARCHAR(255) NULLABLE (Path file gambar atau identifier ikon Lucide)
* `url` : VARCHAR(2048) NOT NULL (Validasi skema http:// atau https://)
* `app_category_id` : BIGINT UNSIGNED NOT NULL, FOREIGN KEY REFERENCES `app_categories(id)` ON DELETE RESTRICT
* `pic_user_id` : BIGINT UNSIGNED NULLABLE, FOREIGN KEY REFERENCES `users(id)` ON DELETE SET NULL
* `status` : ENUM('active', 'maintenance', 'inactive') DEFAULT 'active' (indeks)
* `sort_order` : INT UNSIGNED DEFAULT 0 (indeks)
* `open_in_new_tab` : BOOLEAN DEFAULT TRUE
* `login_hint` : VARCHAR(255) NULLABLE (Contoh: "Login dengan akun Odoo Anda")
* `created_at`, `updated_at` : TIMESTAMP
* *Indeks:* `index(['status', 'sort_order'])`, `index(['app_category_id', 'status'])`

---

## 3. Matriks Hak Akses (Access Control Tables)

### `application_role` (Pivot Aplikasi & Role Spatie)
* `id` : BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
* `application_id` : BIGINT UNSIGNED NOT NULL, FOREIGN KEY REFERENCES `applications(id)` ON DELETE CASCADE
* `role_id` : BIGINT UNSIGNED NOT NULL, FOREIGN KEY REFERENCES `roles(id)` ON DELETE CASCADE
* `created_at`, `updated_at` : TIMESTAMP
* *Indeks Unik:* `unique(['application_id', 'role_id'])`

### `application_department` (Pivot Aplikasi & Departemen)
* `id` : BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
* `application_id` : BIGINT UNSIGNED NOT NULL, FOREIGN KEY REFERENCES `applications(id)` ON DELETE CASCADE
* `department_id` : BIGINT UNSIGNED NOT NULL, FOREIGN KEY REFERENCES `departments(id)` ON DELETE CASCADE
* `created_at`, `updated_at` : TIMESTAMP
* *Indeks Unik:* `unique(['application_id', 'department_id'])`

### `application_user` (Pengecualian Khusus Per User)
* `id` : BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
* `application_id` : BIGINT UNSIGNED NOT NULL, FOREIGN KEY REFERENCES `applications(id)` ON DELETE CASCADE
* `user_id` : BIGINT UNSIGNED NOT NULL, FOREIGN KEY REFERENCES `users(id)` ON DELETE CASCADE
* `type` : ENUM('allow', 'deny') NOT NULL DEFAULT 'allow'
* `notes` : VARCHAR(255) NULLABLE (Alasan pengecualian akses)
* `created_at`, `updated_at` : TIMESTAMP
* *Indeks Unik:* `unique(['application_id', 'user_id'])`
* *Indeks:* `index(['user_id', 'type'])`

---

## 4. Personalisasi & Riwayat Peluncuran (Launch Tracking)

### `user_favorites`
* `id` : BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
* `user_id` : BIGINT UNSIGNED NOT NULL, FOREIGN KEY REFERENCES `users(id)` ON DELETE CASCADE
* `application_id` : BIGINT UNSIGNED NOT NULL, FOREIGN KEY REFERENCES `applications(id)` ON DELETE CASCADE
* `created_at` : TIMESTAMP
* *Indeks Unik:* `unique(['user_id', 'application_id'])`

### `app_launch_logs`
* `id` : BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
* `user_id` : BIGINT UNSIGNED NOT NULL, FOREIGN KEY REFERENCES `users(id)` ON DELETE CASCADE
* `application_id` : BIGINT UNSIGNED NOT NULL, FOREIGN KEY REFERENCES `applications(id)` ON DELETE CASCADE
* `launched_at` : TIMESTAMP NOT NULL
* *Indeks:* `index(['user_id', 'launched_at'])`, `index(['application_id', 'launched_at'])`

---

## 5. Pengumuman & Pengaturan (Content & Settings)

### `announcements`
* `id` : BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
* `title` : VARCHAR(255) NOT NULL
* `body` : TEXT NOT NULL
* `is_published` : BOOLEAN DEFAULT FALSE (indeks)
* `starts_at` : TIMESTAMP NULLABLE (indeks)
* `ends_at` : TIMESTAMP NULLABLE (indeks)
* `created_by` : BIGINT UNSIGNED NULLABLE, FOREIGN KEY REFERENCES `users(id)` ON DELETE SET NULL
* `created_at`, `updated_at` : TIMESTAMP
* *Indeks:* `index(['is_published', 'starts_at', 'ends_at'])`

### `settings`
* `id` : BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
* `key` : VARCHAR(100) UNIQUE NOT NULL
* `value` : TEXT NULLABLE
* `type` : VARCHAR(50) DEFAULT 'string'
* `created_at`, `updated_at` : TIMESTAMP

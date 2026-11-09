# Panduan Deploy & Checklist Produksi — Portal NKRI
PT Nanggala Kencana Rekatama Indonesia

Dokumen ini berisi panduan deployment, checklist keamanan, dan instruksi operasional untuk mempublikasikan Portal NKRI ke lingkungan server produksi (*production*).

---

## 1. Persyaratan Server (Server Requirements)
- **Sistem Operasi:** Linux (Ubuntu 22.04 LTS / 24.04 LTS direkomendasikan)
- **Web Server:** Nginx atau Caddy dengan sertifikat SSL/TLS aktif (HTTPS Wajib)
- **PHP:** PHP 8.4+ (Ekstensi: `intl`, `gd`, `exif`, `pdo_mysql`, `redis`, `bcmath`, `curl`, `mbstring`, `xml`, `zip`)
- **Database:** MySQL 8.0+ atau MariaDB 10.11+ (Collation: `utf8mb4_unicode_ci`)
- **Cache & Session:** Redis 7.0+ (Opsional fallback ke database di server tunggal)
- **Node.js:** Node.js 20+ LTS & NPM untuk kompilasi aset frontend

---

## 2. Checklist Konfigurasi Environment (`.env`)

Pastikan variabel berikut disetel dengan benar sebelum menjalankan aplikasi di server:

```ini
APP_NAME="Portal NKRI"
APP_ENV=production
APP_KEY=base64:... (generate via php artisan key:generate)
APP_DEBUG=false
APP_URL=https://portal.nkri.co.id

# Konfigurasi Database MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portal_nkri
DB_USERNAME=nkri_dbuser
DB_PASSWORD=PasswordDatabaseSangatKuat123!
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci

# Sesi & Cache (Gunakan Redis untuk performa maksimal & isolasi)
SESSION_DRIVER=database # atau redis
SESSION_LIFETIME=120
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true

CACHE_STORE=database # atau redis
QUEUE_CONNECTION=database # atau redis

# Akun Super Admin Awal (Digunakan Seeder)
SUPER_ADMIN_NAME="Super Administrator"
SUPER_ADMIN_EMAIL="admin@nkri.co.id"
SUPER_ADMIN_PASSWORD="GantiPasswordKuatSaatDeploy123!"
```

---

## 3. Langkah-Langkah Deployment (Step-by-Step)

### A. Clone Repositori & Pasang Dependensi
```bash
git clone https://github.com/Tiannn-r/Portal-NKRI.git /var/www/portal-nkri
cd /var/www/portal-nkri

# Pasang dependensi PHP tanpa paket pengembangan
composer install --no-dev --optimize-autoloader

# Pasang dependensi JavaScript & kompilasi aset frontend Vite
npm ci
npm run build
```

### B. Migrasi Database & Seeding Awal
```bash
php artisan migrate --force
php artisan db:seed --class=DatabaseSeeder --force
```

### C. Jalankan Perintah Optimasi Produksi (Production Cache)
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan filament:optimize
```

### D. Izin Akses Folder (Permissions)
```bash
chown -R www-data:www-data /var/www/portal-nkri/storage /var/www/portal-nkri/bootstrap/cache
chmod -R 775 /var/www/portal-nkri/storage /var/www/portal-nkri/bootstrap/cache
```

---

## 4. Konfigurasi Nginx (Virtual Host)

Contoh blok konfigurasi Nginx dengan proteksi header keamanan dan penanganan SPA Inertia:

```nginx
server {
    listen 443 ssl http2;
    server_name portal.nkri.co.id;
    root /var/www/portal-nkri/public;

    ssl_certificate /etc/letsencrypt/live/portal.nkri.co.id/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/portal.nkri.co.id/privkey.pem;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";
    add_header Referrer-Policy "strict-origin-when-cross-origin";
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## 5. Checklist Verifikasi Akhir (Go-Live Audit)
- [x] `APP_DEBUG=false` di `.env` produksi.
- [x] Opsi A aktif: Tamu yang membuka `/` langsung dialihkan ke `/login`.
- [x] CMS Admin (`/admin`) menggunakan auth guard terpisah (`admin`) dan hanya dapat diakses oleh role `Super Admin`.
- [x] Matriks akses diuji: `AccessResolver` mengevaluasi role, departemen, dan aturan mutlak *Deny Wins*.
- [x] URL peluncur sistem divalidasi skema `http/https` dan tautan keluar menggunakan `rel="noopener noreferrer"`.
- [x] Pengujian otomatis seluruh suite lulus (`php artisan test` = 32 passed, 0 failed).
- [x] Query bebas masalah N+1 (teruji O(1) konstan).

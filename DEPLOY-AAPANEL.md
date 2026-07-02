# Panduan Deploy Liqo App di aaPanel

Panduan ini untuk men-deploy aplikasi ini (Laravel 13, PHP 8.3, Vite + Tailwind, PWA) ke server yang menggunakan **aaPanel** dengan **Nginx + MySQL**.

---

## 1. Persiapan Server (aaPanel)

### 1.1 Install stack via App Store

Masuk aaPanel → **App Store**, install:

| Software | Versi | Keterangan |
|----------|-------|------------|
| Nginx | 1.24+ | Web server |
| MySQL | 8.0 (atau MariaDB 10.11+) | Database |
| PHP | **8.3** | Wajib minimal 8.3 (requirement composer.json) |

### 1.2 Konfigurasi PHP 8.3

Buka **App Store → PHP 8.3 → Setting**:

1. **Install Extensions** — pastikan terpasang:
   - `fileinfo`, `opcache`, `redis` (opsional), `intl`, `zip`
   - Ekstensi bawaan yang harus aktif: `pdo_mysql`, `mbstring`, `curl`, `openssl`, `tokenizer`, `xml`, `ctype`, `bcmath`
2. **Disabled functions** — aaPanel secara default mem-blokir beberapa fungsi. **Hapus** dari daftar disabled:
   - `putenv`, `proc_open`, `pcntl_signal`, `pcntl_alarm` (dibutuhkan Composer & artisan queue)
   - `exec` (opsional, hanya jika ada fitur yang membutuhkannya)
3. **Composer** — cek dari terminal SSH:
   ```bash
   composer -V
   ```
   Jika belum ada:
   ```bash
   curl -sS https://getcomposer.org/installer | php
   mv composer.phar /usr/local/bin/composer
   ```

### 1.3 Install Node.js (untuk build Vite)

Via **App Store → Node.js Version Manager**, install **Node 20 LTS** (atau lebih baru). Atau via SSH:

```bash
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt install -y nodejs
```

> Alternatif: build asset di lokal (`npm run build`), lalu upload folder `public/build/` ke server. Dengan cara ini Node.js tidak perlu di server.

---

## 2. Buat Website & Database

### 2.1 Buat website

aaPanel → **Website → Add site**:

- **Domain**: `liqo.contoh.com` (sesuaikan)
- **Root directory**: `/www/wwwroot/liqo-app`
- **PHP version**: 8.3
- **Database**: pilih MySQL, catat nama DB / user / password (misal `liqo_app` / `liqo_user`)

### 2.2 Upload kode

**Opsi A — Git (disarankan):**

```bash
cd /www/wwwroot
rm -rf liqo-app          # hapus folder kosong bawaan aaPanel jika ada
git clone <URL_REPO_ANDA> liqo-app
cd liqo-app
```

**Opsi B — Upload manual:**

Zip proyek **tanpa** folder `vendor/`, `node_modules/`, dan file `.env`, lalu upload via aaPanel **Files** dan extract ke `/www/wwwroot/liqo-app`.

---

## 3. Setup Aplikasi Laravel

Jalankan via SSH di folder proyek:

```bash
cd /www/wwwroot/liqo-app

# 1. Install dependency PHP (production)
composer install --no-dev --optimize-autoloader

# 2. Buat file environment
cp .env.example .env
php artisan key:generate
```

### 3.1 Edit `.env` untuk production

```ini
APP_NAME="Liqo App"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://liqo.contoh.com

APP_LOCALE=id
APP_FALLBACK_LOCALE=id

LOG_CHANNEL=daily
LOG_LEVEL=error

# Ganti dari sqlite ke MySQL (sesuai database yang dibuat di aaPanel)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=liqo_app
DB_USERNAME=liqo_user
DB_PASSWORD=password_dari_aapanel

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database

# Sesuaikan konfigurasi email jika dipakai
MAIL_MAILER=smtp
```

> **Penting:** `APP_DEBUG=false` wajib di production. `APP_URL` harus **https** agar PWA (service worker) berfungsi.

### 3.2 Migrasi & optimasi

```bash
php artisan migrate --force
php artisan storage:link

# Cache konfigurasi untuk performa
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3.3 Build asset frontend

```bash
npm install
npm run build
```

Pastikan folder `public/build/` terbentuk berisi manifest dan asset hasil compile Vite.

### 3.4 Permission file

```bash
chown -R www:www /www/wwwroot/liqo-app
chmod -R 775 storage bootstrap/cache
```

> Di aaPanel, user web server adalah `www`.

---

## 4. Konfigurasi Nginx

### 4.1 Arahkan document root ke `public/`

aaPanel → **Website → (site) → Site directory**:

- **Site Directory**: `/www/wwwroot/liqo-app`
- **Running directory**: pilih **`/public`** ← ini yang paling sering terlewat
- Matikan (uncheck) **anti-XSS / cross-site protection** jika menyebabkan masalah dengan symlink `storage`.

### 4.2 URL Rewrite (pretty URLs)

aaPanel → **Website → (site) → URL Rewrite**, pilih template **laravel5** atau isi manual:

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

### 4.3 Blok akses file sensitif (opsional, tambahan di Config)

```nginx
location ~ /\.(?!well-known) {
    deny all;
}
```

Simpan lalu **Reload** Nginx.

---

## 5. SSL / HTTPS

aaPanel → **Website → (site) → SSL**:

1. Pilih **Let's Encrypt**, centang domain, klik **Apply**.
2. Aktifkan **Force HTTPS**.

HTTPS **wajib** karena aplikasi ini PWA — service worker hanya berjalan di HTTPS.

---

## 6. Queue Worker & Scheduler

Aplikasi ini menggunakan `QUEUE_CONNECTION=database`, jadi perlu worker yang berjalan terus.

### 6.1 Queue worker (Supervisor)

aaPanel → **App Store → Supervisor** → install → **Add Daemon**:

- **Name**: `liqo-queue`
- **Run user**: `www`
- **Run dir**: `/www/wwwroot/liqo-app`
- **Start command**:
  ```
  php artisan queue:work --sleep=3 --tries=3 --max-time=3600
  ```
- **Processes**: 1 (naikkan jika antrian padat)

### 6.2 Scheduler (Cron)

aaPanel → **Cron** → Add task:

- **Type**: Shell Script
- **Period**: N minutes → **1** menit
- **Script**:
  ```bash
  cd /www/wwwroot/liqo-app && php artisan schedule:run >> /dev/null 2>&1
  ```

---

## 7. Update / Re-deploy

Setiap kali ada perubahan kode:

```bash
cd /www/wwwroot/liqo-app

php artisan down                # maintenance mode
git pull origin main
composer install --no-dev --optimize-autoloader
npm install && npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart       # restart worker agar pakai kode baru
php artisan up
```

> `php artisan queue:restart` penting — tanpa ini worker Supervisor masih menjalankan kode lama.

---

## 8. Troubleshooting

| Masalah | Penyebab & solusi |
|---------|-------------------|
| **500 error / halaman putih** | Cek `storage/logs/laravel.log`. Biasanya permission — ulangi langkah 3.4. |
| **404 di semua route kecuali `/`** | URL Rewrite belum dipasang (langkah 4.2). |
| **Halaman menampilkan listing file / source code** | Running directory belum diarahkan ke `/public` (langkah 4.1). |
| **CSS/JS tidak muncul** | `npm run build` belum dijalankan, atau `APP_URL` salah (harus sama dengan domain + https). |
| **Composer error `proc_open disabled`** | Hapus `proc_open` & `putenv` dari disabled functions PHP (langkah 1.2). |
| **PWA tidak bisa di-install** | Situs belum HTTPS, atau Force HTTPS belum aktif (langkah 5). Cek juga manifest & service worker dapat diakses (bukan 404). |
| **Upload/file storage 404** | `php artisan storage:link` belum dijalankan, atau fitur anti-XSS aaPanel memblokir symlink — matikan di Site directory. |
| **Job antrian tidak jalan** | Supervisor daemon `liqo-queue` belum running — cek di App Store → Supervisor. |
| **Session logout terus** | Tabel `sessions` belum ada — jalankan `php artisan migrate --force`. |

---

## Checklist Ringkas

- [ ] PHP 8.3 + ekstensi lengkap, disabled functions dibersihkan
- [ ] Website dibuat, running directory → `/public`
- [ ] Database MySQL dibuat & dikonfigurasi di `.env`
- [ ] `composer install --no-dev` + `php artisan key:generate`
- [ ] `.env`: `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://...`
- [ ] `php artisan migrate --force` + `storage:link`
- [ ] `npm run build` (folder `public/build/` ada)
- [ ] Permission `storage/` & `bootstrap/cache` → user `www`
- [ ] URL Rewrite Laravel terpasang
- [ ] SSL Let's Encrypt + Force HTTPS
- [ ] Supervisor queue worker + Cron scheduler aktif

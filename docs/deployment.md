# Deployment SIMALUN

Panduan deploy SIMALUN ke server produksi (VPS atau shared hosting yang
support PHP-FPM + supervisor). Untuk deploy ke shared hosting murni
(Hostinger basic, dll) lihat catatan di section [Shared Hosting](#shared-hosting-fallback).

## Stack Yang Disarankan

| Komponen | Versi minimum | Catatan |
|---|---|---|
| PHP | 8.4 | Aplikasi pakai property hooks & match |
| Composer | 2.x | |
| MySQL atau MariaDB | 8.0 / 10.6 | SQLite hanya untuk testing |
| Web server | Nginx 1.20+ atau Apache 2.4+ | Nginx direkomendasikan |
| Supervisor | 4.x | Untuk queue worker (opsional kalau pakai cron) |
| OPcache | bawaan PHP | Wajib di production |

## 1. Persiapan Aplikasi

### 1.1 Clone & install

```bash
cd /var/www
git clone https://github.com/Ardinta19/simalun.git azka-laundry
cd azka-laundry

# Install dependency tanpa dev
composer install --no-dev --optimize-autoloader --prefer-dist
```

### 1.2 File `.env`

Copy `.env.example` jadi `.env`, isi dengan kredensial production. Lihat
juga `.env.production.example` (kalau sudah ada) untuk daftar variable
yang relevan untuk produksi. Field penting:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://laundry.example.com
APP_TIMEZONE=Asia/Jakarta

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=azka_laundry
DB_USERNAME=azka
DB_PASSWORD=...

QUEUE_CONNECTION=database
SESSION_DRIVER=database
CACHE_STORE=database

# WAJIB di produksi (nonaktifkan kalau development pakai HTTP)
SESSION_SECURE_COOKIE=true
SESSION_ENCRYPT=true

MAIL_MAILER=smtp
MAIL_HOST=...
MAIL_FROM_ADDRESS=no-reply@laundry.example.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 1.3 Generate key & migrate

```bash
php artisan key:generate --force
php artisan migrate --force
php artisan db:seed --force --class=UserSeeder       # akun admin awal
php artisan db:seed --force --class=ServiceCategorySeeder
```

### 1.4 Storage symlink

Wajib supaya upload (avatar, foto bukti pickup, screenshot laporan
kendala) bisa diakses publik via URL `/storage/...`.

```bash
php artisan storage:link --force
```

Cek output `public/storage` jadi symlink ke `storage/app/public`.

### 1.5 Cache config & route

Wajib di production untuk ngangkat performa request lifecycle.

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

> **Catatan:** kalau ada update `.env` atau penambahan route baru,
> jalanin `php artisan optimize:clear` lalu re-cache. Lupa langkah ini =
> perubahan gak kebaca walau file sudah di-pull.

### 1.6 Permission

User php-fpm (biasanya `www-data` di Ubuntu/Debian, `nginx` di
RHEL-family) harus punya write access ke `storage/` dan
`bootstrap/cache/`:

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

## 2. Web Server (Nginx)

```nginx
server {
    listen 443 ssl http2;
    server_name laundry.example.com;
    root /var/www/azka-laundry/public;

    ssl_certificate     /etc/letsencrypt/live/laundry.example.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/laundry.example.com/privkey.pem;

    index index.php index.html;

    add_header X-Frame-Options "DENY" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known) { deny all; }

    client_max_body_size 6M;   # cukup untuk upload bukti foto (max 5MB)
}

server {
    listen 80;
    server_name laundry.example.com;
    return 301 https://$host$request_uri;
}
```

Sertifikat dari Let's Encrypt (`certbot --nginx -d laundry.example.com`)
atau provider lain. Reload Nginx setelah edit:

```bash
sudo nginx -t && sudo systemctl reload nginx
```

## 3. OPcache

Edit `/etc/php/8.4/fpm/conf.d/10-opcache.ini` (path bisa beda di distro
lain). Setting yang direkomendasikan untuk produksi:

```ini
opcache.enable=1
opcache.enable_cli=0

; Cache 256MB harusnya lebih dari cukup untuk aplikasi sekelas SIMALUN
opcache.memory_consumption=256
opcache.interned_strings_buffer=16

; Jumlah file PHP yang di-cache. Aplikasi ini ~3000 file (vendor + app).
opcache.max_accelerated_files=20000

; Validasi timestamp DI MATIKAN di production untuk speed-up.
; Kalau lupa di-flush setelah deploy, perubahan gak kebaca.
opcache.validate_timestamps=0

; Restart OPcache otomatis kalau cache hampir penuh.
opcache.max_wasted_percentage=10

; Preload (opsional, butuh PHP 7.4+ — preload bootstrap dasar laravel)
; opcache.preload=/var/www/azka-laundry/bootstrap/cache/opcache-preload.php
; opcache.preload_user=www-data
```

Restart PHP-FPM setelah edit:

```bash
sudo systemctl restart php8.4-fpm
```

> **Penting:** karena `validate_timestamps=0`, setiap deploy WAJIB
> jalanin `php artisan opcache:clear` (kalau pakai package) atau restart
> php-fpm. Lihat section [Deploy Routine](#deploy-routine).

## 4. Queue Worker

Notifikasi customer (`OrderStatusUpdated`) di-queue ke driver
`database`, di queue dengan nama `notifications`. Tanpa worker,
notifikasi nyangkut di tabel `jobs` dan customer gak dapet pemberitahuan
status order.

### 4.1 Supervisor (direkomendasikan)

Buat `/etc/supervisor/conf.d/simalun-queue.conf`:

```ini
[program:simalun-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/azka-laundry/artisan queue:work database --queue=notifications,default --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/log/simalun/queue.log
stopwaitsecs=3600
```

Aktifkan:

```bash
sudo mkdir -p /var/log/simalun && sudo chown www-data:www-data /var/log/simalun
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start simalun-queue:*
sudo supervisorctl status
```

> `--max-time=3600` me-restart worker tiap 1 jam — defensive supaya
> memory leak bertahap (eloquent boot model, dll) gak jadi masalah.
> `--tries=3` artinya job yang gagal akan di-retry 2 kali sebelum masuk
> `failed_jobs`. Lihat queue dengan `php artisan queue:failed`.

### 4.2 Systemd (alternatif kalau supervisor gak tersedia)

Buat `/etc/systemd/system/simalun-queue.service`:

```ini
[Unit]
Description=SIMALUN Queue Worker
After=network.target mysql.service

[Service]
Type=simple
User=www-data
Group=www-data
Restart=always
RestartSec=5
WorkingDirectory=/var/www/azka-laundry
ExecStart=/usr/bin/php artisan queue:work database --queue=notifications,default --sleep=3 --tries=3 --max-time=3600
StandardOutput=append:/var/log/simalun/queue.log
StandardError=append:/var/log/simalun/queue.log

[Install]
WantedBy=multi-user.target
```

Aktifkan:

```bash
sudo systemctl daemon-reload
sudo systemctl enable --now simalun-queue
sudo systemctl status simalun-queue
```

## 5. Cron / Scheduler

SIMALUN belum pakai task schedule banyak, tapi ke depan kemungkinan ada
(rekap harian, cleanup soft-delete). Tambah ke crontab `www-data` supaya
siap:

```cron
* * * * * cd /var/www/azka-laundry && php artisan schedule:run >> /dev/null 2>&1
```

## 6. Cache & Performance

### 6.1 Cache driver

Default `database` driver (lihat `.env`). Cocok untuk shared hosting
karena gak butuh service tambahan. Untuk traffic >100 req/menit pindah
ke `redis` (lebih cepat, lebih hemat DB connection):

```env
CACHE_STORE=redis
SESSION_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

Dashboard admin punya 6 aggregate query yang di-cache 60 detik
(top services, pickup buckets, top customers, rating stats, latest
reviews, voucher count). Wajar — analitik 30-hari gak butuh data
real-time.

### 6.2 Manual cache flush

Setelah perubahan signifikan (mis. deploy fitur baru) atau saat support
butuh data terbaru di dashboard:

```bash
php artisan cache:clear
```

Atau lebih granular (hanya 1 segment):

```bash
php artisan tinker
>>> Cache::forget('dashboard.admin.top-services')
```

## 7. Monitoring & Log

### 7.1 Application log

Default lokasi: `storage/logs/laravel.log`. Rotasi via `config/logging.php`
sudah di-set ke `daily` (14 hari retention).

### 7.2 Queue health

```bash
# Cek job yang gagal
php artisan queue:failed

# Re-queue semua job yang gagal
php artisan queue:retry all

# Hapus 1 job tertentu yang gak relevan
php artisan queue:forget {id}
```

### 7.3 Audit log

Audit log untuk aksi admin (voucher CRUD, order assign, finance entry,
service CRUD) ada di tabel `audit_logs`. Akses lewat
`/admin/audit` (filter actor / action / tanggal).

## 8. Deploy Routine

Sekuens setelah `git pull` di server:

```bash
cd /var/www/azka-laundry

git fetch origin main
git reset --hard origin/main

composer install --no-dev --optimize-autoloader --prefer-dist
php artisan migrate --force

php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Restart queue worker supaya code baru ke-load
sudo supervisorctl restart simalun-queue:*
# (atau: sudo systemctl restart simalun-queue)

# Restart PHP-FPM untuk flush opcache
sudo systemctl reload php8.4-fpm
```

Bungkus jadi script `deploy.sh` di root project supaya konsisten:

```bash
#!/usr/bin/env bash
set -e
cd "$(dirname "$0")"
git fetch origin main
git reset --hard origin/main
composer install --no-dev --optimize-autoloader --prefer-dist
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
sudo supervisorctl restart simalun-queue:* || true
sudo systemctl reload php8.4-fpm || true
echo "Deploy selesai $(date)"
```

## Shared Hosting Fallback

Kalau Azka belum sanggup VPS dan deploy ke Hostinger atau sejenis:

- **Storage symlink**: kebanyakan provider gak izinkan symlink. Solusi:
  pindah konten `public/` ke document root (`public_html/`), atau pakai
  ekstensi alternative `php artisan storage:link` yang nyalin file
  bukan symlink (`composer require symlink-as-copy`).
- **Queue worker**: gak bisa supervisor. Pakai cron `php artisan queue:work --stop-when-empty`
  setiap menit. Latency notifikasi naik jadi ~1 menit (acceptable
  untuk Azka yang belum traffic tinggi).
- **OPcache config**: biasanya gak bisa di-edit. Yang penting OPcache
  enabled (cek via `php -i | grep opcache.enable`). Beberapa provider
  punya panel untuk ini.

## Troubleshooting

### "Mixed Content: page loaded over HTTPS but..."

Pasang trusted proxies di `bootstrap/app.php` kalau di belakang Cloudflare /
load balancer:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->trustProxies(at: '*');
})
```

### "419 Page Expired" setelah deploy

Session signing key (di `APP_KEY`) berubah, atau `SESSION_DRIVER=cookie`
tanpa `SESSION_ENCRYPT=true`. Logout-login ulang user. Kalau masif, set
`SESSION_DRIVER=database` lalu truncate tabel `sessions`.

### "MySQL has gone away" di queue worker

Worker hidup terlalu lama, koneksi DB di-drop server. Solusi:

- Sudah ditangani via `--max-time=3600` (worker auto-restart per jam)
- Atau set `wait_timeout` MySQL lebih panjang dari worker max-time

### Notifikasi tidak terkirim

1. Cek `php artisan queue:failed` — ada error?
2. Cek `MAIL_*` env benar (test via `php artisan tinker` →
   `Mail::raw('test', fn($m) => $m->to('test@example.com')->subject('test'))`)
3. Cek queue worker hidup: `sudo supervisorctl status` atau
   `systemctl status simalun-queue`

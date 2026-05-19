# Deployment & Maintenance Guide — SIMALUN

Panduan praktis menyiapkan SIMALUN (Azka Laundry) di server production atau staging.

## 1. Prasyarat

- PHP 8.2+ dengan ekstensi: `pdo_mysql`, `mbstring`, `bcmath`, `gd`, `fileinfo`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`.
- MySQL 8.0+ (atau MariaDB 10.6+).
- Composer 2.x dan Node.js 18+ (untuk build asset Vite).
- Web server: Nginx atau Apache, dengan document root mengarah ke `public/`.

## 2. Setup Awal

```bash
git clone <repo-url> simalun
cd simalun

cp .env.example .env
# Edit .env — sesuaikan APP_URL, DB_*, MAIL_*, dll.

composer install --no-dev --optimize-autoloader
php artisan key:generate

# Buat database MySQL terlebih dulu:
#   CREATE DATABASE simalun CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
php artisan migrate --force
php artisan db:seed --force        # opsional, untuk seed user/service awal

# Symlink storage → public
php artisan storage:link

# Build asset frontend
npm ci
npm run build

# Cache config + route + view (production)
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 3. Database — Switch SQLite → MySQL

`.env.example` sudah default ke MySQL. Jika project sebelumnya pakai SQLite, lakukan:

1. Backup data lama (kalau perlu): `php artisan db:backup` atau `sqlite3 database/database.sqlite .dump > backup.sql`.
2. Buat database MySQL: `CREATE DATABASE simalun CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;`.
3. Update `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=simalun
   DB_USERNAME=simalun_user
   DB_PASSWORD=********
   ```
4. Bersihkan cache config: `php artisan config:clear`.
5. Migrate ulang: `php artisan migrate:fresh --force` (hapus tabel lama) atau `php artisan migrate --force` (hanya migrasi pending).

## 4. Storage Link & Permission

SIMALUN menyimpan file user-uploaded (avatar profil, foto bukti pengiriman driver) di `storage/app/public/`.
File-file ini **harus** dapat diakses lewat web — jadi `storage:link` wajib dijalankan.

```bash
php artisan storage:link
```

Verifikasi: cek `public/storage` jadi symlink yang menunjuk ke `../storage/app/public`.

```bash
ls -la public/storage
# lrwxrwxrwx ... public/storage -> ../storage/app/public
```

### Permission folder yang harus writable oleh web server

Web server (user `www-data` / `nginx` / `apache`) harus bisa menulis ke:

| Folder              | Alasan                                                  |
|---------------------|---------------------------------------------------------|
| `storage/`          | Logs, cache view, sessions, file uploads.               |
| `bootstrap/cache/`  | Cache framework.                                        |

Set permission (Linux):

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo find storage -type d -exec chmod 775 {} \;
sudo find storage -type f -exec chmod 664 {} \;
sudo find bootstrap/cache -type d -exec chmod 775 {} \;
sudo find bootstrap/cache -type f -exec chmod 664 {} \;
```

Atau pakai script helper yang sudah disediakan:

```bash
bash scripts/fix-permissions.sh
```

### Re-link kalau symlink hilang

Setelah deploy ulang atau restore backup, jalankan:

```bash
php artisan storage:link --force
```

`--force` akan menggantikan symlink lama jika sudah ada dan rusak.

## 5. Verifikasi Pasca-Deploy

Checklist cepat:

- [ ] `php artisan about` — muncul tanpa error.
- [ ] Buka `/up` — return 200 (Laravel health endpoint).
- [ ] Login dengan akun seed admin → akses `/admin/dashboard`.
- [ ] Customer buat order → upload avatar profil → cek file muncul di `public/storage/avatars/`.
- [ ] Driver konfirmasi selesai dengan foto bukti → cek file di `public/storage/proof/`.
- [ ] Trigger 404 (URL random) → halaman branded muncul (bukan default Symfony).

## 6. Troubleshooting

### `403 Forbidden` saat akses `/storage/...`
- Pastikan `php artisan storage:link` sudah jalan.
- Pastikan web server boleh follow symlink (`Options +FollowSymLinks` di Apache).

### `The stream or file could not be opened` (log/cache write error)
- Jalankan ulang permission fix di section 4.

### `SQLSTATE[HY000] [2002] Connection refused`
- Cek MySQL aktif: `sudo systemctl status mysql`.
- Cek kredensial di `.env`, lalu `php artisan config:clear`.

### CSRF token mismatch (419)
- Halaman branded SIMALUN akan muncul — minta user refresh dan submit ulang.
- Jika sering, periksa `SESSION_DOMAIN` & `APP_URL` di `.env`.

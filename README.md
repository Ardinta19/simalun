# SIMALUN — Sistem Manajemen Azka Laundry

[![CI](https://github.com/Ardinta19/simalun/actions/workflows/ci.yml/badge.svg)](https://github.com/Ardinta19/simalun/actions/workflows/ci.yml)

> Aplikasi web manajemen laundry berbasis Laravel untuk mendigitalisasi proses
> jemput–antar pesanan, pencatatan transaksi, dan pemantauan operasional harian
> di Azka Laundry (Mayang Mangurai, Kota Jambi).

Versi aktif: **v2.4.0**

---

## Tentang Proyek

SIMALUN dibangun oleh tim MPSI Kelompok 2 sebagai studi kasus penerapan SDLC
pada usaha laundry rumahan. Sebelumnya pencatatan order, ongkos jemput, dan
laporan keuangan ditulis manual di buku — sering bentrok ketika dua pesanan
masuk bersamaan, struk hilang, dan rekap akhir bulan menghabiskan waktu.

Aplikasi ini menjawab masalah itu dengan tiga peran utama yang masing-masing
punya alur dan dashboard sendiri:

- **Customer** — daftar, atur alamat, pesan layanan, lacak pesanan, lihat riwayat & notifikasi.
- **Admin** — kelola pesanan masuk (online & walk-in), tugaskan kurir, ubah status, catat keuangan, ekspor laporan.
- **Driver** — lihat tugas hari ini, ubah status saat jemput / antar, lihat riwayat pengantaran.

Status pesanan mengalir mengikuti realita workshop:
`menunggu → dijemput → dicuci → disetrika → siap → dikirim → selesai`

---

## Fitur Utama

| Area | Cakupan |
|---|---|
| Auth | Login email/no HP, registrasi, verifikasi email, reset password, soft delete user, rate limit |
| Order | Pemesanan online (per-kg + per-item), order walk-in, pembatalan, struk PDF, kalkulasi otomatis termasuk diskon & ongkos jemput |
| Alamat | Multi alamat per customer, alamat utama, zona ongkir (A/B/C — Rp 5k/10k/15k) |
| Driver | Auto-assign, riwayat tugas, history status per pesanan |
| Notifikasi | Notifikasi database real-time per perubahan status |
| Keuangan | Pencatatan pemasukan otomatis dari pesanan selesai, pengeluaran manual, ekspor PDF & Excel |
| Laporan | Form keluhan / saran customer + driver dengan lampiran screenshot |
| Keamanan | `SanitizeInput` middleware, `RoleMiddleware`, throttling auth, `lockForUpdate` saat generate kode pesanan, unique constraint anti-duplikat finance |

---

## Tech Stack

- **Backend** — PHP 8.4+, Laravel 12, MySQL 8 (atau SQLite untuk pengembangan cepat)
- **Frontend** — Blade, TailwindCSS 3, AlpineJS, GSAP (transisi halaman & micro-interaction)
- **Build** — Vite 7
- **Dokumen** — DomPDF (struk & laporan keuangan), OpenSpout (ekspor Excel)
- **Tooling** — Laravel Pint (code style), PHPUnit 11, Laravel Pail (log streaming)

---

## Setup Pengembangan

### Prasyarat

- PHP 8.4 atau lebih baru beserta ekstensi standar (`mbstring`, `xml`, `pdo_mysql` / `pdo_sqlite`, `gd` untuk avatar). Beberapa dependency (mis. `openspout`) sudah membutuhkan PHP 8.4 di versi terkini.
- Composer 2.x
- Node.js 20.x + npm
- MySQL 8 atau SQLite 3.40+

### Langkah Singkat

```bash
git clone https://github.com/Ardinta19/simalun.git
cd simalun

# Otomatis: install composer + copy .env + key:generate + migrate + npm install + build
composer setup

# Isi data awal (3 akun demo + daftar layanan)
php artisan db:seed
```

Untuk dev sehari-hari pakai shortcut yang sudah disediakan — menjalankan
`php artisan serve`, queue listener, log viewer, dan Vite secara paralel:

```bash
composer dev
```

Buka `http://localhost:8000`.

### Pakai SQLite (tanpa MySQL)

Edit `.env`:

```env
DB_CONNECTION=sqlite
# kosongkan/komentari DB_HOST, DB_DATABASE, dst.
```

Lalu:

```bash
touch database/database.sqlite
php artisan migrate --seed
```

### Akun Demo

Setelah `db:seed`:

| Role | Email | Password |
|---|---|---|
| Customer | customer@test.com | password123 |
| Admin | admin@test.com | password123 |
| Driver | driver@test.com | password123 |

> Ganti password demo sebelum deploy.

---

## Struktur Folder Penting

```
app/
├── Http/Controllers/         # OrderController, FinanceController, dst.
├── Http/Middleware/          # RoleMiddleware, SanitizeInput
├── Models/                   # Order, OrderItem, Service, CustomerAddress, ...
├── Notifications/            # OrderStatusUpdated
├── Observers/                # OrderObserver (status history + notif)
├── Support/                  # Laundry helper (config singkat untuk view)
└── View/Components/          # AppLayout, GuestLayout, BackButton

config/laundry.php            # Single source of truth info usaha (nama, alamat, WA, jam)
database/migrations/          # 22 migration
database/seeders/             # UserSeeder + ServiceSeeder

resources/views/
├── auth/                     # login, register, verify-email, reset-password
├── layouts/                  # app, guest
├── roles/                    # admin/, customer/, driver/ — view per peran
├── order/                    # form pemesanan, tracking, struk
└── exports/                  # template PDF & Excel

routes/web.php                # Semua route dikelompokkan per role middleware
tests/Feature/                # Test alur utama (auth, order, walk-in, assign driver)
docs/                         # Dokumen analisis (Project Charter, SRS, hasil wawancara)
```

---

## Konfigurasi Usaha

Detail Azka Laundry (nama, nomor WA, jam buka, alamat, link Google Maps)
diatur lewat `.env` dan dibaca konsisten via `config/laundry.php`. Lihat
section **Laundry Business Info** di `.env.example`.

Helper `App\Support\Laundry` mempermudah akses dari controller / Blade:

```php
\App\Support\Laundry::name();        // "Azka Laundry"
\App\Support\Laundry::waLink('Halo, saya mau tanya soal pesanan');
```

Atau lewat directive Blade:

```blade
@laundryName · @laundryPhone · @laundryAddress
```

---

## Testing

```bash
php artisan test
```

Cakupan saat ini: alur autentikasi, profil, pemesanan customer, walk-in admin,
penugasan driver, dan struk PDF. Test berjalan menggunakan `RefreshDatabase`
sehingga aman dijalankan berulang.

Untuk cek code style sebelum commit:

```bash
./vendor/bin/pint --test    # cek tanpa mengubah file
./vendor/bin/pint           # auto-fix
```

---

## Catatan Operasional

- **Queue.** `QUEUE_CONNECTION=database`. Pastikan `php artisan queue:work` jalan
  agar notifikasi tidak bottleneck. Saat `composer dev`, queue listener sudah dijalankan otomatis.
- **Mail.** Default `MAIL_MAILER=log` — email reset password masuk ke `storage/logs/laravel.log`.
  Set ke SMTP / Mailtrap untuk uji email asli.
- **File upload.** Avatar user, screenshot laporan, dan bukti transfer (rencana) disimpan di `storage/app/public/`. Jalankan `php artisan storage:link` setelah clone.
- **Soft delete.** User, order, dan beberapa tabel pendukung pakai soft delete — gunakan `withTrashed()` saat audit data lama.

---

## Kontribusi

Repo ini privat untuk keperluan akademik. Jika ada akses untuk PR:

1. Branch dari `main` dengan prefix `feat/`, `fix/`, `chore/`, `docs/`.
2. Jalankan `pint --test` dan `php artisan test` sebelum push.
3. PR ditulis dalam Bahasa Indonesia, sebutkan ruang lingkup perubahan & dampak ke role yang terkena.

---

## Tim

Tim MPSI Kelompok 2 — Universitas Jambi.
Studi kasus: Azka Laundry, Jl. Mayang Mangurai, Kota Baru, Jambi.

---

## Lisensi

Kode aplikasi ini dirilis di bawah lisensi MIT.
Aset (logo, foto, identitas Azka Laundry) tetap milik pemilik usaha.

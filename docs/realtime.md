# Realtime Updates di SIMALUN

Dokumen ini menjelaskan dua mode realtime yang didukung sistem dan cara
beralih antar keduanya.

## Mode default: Polling (aktif)

Driver dashboard memanggil endpoint `GET /driver/dashboard/poll` setiap
30 detik. Endpoint ini cuma ngembaliin signature ringan — kalau berubah
dari poll sebelumnya, JS munculkan banner "Ada update tugas, tap untuk
muat ulang" di atas layar.

**Kenapa polling default?**

- Tidak butuh proses worker tambahan — jalan di shared hosting (Hostinger
  Premium / Business / Cloud Startup yang dipakai tim MPSI).
- Zero dependency tambahan (gak install paket frontend / composer).
- Bandwidth kecil (~500 byte per poll, 120 poll/jam → 60KB).

**Trade-off:**

- Update bukan instan — paling buruk 30 detik delay.
- Ada beban DB ringan dari query polling (sudah pakai `latest()` index).

## Mode opsional: Broadcasting via Reverb

Untuk update **instan** (sub-second), pindah ke Reverb. Butuh server yang
bisa run proses daemon (Reverb websocket server) — **tidak jalan di shared
hosting**.

### Prasyarat

- VPS atau Cloud (Hostinger KVM Premium ke atas, Niagahoster VPS, dll).
- Akses SSH untuk run `supervisor` / `systemd` service.
- PHP 8.2+ (sudah ✅).

### Langkah migrasi

#### 1. Install Reverb backend

```bash
composer require laravel/reverb
php artisan reverb:install
```

Wizard akan ngisi `.env` dengan `REVERB_APP_KEY`, dst. Pastikan
`BROADCAST_CONNECTION=reverb` di `.env`.

#### 2. Install Echo + Pusher di frontend

```bash
npm install --save-dev laravel-echo pusher-js
```

#### 3. Aktifkan file `echo.js`

```bash
cp resources/js/echo.js.example resources/js/echo.js
```

Lalu di `resources/js/app.js`, tambahkan satu baris di paling atas:

```js
import './echo.js';
```

#### 4. Aktifkan broadcasting di notifikasi

Edit `app/Notifications/OrderStatusUpdated.php`:

1. Tambah `use Illuminate\Contracts\Broadcasting\ShouldBroadcast;` di import.
2. Ganti class signature jadi:
   ```php
   class OrderStatusUpdated extends Notification implements ShouldQueue, ShouldBroadcast
   ```
3. Ganti method `via()` return jadi:
   ```php
   return ['database', 'broadcast'];
   ```
4. **Uncomment** block `broadcastOn()`, `broadcastAs()`, dan `broadcastWith()`
   di bawah file (sudah disiapkan).

#### 5. Aktifkan auth untuk private channel

Tambah ke `routes/channels.php` (kalau file tidak ada, buat baru):

```php
<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
```

Daftarkan `BroadcastServiceProvider` di `bootstrap/providers.php` kalau belum.

#### 6. Render meta `user-id` di layout

Di view yang butuh listen broadcast (mis. driver dashboard), tambah:

```blade
<meta name="user-id" content="{{ auth()->id() }}">
```

#### 7. Switch mode di runtime

Set di `.env`:

```env
DRIVER_REALTIME_MODE=broadcasting
```

Polling JS auto skip, broadcasting ambil alih.

#### 8. Build frontend

```bash
npm run build
```

#### 9. Run Reverb daemon

```bash
php artisan reverb:start
```

Production: pakai supervisor. Contoh `/etc/supervisor/conf.d/reverb.conf`:

```ini
[program:reverb]
command=php /path/to/simalun/artisan reverb:start --host=0.0.0.0 --port=8080
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/var/log/reverb.log
```

```bash
sudo supervisorctl reread && sudo supervisorctl update && sudo supervisorctl start reverb
```

Konfigurasi reverse-proxy (Nginx) supaya `/app/...` ke port 8080 — detail
lihat dokumentasi Reverb resmi.

### Rollback ke polling

Cukup set `.env`:

```env
DRIVER_REALTIME_MODE=polling
```

dan stop daemon Reverb. Polling akan otomatis aktif lagi tanpa rebuild.

## Tabel keputusan cepat

| Kebutuhan | Mode | Hosting |
|---|---|---|
| Demo MPSI / coursework | Polling | Shared (Hostinger Premium) |
| Volume order < 50/hari | Polling | Shared OK |
| Volume order > 200/hari | Broadcasting | VPS dibutuhkan |
| Tampilan realtime tepat di sidang | Polling cukup (30s feel) | Shared |
| Driver app mobile native | Broadcasting | VPS |

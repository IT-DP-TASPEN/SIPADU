<p align="center">
  <img src="https://bankdptaspen.co.id/wp-content/uploads/2024/01/Logo-Bank-DP-Taspen-Version-New.png" alt="Bank DP Taspen" width="260">
</p>

# SIPADU

SIPADU adalah portal terpusat Bank DP Taspen untuk mencari, membuka, dan memonitor akses ke aplikasi internal.

Portal ini dirancang sebagai:
- search-first internal portal
- SSO gateway untuk aplikasi surrounding
- launcher untuk aplikasi yang tetap membutuhkan login mandiri
- pusat monitoring dan audit akses aplikasi

## Ringkasan Fitur

- Login portal dengan `username` atau `email`
- Search-first launcher untuk aplikasi internal
- Mode aplikasi:
  - `sso`
  - `launch_only`
- Pengaturan akses aplikasi berdasarkan:
  - divisi
  - jabatan
  - tipe kantor
  - kode cabang
  - nama cabang
- Dashboard monitoring admin:
  - summary aplikasi
  - user paling aktif
  - aplikasi paling banyak diakses
  - recent activity audit
- CRUD aplikasi portal
- CRUD user portal
- Profil user
- Panduan implementasi SSO:
  - versi lengkap
  - versi ringkas
  - versi dinamis untuk admin

## Stack

- Laravel 13
- PHP 8.3
- MySQL
- Tailwind via CDN
- Asset statis di `public/portal.css` dan `public/portal.js`

Catatan:
- project ini saat ini tidak bergantung pada Vite untuk runtime UI
- jalankan aplikasi dengan `php artisan serve`

## Struktur Penting

- `app/Http/Controllers`
  - `AuthController.php`
  - `PortalController.php`
  - `SsoLaunchController.php`
  - `DashboardController.php`
  - `PortalApplicationController.php`
  - `UserManagementController.php`
  - `ProfileController.php`
  - `SsoGuideController.php`
- `app/SsoTokenService.php`
- `app/Models`
  - `User.php`
  - `PortalApplication.php`
  - `PortalApplicationAccessRule.php`
  - `SsoLaunchLog.php`
- `resources/views`
  - `auth/login.blade.php`
  - `portal/index.blade.php`
  - `dashboard/index.blade.php`
  - `portal-applications/*`
  - `users/*`
  - `docs/sso-ringkas.blade.php`
- `database/seeders`
  - `UserSeeder.php`
  - `PortalApplicationSeeder.php`
  - `data/users.json`
  - `data/portal-applications.json`

## Setup Lokal

1. Clone repository
```bash
git clone <repo-url>
cd sipadu-app
```

2. Install dependency PHP
```bash
composer install
```

3. Copy environment
```bash
copy .env.example .env
```

4. Generate app key
```bash
php artisan key:generate
```

5. Atur koneksi database MySQL di `.env`

Contoh:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sipadu
DB_USERNAME=root
DB_PASSWORD=
```

6. Jalankan migration
```bash
php artisan migrate
```

7. Seed data awal
```bash
php artisan db:seed
```

8. Jalankan aplikasi
```bash
php artisan serve
```

## Seeder Production

Seeder saat ini sudah disinkronkan dengan data yang ada di database pengembangan terbaru.

Sumber data seeder:
- [users.json](/d:/bpr/web/SIPADU/sipadu-app/database/seeders/data/users.json)
- [portal-applications.json](/d:/bpr/web/SIPADU/sipadu-app/database/seeders/data/portal-applications.json)

Yang sudah tercakup:
- user portal aktif saat ini
- hash password user saat ini
- status admin
- daftar aplikasi portal saat ini
- mode `sso` / `launch_only`
- audience SSO
- shared secret SSO
- rule akses aplikasi

Untuk deploy environment baru:
```bash
php artisan migrate --force
php artisan db:seed --force
```

## Akun Admin Awal

Akun admin seeded:
- username: `2907997_Ferrian`
- employee_id: `2907997`

Hak admin diberikan lewat field `is_admin`.

## Konsep SSO

SIPADU menggunakan dua mode launch:

### 1. `sso`

Dipakai untuk aplikasi surrounding yang menerima login dari SIPADU.

Saat user klik aplikasi:
- SIPADU membuat `sso_token`
- aplikasi tujuan memvalidasi token
- aplikasi tujuan login-kan user lokal berdasarkan `employee_id`

### 2. `launch_only`

Dipakai untuk aplikasi yang tetap login sendiri.

Saat user klik aplikasi:
- SIPADU hanya membuka URL aplikasi
- tidak ada handoff token login

## Standar Integrasi Aplikasi Surrounding

Setiap aplikasi surrounding minimal harus:
- punya kolom `employee_id` di tabel user
- punya endpoint penerima SSO, misalnya `/sso/login`
- memvalidasi token dari SIPADU
- mencocokkan user berdasarkan `employee_id`
- membuat session login lokal

Untuk panduan implementasi:
- versi lengkap: `/panduan/sso`
- versi lengkap PDF: `/panduan/sso/pdf`
- versi ringkas dinamis admin: `/panduan/sso-ringkas`
- versi ringkas PDF: `/panduan/sso-ringkas/pdf`

Catatan keamanan:
- halaman ringkas dinamis admin-only karena menampilkan shared secret aplikasi

## Shared Secret Otomatis

Untuk aplikasi mode `sso`:
- `shared secret` akan di-generate otomatis saat aplikasi dibuat jika field dikosongkan
- admin bisa regenerate secret dari form aplikasi
- panduan dinamis admin akan menampilkan secret terbaru agar programmer surrounding bisa mencocokkan konfigurasi

## Monitoring dan Audit

Dashboard admin tersedia untuk:
- total aplikasi
- total user
- launch hari ini
- audit 30 hari
- top users
- top apps
- recent launches

Data audit bersumber dari `sso_launch_logs`.

## Menjalankan Test

```bash
php artisan test
```

Status terakhir saat README ini diperbarui:
- `28` test pass

## Catatan Deploy Production

Sebelum push dan deploy:
- pastikan `.env` production sudah benar
- pastikan `APP_ENV=production`
- pastikan `APP_DEBUG=false`
- pastikan `APP_URL` sesuai domain production
- pastikan database production sudah disiapkan
- review kembali `shared secret` aplikasi SSO aktif
- pastikan session, cache, dan queue sesuai environment production

Perintah umum sesudah deploy:
```bash
php artisan migrate --force
php artisan db:seed --force
php artisan optimize
```

Kalau perlu clear cache:
```bash
php artisan optimize:clear
```

## Catatan Git

Sebelum push ke GitHub:
- review isi `.env` agar tidak ikut ter-push
- review data `shared secret` pada seeder jika repo akan dibagikan luas
- jika repo hanya untuk internal bank, simpan dengan kontrol akses yang sesuai

## Lisensi

Internal use only - Bank DP Taspen.

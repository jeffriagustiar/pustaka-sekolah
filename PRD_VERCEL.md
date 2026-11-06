# PRD: Deploy Laravel di Vercel (Stateless Web/API)

## 1. Tujuan

Panduan standar untuk men-deploy aplikasi Laravel (web + API) ke Vercel dengan arsitektur serverless, berdasarkan pengalaman deploy project `pustaka-sekolah`.

## 2. Ruang Lingkup

- Aplikasi Laravel 11/13 (PHP 8.3+; Vercel runtime tersedia hingga 8.5)
- Database cloud: PostgreSQL (Supabase/Neon)
- Auth: session cookie / Firebase / Sanctum (opsional)
- Storage file: Supabase Storage / S3 (bukan lokal)
- Cache/Session: cookie/array/Redis (bukan file)
- Tidak menggunakan queue worker / scheduler native Laravel

## 3. Arsitektur

- **Frontend/View**: Blade + Tailwind (Vite build) atau API JSON
- **Backend**: Laravel (PHP) di Vercel Serverless Function (`api/index.php`)
- **Database**: Supabase Postgres (external)
- **File Storage**: Supabase Storage / S3 (wajib, filesystem read-only)
- **Cache/Session**: cookie/array atau Redis (Upstash)

## 4. Konfigurasi Vercel

### 4.1 `vercel.json`

```json
{
    "version": 2,
    "builds": [
        { "src": "api/index.php", "use": "vercel-php@0.9.0" },
        { "src": "/public/**", "use": "@vercel/static" }
    ],
    "routes": [
        { "src": "/build/(.*)", "dest": "/public/build/$1" },
        { "src": "/(css|js|images)/(.*)", "dest": "/public/$1/$2" },
        { "src": "/(.*)", "dest": "/api/index.php" }
    ],
    "env": {
        "APP_ENV": "production",
        "APP_DEBUG": "false",
        "APP_URL": "https://<nama-project>.vercel.app",
        "ASSET_URL": "https://<nama-project>.vercel.app",
        "LOG_CHANNEL": "stderr",
        "CACHE_DRIVER": "array",
        "SESSION_DRIVER": "cookie",
        "VIEW_COMPILED_PATH": "/tmp",
        "APP_SERVICES_CACHE": "/tmp/services.php",
        "APP_PACKAGES_CACHE": "/tmp/packages.php",
        "APP_CONFIG_CACHE": "/tmp/config.php"
    }
}
```

### 4.2 Entry Point `api/index.php`

```php
<?php

require __DIR__ . '/../public/index.php';
```

## 5. Penyesuaian Laravel

### 5.1 `bootstrap/app.php`

- Daftarkan route web (dan opsional api).
- Hindari prefix `/api` untuk endpoint yang ingin diakses publik di Vercel (karena diintersep Vercel). Gunakan `/data/.../api` atau `/students-api`.

### 5.2 `config/app.php`

Pastikan memuat:
- `providers` (gunakan `ServiceProvider::defaultProviders()` + `App\Providers\AppServiceProvider::class`).
- `aliases` (gunakan `Facade::defaultAliases()`).
- `'asset_url' => env('ASSET_URL')`.

Jika `providers`/`aliases` hilang, Vercel akan error "Target class [view] does not exist".

### 5.3 `config/view.php`

File ini wajib ada (copy dari `vendor/laravel/framework/config/view.php` jika hilang).

### 5.4 `app/Providers/AppServiceProvider.php`

Paksa skema HTTPS di production agar URL/form/asset tidak menjadi `http://`:

```php
public function boot(): void
{
    if (app()->environment('production')) {
        \Illuminate\Support\Facades\URL::forceScheme('https');
    }
}
```

### 5.5 `.env` (Production / Vercel)

```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://<nama-project>.vercel.app
ASSET_URL=https://<nama-project>.vercel.app
DB_CONNECTION=pgsql
DB_HOST=...
DB_PORT=6543
DB_DATABASE=postgres
DB_USERNAME=...
DB_PASSWORD=...
SESSION_DRIVER=cookie
CACHE_STORE=array
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=s3 (atau supabase)
```

### 5.6 Storage

- Jangan gunakan disk `local` untuk upload.
- Gunakan S3/Supabase Storage + presigned URL.

### 5.7 Cache/Session

- `SESSION_DRIVER=cookie`
- `CACHE_STORE=array` atau Redis (Upstash)
- Cache service/package/config diarahkan ke `/tmp` (karena filesystem read-only).

## 6. Catatan Penting dari Pengalaman Deploy

1. **Boost / dev dependencies**: `laravel/boost` hanya dev. Jangan commit `bootstrap/cache/services.php` yang memuat `BoostServiceProvider`, atau arahkan cache ke `/tmp` agar di-generate ulang tanpa boost di production.
2. **Cached manifests**: `bootstrap/cache/*.php` di-ignore oleh `.gitignore` default. Untuk Vercel, arahkan `APP_SERVICES_CACHE`, `APP_PACKAGES_CACHE`, `APP_CONFIG_CACHE` ke `/tmp` agar file di-generate otomatis.
3. **Mixed content (http vs https)**: `APP_URL` dan `ASSET_URL` harus HTTPS; tambahkan `URL::forceScheme('https')` di production. Jika tidak, CSS/JS/form action akan diblokir browser.
4. **Vite build**: jalankan `npm run build`, commit `public/build` (atau build di CI). Pastikan `.gitignore` tidak mengabaikan `/public/build`.
5. **Route prefix**: hindari prefix `/api` untuk endpoint publik di Vercel.
6. **Migrasi DB**: jalankan manual/CI, jangan di runtime Vercel.

## 7. Database

- Gunakan Supabase/Neon Postgres.
- Jalankan migrasi manual atau via CI/CD.
- Hindari `DB::statement` berat di request runtime.

## 8. Auth

- Firebase: verifikasi ID token di backend.
- Atau Sanctum untuk API token.
- Atau session cookie (dengan `SESSION_DRIVER=cookie`).

## 9. Batasan Serverless

- Filesystem read-only (kecuali `/tmp`).
- Tidak ada background worker / cron native.
- Gunakan Vercel Cron untuk scheduler (endpoint HTTP).
- Cold start mungkin terjadi.

## 10. Langkah Deploy

1. Pastikan `.env` production valid.
2. Jalankan `npm run build` (Vite) secara lokal/CI.
3. Commit `public/build` atau build di CI.
4. Push ke Git.
5. Import project di Vercel, set environment variables (atau gunakan `vercel.json` env).
6. Deploy.
7. Jalankan migrasi DB manual (jangan di runtime).
8. Jalankan `php artisan route:clear` / `config:clear` sebelum deploy.

## 11. Checklist

- [ ] `vercel.json` valid
- [ ] `api/index.php` ada
- [ ] `config/app.php` memuat `providers` dan `aliases`
- [ ] `config/view.php` ada
- [ ] `SESSION_DRIVER=cookie`
- [ ] `CACHE_STORE=array`
- [ ] `APP_URL` dan `ASSET_URL` HTTPS
- [ ] `URL::forceScheme('https')` di production
- [ ] Cache diarahkan ke `/tmp`
- [ ] Database cloud terhubung
- [ ] Storage eksternal (S3/Supabase)
- [ ] Tidak ada upload ke disk lokal
- [ ] Route publik tidak pakai prefix `/api`
- [ ] Migrasi DB sudah dijalankan
- [ ] `public/build` ter-commit (jika tidak build di CI)

## 12. Rollback

- Revert commit di Git → redeploy.
- Restore database dari backup jika migrasi gagal.

## 13. Catatan

- Untuk project `roomroom`, endpoint data siswa menggunakan `/data-siswa/api` (bukan `/api/students`) agar tidak diintersep Vercel.
- Gunakan `php artisan route:clear` sebelum deploy agar route baru terbaca.

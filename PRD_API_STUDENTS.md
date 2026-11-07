# PRD: Integrasi API Data Siswa (Read-Only) ke Laravel

## 1. Tujuan

Menyediakan panduan standar untuk mengintegrasikan data siswa dari REST API eksternal (read-only) ke aplikasi Laravel, digunakan pada modul sirkulasi perpustakaan (pencarian siswa & test sinkronisasi).

## 2. Ruang Lingkup

- Laravel 11/13 (PHP 8.3+)
- Konsumsi REST API (GET) dengan autentikasi Bearer / X-API-Key
- Tidak menyimpan data siswa secara penuh di database lokal (hanya disimpan saat transaksi untuk riwayat)
- Endpoint backend Laravel untuk test sinkron dan pencarian siswa

## 3. Spesifikasi API Eksternal

### 3.1 Endpoint dasar

```
GET https://<your-domain>/data-siswa/api
```

### 3.2 Header (pilih salah satu)

- `Authorization: Bearer <API_KEY>`
- `X-API-Key: <API_KEY>`

Rekomendasi: kirim **keduanya** untuk kompatibilitas.

### 3.3 Parameter (opsional)

- `kelas` — filter per kelas
- `nisn` — cari berdasarkan NISN
- `nama` — cari berdasarkan nama
- `limit` — jumlah data (default 5, maks 50)

### 3.4 Response

```json
{
  "data": [
    {
      "nisn": "...",
      "nama_lengkap": "...",
      "kelas": "X-A",
      ...
    }
  ]
}
```

Field yang dipakai aplikasi: `nisn`, `nama_lengkap`, `kelas`.

## 4. Konfigurasi Laravel

### 4.1 Environment Variables

```
STUDENT_API_URL=https://<your-domain>/data-siswa/api
STUDENT_API_TOKEN=<API_KEY>
```

### 4.2 `config/services.php`

```php
'student_api' => [
    'url' => env('STUDENT_API_URL', 'https://roomroom-nine.vercel.app/data-siswa/api'),
    'token' => env('STUDENT_API_TOKEN'),
],
```

## 5. Implementasi Backend

### 5.1 Test Sinkronisasi (GET)

Route: `GET /api-test/test`

- Kirim GET request ke API dengan header auth.
- Jika HTTP 200 dan node `data` terbaca → sukses.
- Return JSON: `{ success, message, status, count }`.

### 5.2 Pencarian Siswa (GET)

Route: `GET /borrowings/search-students?name=...`

- Tentukan parameter: jika input angka → `nisn`, selain itu → `nama`.
- Kirim GET request dengan query `limit=50`.
- Mapping response `nama_lengkap` → `nama`, `nisn`, `kelas`.
- Filter lokal sebagai fallback (nama/nisn contains).
- Return JSON: `{ success, message, students: [{ nama, nisn, kelas }] }`.

### 5.3 HTTP Client

- Gunakan `Http::timeout(15)->acceptJson()`.
- Tambahkan header: `Authorization: Bearer <token>` dan `X-API-Key: <token>`.
- Jangan simpan data siswa di DB lokal kecuali saat transaksi (untuk riwayat).

## 6. Implementasi Frontend

- Halaman Test Sinkron: tombol "Test Sinkron" memanggil endpoint via GET (fetch), menampilkan notifikasi berhasil/gagal.
- Halaman Transaksi Baru:
  - Input teks nama + tombol "Cari".
  - Panggil endpoint pencarian via GET (query `name`).
  - Tampilkan hasil (Nama, NISN, Kelas) sebagai daftar/kartu.
  - Pilih siswa → isi hidden field `student_nisn`, `student_name`.
  - Tambahkan satu/banyak buku ke keranjang → submit transaksi (POST).

## 7. Acceptance Criteria

- Test Sinkron: jika API merespons 200 + node `data` terbaca → "Sinkronisasi Berhasil"; jika gagal → pesan error.
- Pencarian: input nama/NISN + tombol Cari → tampilkan daftar siswa (Nama, NISN, Kelas).
- Transaksi: pilih siswa + banyak buku → satu ID transaksi, stok buku berkurang.

## 8. Batasan / Catatan

- API read-only, jangan coba POST/PUT/DELETE.
- Hindari menyimpan data siswa penuh di DB lokal.
- Untuk Vercel: gunakan GET untuk endpoint internal agar tidak terkena CSRF; set `STUDENT_API_URL` dan `STUDENT_API_TOKEN` di environment variables.
- Jika API mengembalikan 401, cek token dan domain (pastikan `STUDENT_API_URL` sesuai instance yang menerbitkan token).

## 9. Contoh curl

```bash
curl -H "Authorization: Bearer <API_KEY>" -H "X-API-Key: <API_KEY>" \
  "https://<your-domain>/data-siswa/api?nama=andi&limit=10"
```

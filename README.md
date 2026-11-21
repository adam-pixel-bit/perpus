# SISTEM INFORMASI PERPUSTAKAAN BERBASIS WEB (SIPUS DIGITAL)

Aplikasi web Sistem Informasi Perpustakaan modern yang mengintegrasikan pengelolaan data buku, kategori, sirkulasi peminjaman, pengembalian buku, denda keterlambatan, manajemen pengguna, dan laporan analitik dengan menerapkan **Role-Based Access Control (RBAC)** antara **Administrator** dan **Anggota Perpustakaan (User)**.

---

## 🚀 Tech Stack

- **Backend:** Laravel 12 (PHP 8.4+)
- **Frontend:** Laravel Blade Templating
- **Styling:** Tailwind CSS v4
- **Database:** Supabase PostgreSQL (driver `pgsql`) & SQLite untuk local dev
- **Build Tool:** Vite
- **Authentication:** Laravel Session Authentication (RBAC Admin & User)
- **Icons:** Lucide Icons
- **Code Formatter & Testing:** Laravel Pint & PHPUnit

---

## 🔑 Akun Demo Siap Pakai

Sistem telah dilengkapi dengan seeder akun demo untuk pengujian langsung:

| Peran (Role) | Alamat Email | Password | Hak Akses |
|---|---|---|---|
| **Administrator** | `admin@perpustakaan.com` | `password123` | Akses Penuh: Dashboard Admin, CRUD Buku, Kategori, Sirkulasi, Kelola Pengguna, Laporan |
| **Anggota (User 1)** | `budi@perpustakaan.com` | `password123` | Portal Anggota: Dashboard, Jelajah Katalog, Ajukan Pinjam, Riwayat Peminjaman |
| **Anggota (User 2)** | `siti@perpustakaan.com` | `password123` | Portal Anggota |
| **Anggota (User 3)** | `andi@perpustakaan.com` | `password123` | Portal Anggota (memiliki data keterlambatan peminjaman) |

*(Tersedia tombol satu-klik isi otomatis pada form login untuk memudahkan pengujian).*

---

## 📋 Fitur Utama Sistem

### A. Fitur Administrator
1. **Dashboard Admin**:
   - Kartu statistik: Total Buku, Kategori, Total Anggota, dan Total Sirkulasi.
   - Peringatan status: Peminjaman Menunggu Persetujuan, Sedang Dipinjam, dan Terlambat Kembali.
   - Tabel aktivitas transaksi sirkulasi terbaru beserta tombol aksi cepat persetujuan.
   - Widget daftar buku terpopuler berdasarkan frekuensi peminjaman.
2. **Manajemen Buku (CRUD)**:
   - Menambah buku baru (judul, ISBN, penulis, penerbit, tahun terbit, stok, lokasi rak, cover, sinopsis).
   - Menampilkan daftar koleksi dengan fitur pencarian teks, filter kategori, dan ketersediaan stok.
   - Mengedit data buku dengan validasi stok tidak boleh lebih kecil dari eksemplar yang sedang dipinjam.
   - Menghapus buku (dilengkapi proteksi bisnis: buku yang sedang dipinjam dilarang dihapus).
   - Dukungan unggah gambar cover lokal (storage) maupun URL gambar eksternal.
3. **Manajemen Kategori (CRUD)**:
   - Tambah, lihat, dan edit kategori buku secara responsif.
   - Hapus kategori (dilengkapi proteksi bisnis: kategori yang masih memiliki relasi buku tidak dapat dihapus).
4. **Manajemen Pengguna**:
   - Melihat seluruh pengguna terdaftar, status akun, dan total transaksi peminjaman.
   - Melihat rincian profil pengguna dan seluruh riwayat peminjaman buku mereka.
   - Mengedit informasi pengguna, mengubah hak akses peran (Admin / User).
   - Mengaktifkan atau menonaktifkan akun anggota.
5. **Sirkulasi Peminjaman & Pengembalian**:
   - Tab navigasi status: *Semua, Menunggu Persetujuan, Sedang Dipinjam, Terlambat, Selesai*.
   - Filter rentang tanggal transaksi dan pencarian kode transaksi.
   - **Persetujuan Peminjaman**: Menggunakan Database Transaction (`DB::transaction`) untuk memastikan pengurangan stok secara atomik dan aman dari *race condition*.
   - **Penolakan Peminjaman**: Disertai catatan alasan penolakan dari admin.
   - **Pemrosesan Pengembalian**: Menambah kembali stok buku dan secara otomatis menghitung denda keterlambatan (Rp 2.000 / hari) jika melewati tanggal jatuh tempo.
6. **Laporan & Rekapitulasi**:
   - Laporan transaksi peminjaman pada periode tanggal tertentu.
   - Laporan 10 buku paling sering dipinjam.
   - Laporan keaktifan anggota perpustakaan.
   - Ekspor laporan ke format file **CSV** dan mode cetak (**Print-friendly**).

---

### B. Fitur Anggota (User)
1. **Dashboard Anggota**:
   - Statistik pribadi: Buku sedang dipinjam, pengajuan menunggu persetujuan, dan buku selesai dibaca.
   - Kartu Anggota Digital.
   - Daftar peminjaman aktif dan batas tanggal jatuh tempo pengembalian.
   - Rekomendasi koleksi buku yang tersedia untuk dipinjam.
2. **Katalog Buku**:
   - Tampilan visual kartu buku dengan indikator ketersediaan stok secara real-time.
   - Pencarian instan berdasarkan judul, pengarang, penerbit, atau nomor ISBN.
   - Filter cepat berdasarkan kategori buku.
   - Modal cepat pengajuan peminjaman langsung dari katalog.
3. **Detail Buku**:
   - Menampilkan sinopsis lengkap, lokasi penataan rak fisik di perpustakaan, dan nomor ISBN.
   - Form pengajuan durasi pinjam (3, 7, 10, atau 14 hari) dan catatan keperluan.
   - Rekomendasi buku lain yang sejenis dalam kategori yang sama.
4. **Riwayat Peminjaman**:
   - Rekam jejak seluruh transaksi peminjaman pribadi dengan status transparan (*Menunggu Persetujuan, Disetujui, Sedang Dipinjam, Sudah Dikembalikan, Terlambat, Ditolak*).
   - Membatalkan pengajuan peminjaman mandiri jika status masih *Menunggu Persetujuan*.

---

## ⚖️ Aturan Bisnis yang Diterapkan

- **Integritas Stok Fisik:** Stok buku berkurang hanya ketika peminjaman disetujui oleh admin, dan bertambah kembali ketika buku dikembalikan.
- **Pencegahan Stok Negatif:** Sistem memvalidasi ketersediaan stok secara atomik dengan `lockForUpdate()` di dalam `DB::transaction`.
- **Batas Kuota Anggota:** Anggota dibatasi meminjam maksimal **3 buku aktif** secara bersamaan.
- **Pencegahan Duplikasi:** Anggota tidak dapat mengajukan peminjaman buku yang sama jika buku tersebut masih dalam proses pinjam atau belum dikembalikan.
- **Isolasi Transaksi:** Anggota hanya dapat melihat dan mengakses data transaksi miliknya sendiri.

---

## 📦 Panduan Instalasi & Menjalankan Aplikasi

### 1. Kebutuhan Sistem
- PHP >= 8.4
- Composer >= 2.0
- Ekstensi PHP: `pdo_pgsql` (untuk Supabase) atau `pdo_sqlite`
- Node.js >= 20 & npm

### 2. Instalasi Dependensi
```bash
# Clone atau buka direktori proyek
cd "DAY 4"

# Instal dependensi backend PHP
composer install

# Instal dependensi frontend Node.js
npm install
```

### 3. Konfigurasi Environment (`.env`)
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Konfigurasi Supabase PostgreSQL
Buka file `.env` dan perbarui baris koneksi database dengan kredensial dari dashboard Supabase Anda (**Project Settings** $\rightarrow$ **Database**):

```env
DB_CONNECTION=pgsql
DB_HOST=aws-0-ap-southeast-1.pooler.supabase.com
DB_PORT=6543
DB_DATABASE=postgres
DB_USERNAME=postgres.your-project-ref
DB_PASSWORD=your-supabase-db-password
DB_SSLMODE=require
```

*(Catatan: Aplikasi secara default juga siap dijalankan langsung menggunakan database SQLite lokal tanpa konfigurasi tambahan).*

### 5. Jalankan Migrasi dan Seeder
Eksekusi migrasi tabel dan pengisian data awal katalog buku serta akun demo:
```bash
php artisan migrate:fresh --seed
```

### 6. Hubungkan Storage Simbolik
```bash
php artisan storage:link
```

### 7. Build Aset Frontend
```bash
npm run build
```

### 8. Jalankan Server Aplikasi
Jalankan server pengembangan Laravel:
```bash
php artisan serve
```

Buka peramban (browser) di alamat: **[http://localhost:8000](http://localhost:8000)**

---

## 🧪 Menjalankan Pengujian Otomatis (Automated Testing)

Seluruh logika bisnis, otentikasi role, CRUD buku, dan perputaran transaksi sirkulasi stok telah diuji menggunakan PHPUnit:

```bash
php artisan test --compact
```

Hasil pengujian: **14 Passed / 14 Tests (39 Assertions)**.

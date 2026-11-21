# 📚 SIPUS Digital — Enterprise Integrated Library Management System

<div align="center">

![SIPUS Banner](https://img.shields.io/badge/SIPUS-Digital%20Library%20System-3B82F6?style=for-the-badge&logo=bookstack&logoColor=white)

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-v4.0-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![Supabase](https://img.shields.io/badge/Supabase-PostgreSQL-3ECF8E?style=flat-square&logo=supabase&logoColor=white)](https://supabase.com)
[![Tests](https://img.shields.io/badge/Tests-27%20Passed%20(80%20Assertions)-brightgreen?style=flat-square&logo=phpunit&logoColor=white)](#-automated-testing--quality-assurance)
[![Pint](https://img.shields.io/badge/Code%20Style-Laravel%20Pint-F9A03F?style=flat-square)](https://laravel.com/docs/pint)
[![License](https://img.shields.io/badge/License-MIT-blue?style=flat-square)](LICENSE)

<p align="center">
  <b>Sistem Informasi Perpustakaan Digital Berbasis Web yang Modern, Aman, dan Siap Produksi.</b><br>
  Dirancang dengan arsitektur tangguh untuk sekolah, universitas, dan instansi umum dengan integrasi Supabase PostgreSQL Cloud, Real-Time Webcam Barcode Scanner, Tiket QR Code, Slip Termal Kasir, Cetak Laporan Resmi A4 Berstempel, dan Audit Trail Lengkap.
</p>

[Fitur Utama](#-fitur-utama--modul-sistem) •
[Demo Akun](#-akun-demo-pengujian) •
[Instalasi](#-panduan-instalasi--konfigurasi) •
[Arsitektur & Keamanan](#-arsitektur--keamanan-sistem) •
[Testing](#-automated-testing--quality-assurance)

---

</div>

## 🌟 Highlights Fitur Unggulan

- 🎯 **Role-Based Access Control (RBAC)** — Hak akses terisolasi untuk **Administrator**, **Petugas (Staff)**, dan **Anggota (User)**.
- 📷 **Live Webcam Circulation Desk** — Scan QR Code / Barcode tiket peminjaman dan buku secara langsung menggunakan kamera laptop/ponsel dilengkapi sound feedback dan laser visualizer.
- 🎟️ **Digital QR Ticketing & KTA Card** — Setiap transaksi peminjaman memiliki e-Ticket QR Code unik, dan setiap anggota memiliki Kartu Tanda Anggota (KTA) digital siap cetak.
- 🧾 **Thermal POS Receipt (80mm)** — Cetak struk bukti peminjaman dan pelunasan denda bergaya kasir fisik mini POS.
- 📑 **Laporan Resmi A4 Landscape** — Rekapitulasi transaksi peminjaman dan denda terintegrasi tanda tangan digital Kepala Perpustakaan & Petugas.
- 💳 **Manajemen Pembayaran Denda** — Pencatatan pelunasan denda otomatis, petugas penerima, waktu bayar, dan audit riwayat kas denda.
- 🔄 **Perpanjangan Mandiri (+7 Hari)** — Anggota dapat mengajukan perpanjangan masa pinjam secara mandiri jika belum melewati tanggal jatuh tempo.
- ⏱️ **Kalkulasi Denda Otomatis via Scheduler** — Background worker harian (`perpus:calculate-fines`) menghitung denda keterlambatan secara otomatis.
- 🛡️ **Comprehensive Audit Logging** — Seluruh aktivitas kritikal tercatat otomatis (login, logout, modifikasi katalog, sirkulasi buku, penerimaan denda).
- ⭐ **Rating & Ulasan Koleksi Buku** — Anggota dapat memberikan rating bintang 1–5 serta testimoni untuk buku yang telah selesai dibaca.
- 📖 **Pengadaan & Usulan Buku Baru** — Siswa/anggota dapat mengusulkan buku yang belum tersedia ke perpustakaan dengan status approval langsung dari admin.

---

## 🔑 Akun Demo Pengujian

Aplikasi telah dilengkapi seeder akun untuk pengujian fungsionalitas di semua level otorisasi:

| Peran (Role) | Alamat Email | Password | Hak Akses Utama |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin@perpustakaan.test` | `password` | Kendali sistem penuh, audit logs, kelola user, scanner desk, konfirmasi usulan buku, ekspor laporan. |
| **Petugas / Staff** | `petugas@perpustakaan.test` | `password` | Meja sirkulasi, persetujuan peminjaman, pengembalian buku, penerimaan denda, scan kamera. |
| **Anggota 1** | `budi@perpustakaan.test` | `password` | Eksplorasi katalog, pinjam buku, ulasan buku, perpanjang waktu, usulkan buku, cetak KTA. |
| **Anggota 2** | `siti@perpustakaan.test` | `password` | Eksplorasi katalog, riwayat peminjaman, pelunasan denda, cetak KTA digital. |

> **Catatan:** Form login juga dilengkapi tombol _One-Click Quick Fill_ untuk mempermudah login tanpa perlu mengetik manual.

---

## 📋 Fitur Utama & Modul Sistem

### 1. Modul Sirkulasi & Scanner Meja Petugas
- **Webcam Barcode/QR Scanner**: Deteksi instan kamera menggunakan modul HTML5 `getUserMedia` + ZXing engine.
- **Validasi Transaksi Atomik**: Menggunakan database pessimistic locking (`lockForUpdate`) di dalam `DB::transaction` untuk mencegah insiden *race condition* atau stok buku minus.
- **Sirkulasi Lengkap**: Pengajuan $\rightarrow$ Persetujuan Petugas $\rightarrow$ Peminjaman Berjalan $\rightarrow$ Perpanjangan Mandiri $\rightarrow$ Pengembalian & Perhitungan Denda.
- **Struk Termal POS**: Cetak struk 80mm untuk diserahkan ke anggota saat mengambil buku di meja sirkulasi.

### 2. Modul Manajemen Koleksi & Katalog
- **CRUD Buku Komprehensif**: Judul, ISBN, Pengarang, Penerbit, Tahun Terbit, Kategori, Stok Fisik, Lokasi Rak, Sinopsis, dan Cover Gambar.
- **Pencarian Instan (Live Search)**: Debounced live query berbasis judul, pengarang, penerbit, maupun nomor ISBN.
- **Ulasan & Rating**: Menampilkan ulasan terverifikasi dari anggota yang sudah pernah membaca buku tersebut.
- **Proteksi Hapus Data**: Koleksi buku yang sedang dipinjam secara aktif dilarang dihapus oleh sistem.

### 3. Modul Anggota & Self-Service
- **Kartu Tanda Anggota (KTA)**: Kartu identitas digital ber-QR Code dengan tombol cetak format kartu fisik.
- **Pengajuan Usulan Buku**: Anggota dapat mengirimkan judul, pengarang, dan alasan rekomendasi buku baru ke tim perpustakaan.
- **Manajemen Profil**: Penggantian data pribadi, upload foto avatar profil, serta pembaruan kata sandi akun secara aman.

### 4. Modul Laporan & Audit Trail
- **Laporan Resmi A4 Landscape**: Rekapitulasi transaksi, filter tanggal, status sirkulasi, total denda terhimpun, dilengkapi kolom tanda tangan resmi.
- **Ekspor Data CSV**: Kompatibel dengan spreadsheet Microsoft Excel dan Google Sheets.
- **Audit Logging**: Mencatat aktor, peran, IP address, deskripsi aksi, serta *timestamp* setiap kali ada perubahan data signifikan.

---

## 🛠️ Tech Stack & Spesifikasi Teknis

| Komponen | Teknologi yang Digunakan |
| :--- | :--- |
| **Framework** | Laravel 12.x (PHP 8.4+) |
| **Database** | Supabase Cloud PostgreSQL (Port 6543 / Pooler) & SQLite Support |
| **Frontend** | Blade Templating Engine + Tailwind CSS v4 + Alpine.js |
| **Iconography** | Lucide Icons |
| **Kamera & QR Engine** | HTML5 Camera Stream API + Web Audio Synthesizer Beep |
| **Asset Bundler** | Vite 6 |
| **Linting & Formatting** | Laravel Pint |
| **Testing Suite** | PHPUnit (27 Feature & Unit Tests, 80 Assertions) |

---

## 📦 Panduan Instalasi & Konfigurasi

### Prasyarat Sistem
- PHP >= 8.4 (dengan ekstensi `pdo_pgsql`, `pdo_sqlite`, `curl`, `mbstring`, `openssl`)
- Composer >= 2.2
- Node.js >= 20.x & npm
- Akun Supabase (opsional jika ingin memakai PostgreSQL cloud)

### Langkah-Langkah Instalasi:

1. **Clone Repositori:**
   ```bash
   git clone https://github.com/adam-pixel-bit/perpus.git
   cd perpus
   ```

2. **Instal Dependensi PHP & Node.js:**
   ```bash
   composer install
   npm install
   ```

3. **Setup File Lingkungan (`.env`):**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Konfigurasi Database:**
   
   **Opsi A: Menggunakan Supabase PostgreSQL (Direkomendasikan untuk Production)**
   Buka file `.env` dan sesuaikan parameter berikut:
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=aws-1-ap-southeast-1.pooler.supabase.com
   DB_PORT=6543
   DB_DATABASE=postgres
   DB_USERNAME=postgres.your-project-ref
   DB_PASSWORD="your-strong-password"
   DB_SSLMODE=require
   ```

   **Opsi B: Menggunakan SQLite (Untuk Pengujian Cepat Lokal)**
   ```env
   DB_CONNECTION=sqlite
   ```
   *(Buat file kosong `touch database/database.sqlite` jika belum ada).*

5. **Jalankan Migrasi & Data Seeder:**
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Buat Symbolic Link Storage:**
   ```bash
   php artisan storage:link
   ```

7. **Kompilasi Aset Frontend:**
   ```bash
   npm run build
   ```

8. **Jalankan Aplikasi:**
   ```bash
   php artisan serve
   ```
   Aplikasi siap diakses pada: **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## ⚡ Perintah Otomatisasi (Cron Job / Scheduler)

Perpustakaan ini dilengkapi perintah command line artisan untuk mengkalkulasi denda secara berkala:

```bash
# Menjalankan kalkulasi denda manual
php artisan perpus:calculate-fines

# Menjalankan worker scheduler Laravel di background
php artisan schedule:work
```

Tambahkan entri cron berikut pada server production Anda:
```cron
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🧪 Automated Testing & Quality Assurance

Aplikasi ini menjamin keandalan transaksi sirkulasi stok, mitigasi race-condition, dan validasi hak akses melalui pengujian unit & integrasi otomatis:

```bash
# Menjalankan seluruh rangkaian tes unit & fitur
php artisan test --compact

# Memeriksa dan memformat standar kode sesuai PSR-12 / Laravel
vendor/bin/pint --format agent
```

### Hasil Ringkasan Pengujian:
```text
✓ Auth & Session Middleware Protection
✓ Book CRUD with Stock Integrity
✓ Pessimistic Locking on Borrowing Approvals
✓ Automatic Overdue Fine Calculation
✓ QR Code Generation & Receipt Printing
✓ Profile & Avatar Updates
✓ Fines Payment Processing
✓ Book Request Workflow

Tests:    27 passed (80 assertions)
Duration: 2.03s
```

---

## 🔒 Arsitektur & Keamanan Sistem

- **Atomisitas Stok (ACID Compliant):** Menggunakan `DB::transaction()` dipadukan dengan `lockForUpdate()` sehingga mencegah stok berkurang lebih dari jumlah ketersediaan saat banyak pengguna mengajukan secara bersamaan.
- **Proteksi Akses Berlapis:** Seluruh rute dilindungi middleware `auth` dan pengecekan role granular (`AdminMiddleware`, `StaffMiddleware`).
- **Audit Logging:** Setiap operasi penambahan/pengurangan stok, persetujuan pinjam, penolakan, pelunasan denda, dan otentikasi disimpan ke tabel `audit_logs` dengan informasi IP & User Agent.
- **CSRF & XSS Shield:** Sanitasi input Blade otomatis dan verifikasi token CSRF di seluruh form interaktif.

---

## 👥 Kontributor & Lisensi

Dibuat dengan ❤️ untuk modernisasi perpustakaan instansi dan sekolah di Indonesia.  
Didistribusikan di bawah lisensi open-source **[MIT License](LICENSE)**.

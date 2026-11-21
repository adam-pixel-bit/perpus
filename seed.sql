-- ==============================================================================
-- SIPUS DIGITAL - SUPABASE DATABASE SEED DATA
-- File: seed.sql
-- Deskripsi: Data awal (Seeder) untuk Administrator, Anggota, Kategori Buku,
--            Koleksi Buku Terpopuler, Sirkulasi Peminjaman, dan Notifikasi.
-- ==============================================================================

-- 1. BERSIHKAN DATA SEBELUMNYA (Opsional tapi aman untuk mencegah duplikasi)
TRUNCATE TABLE book_requests, audit_logs, reviews, notifications, borrowings, books, categories, users RESTART IDENTITY CASCADE;

-- 2. INSERT USERS (Password default semua akun: password123)
-- Hash bcrypt '$2y$12$G.DZwCIVzsdFY3niJ6CCp.r2zhyO4t2V/NCr.jdvkYsim656Jgj3i' = 'password123'
INSERT INTO users (id, name, email, password, role, phone, address, is_active, created_at, updated_at) VALUES
(1, 'Administrator Perpustakaan', 'admin@perpustakaan.com', '$2y$12$G.DZwCIVzsdFY3niJ6CCp.r2zhyO4t2V/NCr.jdvkYsim656Jgj3i', 'admin', '081234567890', 'Gedung Perpustakaan Pusat Lt. 2, Jakarta', true, NOW(), NOW()),
(2, 'Budi Santoso', 'budi@perpustakaan.com', '$2y$12$G.DZwCIVzsdFY3niJ6CCp.r2zhyO4t2V/NCr.jdvkYsim656Jgj3i', 'user', '081298765432', 'Jl. Merdeka No. 45, Jakarta Barat', true, NOW(), NOW()),
(3, 'Siti Rahma', 'siti@perpustakaan.com', '$2y$12$G.DZwCIVzsdFY3niJ6CCp.r2zhyO4t2V/NCr.jdvkYsim656Jgj3i', 'user', '081345678901', 'Jl. Mawar No. 12, Bandung', true, NOW(), NOW()),
(4, 'Andi Wijaya', 'andi@perpustakaan.com', '$2y$12$G.DZwCIVzsdFY3niJ6CCp.r2zhyO4t2V/NCr.jdvkYsim656Jgj3i', 'user', '081567890123', 'Jl. Diponegoro No. 8, Surabaya', true, NOW(), NOW());

-- Sesuaikan sequence ID users
SELECT setval('users_id_seq', (SELECT MAX(id) FROM users));

-- 3. INSERT CATEGORIES
INSERT INTO categories (id, name, slug, description, created_at, updated_at) VALUES
(1, 'Teknologi & Komputer', 'teknologi-komputer', 'Buku-buku mengenai pemrograman, kecerdasan buatan, jaringan, dan arsitektur sistem.', NOW(), NOW()),
(2, 'Sains & Matematika', 'sains-matematika', 'Literatur ilmiah, fisika, kimia, biologi, dan matematika murni maupun terapan.', NOW(), NOW()),
(3, 'Sastra & Fiksi', 'sastra-fiksi', 'Novel, cerpen, puisi, roman, dan karya sastra klasik maupun modern.', NOW(), NOW()),
(4, 'Sejarah & Budaya', 'sejarah-budaya', 'Kajian sejarah peradaban dunia, antropologi, arkeologi, dan warisan budaya Nusantara.', NOW(), NOW()),
(5, 'Bisnis & Manajemen', 'bisnis-manajemen', 'Strategi bisnis, akuntansi, kewirausahaan, keuangan, dan kepemimpinan korporat.', NOW(), NOW()),
(6, 'Pengembangan Diri', 'pengembangan-diri', 'Motivasi, psikologi praktis, produktivitas kerja, dan pembentukan kebiasaan positif.', NOW(), NOW());

-- Sesuaikan sequence ID categories
SELECT setval('categories_id_seq', (SELECT MAX(id) FROM categories));

-- 4. INSERT BOOKS
INSERT INTO books (id, category_id, title, slug, isbn, author, publisher, publication_year, stock, available_stock, shelf_location, description, cover_image, is_available, created_at, updated_at) VALUES
(1, 1, 'Clean Code: A Handbook of Agile Software Craftsmanship', 'clean-code-a-handbook-of-agile-software-craftsmanship', '978-0132350884', 'Robert C. Martin', 'Prentice Hall', 2008, 5, 3, 'Rak TK-01', 'Panduan fundamental mengenai penulisan kode yang bersih, mudah dibaca, dan mudah dirawat dalam pengembangan perangkat lunak modern.', 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=600&q=80', true, NOW(), NOW()),
(2, 1, 'Designing Data-Intensive Applications', 'designing-data-intensive-applications', '978-1449373320', 'Martin Kleppmann', 'O''Reilly Media', 2017, 4, 4, 'Rak TK-02', 'Panduan komprehensif tentang prinsip dasar dan arsitektur sistem terdistribusi, replikasi data, partisi, konsistensi, dan pemrosesan stream.', 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?auto=format&fit=crop&w=600&q=80', true, NOW(), NOW()),
(3, 6, 'Atomic Habits: Perubahan Kecil yang Memberikan Hasil Luar Biasa', 'atomic-habits-perubahan-kecil-yang-memberikan-hasil-luar-biasa', '978-6020633176', 'James Clear', 'Gramedia Pustaka Utama', 2019, 6, 4, 'Rak PD-01', 'Buku praktis tentang bagaimana kebiasaan kecil sehari-hari dapat terakumulasi menjadi pencapaian luar biasa dalam jangka panjang.', 'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?auto=format&fit=crop&w=600&q=80', true, NOW(), NOW()),
(4, 2, 'A Brief History of Time: Dari Dentuman Besar hingga Lubang Hitam', 'a-brief-history-of-time-dari-dentuman-besar-hingga-lubang-hitam', '978-9792228809', 'Stephen Hawking', 'Bantam Books', 1988, 3, 2, 'Rak SM-03', 'Penjelasan menakjubkan tentang asal mula alam semesta, lubang hitam, ruang-waktu, dan teori relativitas untuk pembaca umum.', 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=600&q=80', true, NOW(), NOW()),
(5, 3, 'Bumi Manusia (Tetralogi Buru Buku 1)', 'bumi-manusia-tetralogi-buru-buku-1', '978-9799731234', 'Pramoedya Ananta Toer', 'Lentera Dipantara', 1980, 5, 5, 'Rak SF-01', 'Karya masterpiece sastra Indonesia yang mengisahkan perjuangan Minke dan Annelies di masa kolonial Hindia Belanda awal abad ke-20.', 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&w=600&q=80', true, NOW(), NOW()),
(6, 5, 'The Lean Startup: How Constant Innovation Creates Radically Successful Businesses', 'the-lean-startup-how-constant-innovation-creates-radically-successful-businesses', '978-0307887894', 'Eric Ries', 'Crown Business', 2011, 4, 3, 'Rak BM-02', 'Metodologi inovasi bisnis cepat melalui siklus Build-Measure-Learn untuk meminimalkan risiko kegagalan startup.', 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&w=600&q=80', true, NOW(), NOW()),
(7, 4, 'Nusantara: Sejarah Indonesia', 'nusantara-sejarah-indonesia', '978-6024240578', 'Bernard H.M. Vlekke', 'Komunitas Bambu', 2008, 2, 2, 'Rak SB-01', 'Kajian sejarah komprehensif kepulauan Nusantara sejak zaman prasejarah hingga terbentuknya negara-bangsa Indonesia modern.', 'https://images.unsplash.com/photo-1461360370896-922624d12aa1?auto=format&fit=crop&w=600&q=80', true, NOW(), NOW()),
(8, 6, 'Filosofi Teras: Hindari Stres dengan Stoikisme', 'filosofi-teras-hindari-stres-dengan-stoikisme', '978-6024125189', 'Henry Manampiring', 'Buku Kompas', 2018, 4, 4, 'Rak PD-02', 'Penerapan filosofi Stoa kuno Yunani-Romawi dalam mengelola emosi negatif dan menjalani kehidupan modern yang tenang.', 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=600&q=80', true, NOW(), NOW());

-- Sesuaikan sequence ID books
SELECT setval('books_id_seq', (SELECT MAX(id) FROM books));

-- 5. INSERT BORROWINGS SAMPLE TRANSACTIONS
INSERT INTO borrowings (id, user_id, book_id, borrowing_code, borrow_date, return_due_date, actual_return_date, status, notes, admin_note, fines, approved_by, returned_to, created_at, updated_at) VALUES
-- Transaksi 1: Sedang dipinjam (Aktif)
(1, 2, 1, 'BRW-202611-0001', CURRENT_DATE - INTERVAL '3 days', CURRENT_DATE + INTERVAL '4 days', NULL, 'borrowed', 'Untuk bahan referensi skripsi rekayasa perangkat lunak.', NULL, 0.00, 1, NULL, NOW(), NOW()),

-- Transaksi 2: Menunggu persetujuan admin (Pending)
(2, 3, 3, 'BRW-202611-0002', CURRENT_DATE, CURRENT_DATE + INTERVAL '7 days', NULL, 'pending', 'Mohon persetujuannya untuk tugas pembinaan karakter.', NULL, 0.00, NULL, NULL, NOW(), NOW()),

-- Transaksi 3: Telat pengembalian (Overdue)
(3, 4, 4, 'BRW-202611-0003', CURRENT_DATE - INTERVAL '12 days', CURRENT_DATE - INTERVAL '2 days', NULL, 'borrowed', 'Kajian astronomi dan kosmologi.', NULL, 0.00, 1, NULL, NOW(), NOW()),

-- Transaksi 4: Selesai dikembalikan (Returned)
(4, 2, 6, 'BRW-202611-0004', CURRENT_DATE - INTERVAL '14 days', CURRENT_DATE - INTERVAL '7 days', CURRENT_DATE - INTERVAL '8 days', 'returned', 'Buku sangat inspiratif dan bermanfaat.', 'Buku dikembalikan dalam kondisi sangat baik.', 0.00, 1, 1, NOW(), NOW());

-- Sesuaikan sequence ID borrowings
SELECT setval('borrowings_id_seq', (SELECT MAX(id) FROM borrowings));

-- 6. INSERT NOTIFICATIONS
INSERT INTO notifications (id, user_id, title, message, type, is_read, link, created_at, updated_at) VALUES
(1, 2, 'Peminjaman Disetujui', 'Peminjaman buku "Clean Code" telah disetujui. Silakan ambil buku di perpustakaan.', 'success', true, '/user/borrowings', NOW(), NOW()),
(2, 4, 'Pengingat Pengembalian Buku', 'Peminjaman buku "A Brief History of Time" telah melewati batas waktu pengembalian. Segera kembalikan ke perpustakaan.', 'warning', false, '/user/borrowings', NOW(), NOW());

-- Sesuaikan sequence ID notifications
SELECT setval('notifications_id_seq', (SELECT MAX(id) FROM notifications));

-- 7. INSERT SAMPLE REVIEWS
INSERT INTO reviews (id, user_id, book_id, borrowing_id, rating, comment, created_at, updated_at) VALUES
(1, 2, 6, 4, 5, 'Buku The Lean Startup sangat membuka wawasan tentang cara membangun produk dengan efisien melalui validasi nyata.', NOW(), NOW()),
(2, 2, 1, 1, 5, 'Clean Code wajib dibaca setiap software engineer! Pembahasannya tentang penamaan variabel dan fungsi sangat relevan.', NOW(), NOW());

SELECT setval('reviews_id_seq', (SELECT MAX(id) FROM reviews));

-- 8. INSERT INITIAL AUDIT LOGS
INSERT INTO audit_logs (id, user_id, action, description, ip_address, user_agent, created_at) VALUES
(1, 1, 'system_initialized', 'Inisialisasi sistem perpustakaan SIPUS Digital berhasil dijalankan.', '127.0.0.1', 'Database Seeder', NOW());

SELECT setval('audit_logs_id_seq', (SELECT MAX(id) FROM audit_logs));

-- 9. INSERT SAMPLE BOOK REQUESTS
INSERT INTO book_requests (id, user_id, title, author, publisher, publication_year, isbn, reason, status, admin_note, reviewed_by, created_at, updated_at) VALUES
(1, 2, 'Refactoring: Improving the Design of Existing Code', 'Martin Fowler', 'Addison-Wesley Professional', 2018, '978-0134757599', 'Buku ini sangat penting untuk referensi teknik refaktorisasi kode yang bersih dalam praktikum perangkat lunak.', 'approved', 'Disetujui untuk pengadaan anggaran triwulan ini.', 1, NOW(), NOW());

SELECT setval('book_requests_id_seq', (SELECT MAX(id) FROM book_requests));

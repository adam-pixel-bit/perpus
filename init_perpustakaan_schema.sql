-- ==============================================================================
-- SIPUS DIGITAL - SUPABASE DATABASE SCHEMA INITIALIZATION
-- File: init_perpustakaan_schema.sql
-- Deskripsi: Script SQL untuk inisialisasi tabel, constraint, index, trigger,
--            dan Row Level Security (RLS) di Supabase SQL Editor.
-- ==============================================================================

-- 1. DROP EXISTING TABLES (Urutan dibalik agar aman dari foreign key constraint)
DROP TABLE IF EXISTS notifications CASCADE;
DROP TABLE IF EXISTS borrowings CASCADE;
DROP TABLE IF EXISTS books CASCADE;
DROP TABLE IF EXISTS categories CASCADE;
DROP TABLE IF EXISTS failed_jobs CASCADE;
DROP TABLE IF EXISTS job_batches CASCADE;
DROP TABLE IF EXISTS jobs CASCADE;
DROP TABLE IF EXISTS cache_locks CASCADE;
DROP TABLE IF EXISTS cache CASCADE;
DROP TABLE IF EXISTS sessions CASCADE;
DROP TABLE IF EXISTS password_reset_tokens CASCADE;
DROP TABLE IF EXISTS users CASCADE;

-- 2. EXTENSIONS
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pgcrypto";

-- 3. TRIGGER FUNCTION: Otomatis perbarui updated_at
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- ==============================================================================
-- TABEL UTAMA APLIKASI
-- ==============================================================================

-- 4. TABEL USERS (Anggota & Administrator)
CREATE TABLE users (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP WITH TIME ZONE NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'user', -- 'admin' atau 'user'
    phone VARCHAR(50) NULL,
    address TEXT NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

CREATE TRIGGER set_timestamp_users
BEFORE UPDATE ON users
FOR EACH ROW
EXECUTE FUNCTION update_updated_at_column();

-- 5. TABEL PASSWORD RESET TOKENS
CREATE TABLE password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE NULL
);

-- 6. TABEL SESSIONS (Sesi Login Pengguna)
CREATE TABLE sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT NULL REFERENCES users(id) ON DELETE SET NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload TEXT NOT NULL,
    last_activity INTEGER NOT NULL
);

CREATE INDEX idx_sessions_user_id ON sessions(user_id);
CREATE INDEX idx_sessions_last_activity ON sessions(last_activity);

-- 7. TABEL CACHE & CACHE LOCKS (Laravel Cache System)
CREATE TABLE cache (
    key VARCHAR(255) PRIMARY KEY,
    value TEXT NOT NULL,
    expiration BIGINT NOT NULL
);
CREATE INDEX idx_cache_expiration ON cache(expiration);

CREATE TABLE cache_locks (
    key VARCHAR(255) PRIMARY KEY,
    owner VARCHAR(255) NOT NULL,
    expiration BIGINT NOT NULL
);
CREATE INDEX idx_cache_locks_expiration ON cache_locks(expiration);

-- 8. TABEL QUEUE JOBS (Laravel Background Jobs)
CREATE TABLE jobs (
    id BIGSERIAL PRIMARY KEY,
    queue VARCHAR(255) NOT NULL,
    payload TEXT NOT NULL,
    attempts SMALLINT NOT NULL,
    reserved_at INTEGER NULL,
    available_at INTEGER NOT NULL,
    created_at INTEGER NOT NULL
);
CREATE INDEX idx_jobs_queue ON jobs(queue);

CREATE TABLE job_batches (
    id VARCHAR(255) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    total_jobs INTEGER NOT NULL,
    pending_jobs INTEGER NOT NULL,
    failed_jobs INTEGER NOT NULL,
    failed_job_ids TEXT NOT NULL,
    options TEXT NULL,
    cancelled_at INTEGER NULL,
    created_at INTEGER NOT NULL,
    finished_at INTEGER NULL
);

CREATE TABLE failed_jobs (
    id BIGSERIAL PRIMARY KEY,
    uuid VARCHAR(255) NOT NULL UNIQUE,
    connection TEXT NOT NULL,
    queue TEXT NOT NULL,
    payload TEXT NOT NULL,
    exception TEXT NOT NULL,
    failed_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX idx_failed_jobs_queue ON failed_jobs(connection, queue, failed_at);

-- 9. TABEL CATEGORIES (Kategori Buku)
CREATE TABLE categories (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

CREATE TRIGGER set_timestamp_categories
BEFORE UPDATE ON categories
FOR EACH ROW
EXECUTE FUNCTION update_updated_at_column();

-- 10. TABEL BOOKS (Koleksi Buku Perpustakaan)
CREATE TABLE books (
    id BIGSERIAL PRIMARY KEY,
    category_id BIGINT NOT NULL REFERENCES categories(id) ON DELETE RESTRICT,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    isbn VARCHAR(30) NOT NULL UNIQUE,
    author VARCHAR(255) NOT NULL,
    publisher VARCHAR(255) NOT NULL,
    publication_year SMALLINT NOT NULL,
    stock INTEGER NOT NULL DEFAULT 1,
    available_stock INTEGER NOT NULL DEFAULT 1,
    shelf_location VARCHAR(50) NOT NULL DEFAULT 'Rak A-1',
    description TEXT NULL,
    cover_image VARCHAR(500) NULL,
    is_available BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_books_title_author ON books(title, author);
CREATE INDEX idx_books_is_available ON books(is_available);
CREATE INDEX idx_books_category_id ON books(category_id);

CREATE TRIGGER set_timestamp_books
BEFORE UPDATE ON books
FOR EACH ROW
EXECUTE FUNCTION update_updated_at_column();

-- 11. TABEL BORROWINGS (Sirkulasi & Transaksi Peminjaman)
CREATE TABLE borrowings (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    book_id BIGINT NOT NULL REFERENCES books(id) ON DELETE RESTRICT,
    borrowing_code VARCHAR(30) NOT NULL UNIQUE,
    borrow_date DATE NOT NULL,
    return_due_date DATE NOT NULL,
    actual_return_date DATE NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pending' CHECK (status IN ('pending', 'approved', 'rejected', 'borrowed', 'returned', 'overdue')),
    notes TEXT NULL,
    admin_note TEXT NULL,
    fines NUMERIC(10, 2) NOT NULL DEFAULT 0.00,
    approved_by BIGINT NULL REFERENCES users(id) ON DELETE SET NULL,
    returned_to BIGINT NULL REFERENCES users(id) ON DELETE SET NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_borrowings_user_status ON borrowings(user_id, status);
CREATE INDEX idx_borrowings_borrow_date ON borrowings(borrow_date);
CREATE INDEX idx_borrowings_return_due_date ON borrowings(return_due_date);

CREATE TRIGGER set_timestamp_borrowings
BEFORE UPDATE ON borrowings
FOR EACH ROW
EXECUTE FUNCTION update_updated_at_column();

-- 12. TABEL NOTIFICATIONS (Pemberitahuan Sistem)
CREATE TABLE notifications (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type VARCHAR(20) NOT NULL DEFAULT 'info', -- 'info', 'success', 'warning', 'danger'
    is_read BOOLEAN NOT NULL DEFAULT FALSE,
    link VARCHAR(255) NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_notifications_user_is_read ON notifications(user_id, is_read);

CREATE TRIGGER set_timestamp_notifications
BEFORE UPDATE ON notifications
FOR EACH ROW
EXECUTE FUNCTION update_updated_at_column();

-- ==============================================================================
-- ROW LEVEL SECURITY (RLS) POLICIES UNTUK SUPABASE
-- ==============================================================================

-- Aktifkan RLS pada seluruh tabel
ALTER TABLE users ENABLE ROW LEVEL SECURITY;
ALTER TABLE categories ENABLE ROW LEVEL SECURITY;
ALTER TABLE books ENABLE ROW LEVEL SECURITY;
ALTER TABLE borrowings ENABLE ROW LEVEL SECURITY;
ALTER TABLE notifications ENABLE ROW LEVEL SECURITY;

-- Buat Kebijakan Akses Penuh (Permissive) untuk koneksi aplikasi backend & service role
CREATE POLICY "Full access for all operations on users" ON users FOR ALL USING (true) WITH CHECK (true);
CREATE POLICY "Full access for all operations on categories" ON categories FOR ALL USING (true) WITH CHECK (true);
CREATE POLICY "Full access for all operations on books" ON books FOR ALL USING (true) WITH CHECK (true);
CREATE POLICY "Full access for all operations on borrowings" ON borrowings FOR ALL USING (true) WITH CHECK (true);
CREATE POLICY "Full access for all operations on notifications" ON notifications FOR ALL USING (true) WITH CHECK (true);

-- Selesai
COMMENT ON TABLE users IS 'Data pengguna dan kredensial login SIPUS Digital';
COMMENT ON TABLE categories IS 'Daftar kategori koleksi buku';
COMMENT ON TABLE books IS 'Data master buku dan ketersediaan stok';
COMMENT ON TABLE borrowings IS 'Pencatatan sirkulasi peminjaman dan denda';
COMMENT ON TABLE notifications IS 'Notifikasi status peminjaman untuk pengguna';

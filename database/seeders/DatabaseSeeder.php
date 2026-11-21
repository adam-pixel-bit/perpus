<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Category;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Admin Account
        $admin = User::firstOrCreate(
            ['email' => 'admin@perpustakaan.com'],
            [
                'name' => 'Administrator Perpustakaan',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '081234567890',
                'address' => 'Gedung Perpustakaan Pusat Lt. 2, Jakarta',
                'is_active' => true,
            ]
        );

        // 2. Create User Accounts
        $user1 = User::firstOrCreate(
            ['email' => 'budi@perpustakaan.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'phone' => '081298765432',
                'address' => 'Jl. Merdeka No. 45, Jakarta Barat',
                'is_active' => true,
            ]
        );

        $user2 = User::firstOrCreate(
            ['email' => 'siti@perpustakaan.com'],
            [
                'name' => 'Siti Rahma',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'phone' => '081345678901',
                'address' => 'Jl. Mawar No. 12, Bandung',
                'is_active' => true,
            ]
        );

        $user3 = User::firstOrCreate(
            ['email' => 'andi@perpustakaan.com'],
            [
                'name' => 'Andi Wijaya',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'phone' => '081567890123',
                'address' => 'Jl. Diponegoro No. 8, Surabaya',
                'is_active' => true,
            ]
        );

        // 3. Create Categories
        $categoriesData = [
            [
                'name' => 'Teknologi & Komputer',
                'description' => 'Buku-buku mengenai pemrograman, kecerdasan buatan, jaringan, dan arsitektur sistem.',
            ],
            [
                'name' => 'Sains & Matematika',
                'description' => 'Literatur ilmiah, fisika, kimia, biologi, dan matematika murni maupun terapan.',
            ],
            [
                'name' => 'Sastra & Fiksi',
                'description' => 'Novel, cerpen, puisi, roman, dan karya sastra klasik maupun modern.',
            ],
            [
                'name' => 'Sejarah & Budaya',
                'description' => 'Kajian sejarah peradaban dunia, antropologi, arkeologi, dan warisan budaya Nusantara.',
            ],
            [
                'name' => 'Bisnis & Manajemen',
                'description' => 'Strategi bisnis, akuntansi, kewirausahaan, keuangan, dan kepemimpinan korporat.',
            ],
            [
                'name' => 'Pengembangan Diri',
                'description' => 'Motivasi, psikologi praktis, produktivitas kerja, dan pembentukan kebiasaan positif.',
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['name']] = Category::firstOrCreate(
                ['name' => $cat['name']],
                [
                    'slug' => Str::slug($cat['name']),
                    'description' => $cat['description'],
                ]
            );
        }

        // 4. Create Books
        $booksData = [
            [
                'category' => 'Teknologi & Komputer',
                'title' => 'Clean Code: A Handbook of Agile Software Craftsmanship',
                'isbn' => '978-0132350884',
                'author' => 'Robert C. Martin',
                'publisher' => 'Prentice Hall',
                'publication_year' => 2008,
                'stock' => 5,
                'available_stock' => 3,
                'shelf_location' => 'Rak TK-01',
                'description' => 'Panduan fundamental mengenai penulisan kode yang bersih, mudah dibaca, dan mudah dirawat dalam pengembangan perangkat lunak modern.',
                'cover_image' => 'https://images.unsplash.com/photo-1532012164546-f432f2e3777a?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'category' => 'Teknologi & Komputer',
                'title' => 'Designing Data-Intensive Applications',
                'isbn' => '978-1449373320',
                'author' => 'Martin Kleppmann',
                'publisher' => "O'Reilly Media",
                'publication_year' => 2017,
                'stock' => 4,
                'available_stock' => 4,
                'shelf_location' => 'Rak TK-02',
                'description' => 'Panduan komprehensif tentang prinsip dasar dan arsitektur sistem terdistribusi, replikasi data, partisi, konsistensi, dan pemrosesan stream.',
                'cover_image' => 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'category' => 'Pengembangan Diri',
                'title' => 'Atomic Habits: Perubahan Kecil yang Memberikan Hasil Luar Biasa',
                'isbn' => '978-6020633176',
                'author' => 'James Clear',
                'publisher' => 'Gramedia Pustaka Utama',
                'publication_year' => 2019,
                'stock' => 6,
                'available_stock' => 4,
                'shelf_location' => 'Rak PD-01',
                'description' => 'Buku praktis tentang bagaimana kebiasaan kecil sehari-hari dapat terakumulasi menjadi pencapaian luar biasa dalam jangka panjang.',
                'cover_image' => 'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'category' => 'Sains & Matematika',
                'title' => 'A Brief History of Time: Dari Dentuman Besar hingga Lubang Hitam',
                'isbn' => '978-9792228809',
                'author' => 'Stephen Hawking',
                'publisher' => 'Bantam Books',
                'publication_year' => 1988,
                'stock' => 3,
                'available_stock' => 2,
                'shelf_location' => 'Rak SM-03',
                'description' => 'Penjelasan menakjubkan tentang asal mula alam semesta, lubang hitam, ruang-waktu, dan teori relativitas untuk pembaca umum.',
                'cover_image' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'category' => 'Sastra & Fiksi',
                'title' => 'Bumi Manusia (Tetralogi Buru Buku 1)',
                'isbn' => '978-9799731234',
                'author' => 'Pramoedya Ananta Toer',
                'publisher' => 'Lentera Dipantara',
                'publication_year' => 1980,
                'stock' => 5,
                'available_stock' => 5,
                'shelf_location' => 'Rak SF-01',
                'description' => 'Karya masterpiece sastra Indonesia yang mengisahkan perjuangan Minke dan Annelies di masa kolonial Hindia Belanda awal abad ke-20.',
                'cover_image' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'category' => 'Bisnis & Manajemen',
                'title' => 'The Lean Startup: How Constant Innovation Creates Radically Successful Businesses',
                'isbn' => '978-0307887894',
                'author' => 'Eric Ries',
                'publisher' => 'Crown Business',
                'publication_year' => 2011,
                'stock' => 4,
                'available_stock' => 3,
                'shelf_location' => 'Rak BM-02',
                'description' => 'Metodologi inovasi bisnis cepat melalui siklus Build-Measure-Learn untuk meminimalkan risiko kegagalan startup.',
                'cover_image' => 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'category' => 'Sejarah & Budaya',
                'title' => 'Nusantara: Sejarah Indonesia',
                'isbn' => '978-6024240578',
                'author' => 'Bernard H.M. Vlekke',
                'publisher' => 'Komunitas Bambu',
                'publication_year' => 2008,
                'stock' => 2,
                'available_stock' => 2,
                'shelf_location' => 'Rak SB-01',
                'description' => 'Kajian sejarah komprehensif kepulauan Nusantara sejak zaman prasejarah hingga terbentuknya negara-bangsa Indonesia modern.',
                'cover_image' => 'https://images.unsplash.com/photo-1461360370896-922624d12aa1?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'category' => 'Pengembangan Diri',
                'title' => 'Filosofi Teras: Hindari Stres dengan Stoikisme',
                'isbn' => '978-6024125189',
                'author' => 'Henry Manampiring',
                'publisher' => 'Buku Kompas',
                'publication_year' => 2018,
                'stock' => 4,
                'available_stock' => 4,
                'shelf_location' => 'Rak PD-02',
                'description' => 'Penerapan filosofi Stoa kuno Yunani-Romawi dalam mengelola emosi negatif dan menjalani kehidupan modern yang tenang.',
                'cover_image' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=600&q=80',
            ],
        ];

        $createdBooks = [];
        foreach ($booksData as $b) {
            $cat = $categories[$b['category']];
            $createdBooks[] = Book::firstOrCreate(
                ['isbn' => $b['isbn']],
                [
                    'category_id' => $cat->id,
                    'title' => $b['title'],
                    'slug' => Str::slug($b['title']),
                    'author' => $b['author'],
                    'publisher' => $b['publisher'],
                    'publication_year' => $b['publication_year'],
                    'stock' => $b['stock'],
                    'available_stock' => $b['available_stock'],
                    'shelf_location' => $b['shelf_location'],
                    'description' => $b['description'],
                    'cover_image' => $b['cover_image'],
                    'is_available' => true,
                ]
            );
        }

        // 5. Create Borrowings Sample
        // A. Borrowed (active)
        Borrowing::firstOrCreate(
            ['borrowing_code' => 'BRW-202611-0001'],
            [
                'user_id' => $user1->id,
                'book_id' => $createdBooks[0]->id, // Clean Code
                'borrow_date' => Carbon::now()->subDays(3)->toDateString(),
                'return_due_date' => Carbon::now()->addDays(4)->toDateString(),
                'status' => 'borrowed',
                'notes' => 'Untuk bahan referensi skripsi rekayasa perangkat lunak.',
                'approved_by' => $admin->id,
            ]
        );

        // B. Pending approval
        Borrowing::firstOrCreate(
            ['borrowing_code' => 'BRW-202611-0002'],
            [
                'user_id' => $user2->id,
                'book_id' => $createdBooks[2]->id, // Atomic Habits
                'borrow_date' => Carbon::now()->toDateString(),
                'return_due_date' => Carbon::now()->addDays(7)->toDateString(),
                'status' => 'pending',
                'notes' => 'Mohon persetujuannya untuk tugas pembinaan karakter.',
            ]
        );

        // C. Overdue
        Borrowing::firstOrCreate(
            ['borrowing_code' => 'BRW-202611-0003'],
            [
                'user_id' => $user3->id,
                'book_id' => $createdBooks[3]->id, // A Brief History of Time
                'borrow_date' => Carbon::now()->subDays(12)->toDateString(),
                'return_due_date' => Carbon::now()->subDays(2)->toDateString(),
                'status' => 'borrowed', // effectively overdue
                'notes' => 'Kajian astronomi dan kosmologi.',
                'approved_by' => $admin->id,
            ]
        );

        // D. Returned
        Borrowing::firstOrCreate(
            ['borrowing_code' => 'BRW-202611-0004'],
            [
                'user_id' => $user1->id,
                'book_id' => $createdBooks[5]->id, // The Lean Startup
                'borrow_date' => Carbon::now()->subDays(14)->toDateString(),
                'return_due_date' => Carbon::now()->subDays(7)->toDateString(),
                'actual_return_date' => Carbon::now()->subDays(8)->toDateString(),
                'status' => 'returned',
                'notes' => 'Buku sangat inspiratif dan bermanfaat.',
                'admin_note' => 'Buku dikembalikan dalam kondisi sangat baik.',
                'approved_by' => $admin->id,
                'returned_to' => $admin->id,
            ]
        );

        // 6. Create Notifications
        Notification::firstOrCreate(
            ['title' => 'Peminjaman Disetujui'],
            [
                'user_id' => $user1->id,
                'message' => 'Peminjaman buku "Clean Code" telah disetujui. Silakan ambil buku di perpustakaan.',
                'type' => 'success',
                'is_read' => true,
                'link' => '/user/borrowings',
            ]
        );

        Notification::firstOrCreate(
            ['title' => 'Pengingat Pengembalian Buku'],
            [
                'user_id' => $user3->id,
                'message' => 'Peminjaman buku "A Brief History of Time" telah melewati batas waktu pengembalian. Segera kembalikan ke perpustakaan.',
                'type' => 'warning',
                'is_read' => false,
                'link' => '/user/borrowings',
            ]
        );
    }
}

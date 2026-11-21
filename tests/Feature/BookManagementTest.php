<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->category = Category::create([
            'name' => 'Teknologi',
            'slug' => 'teknologi',
            'description' => 'Buku seputar IT',
        ]);
    }

    public function test_admin_can_view_book_index(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.books.index'));

        $response->assertStatus(200);
        $response->assertSee('Manajemen Buku');
    }

    public function test_admin_can_create_a_book(): void
    {
        $bookData = [
            'category_id' => $this->category->id,
            'title' => 'Buku Pemrograman Laravel',
            'isbn' => '978-6020000001',
            'author' => 'Taylor Otwell',
            'publisher' => 'Laravel LLC',
            'publication_year' => 2024,
            'stock' => 5,
            'shelf_location' => 'Rak A-1',
            'description' => 'Buku panduan framework Laravel terkini.',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.books.store'), $bookData);

        $response->assertRedirect(route('admin.books.index'));
        $this->assertDatabaseHas('books', [
            'title' => 'Buku Pemrograman Laravel',
            'isbn' => '978-6020000001',
            'stock' => 5,
            'available_stock' => 5,
        ]);
    }

    public function test_admin_can_update_a_book(): void
    {
        $book = Book::create([
            'category_id' => $this->category->id,
            'title' => 'Judul Awal',
            'slug' => 'judul-awal',
            'isbn' => '978-1111111111',
            'author' => 'Penulis Lama',
            'publisher' => 'Penerbit Lama',
            'publication_year' => 2020,
            'stock' => 3,
            'available_stock' => 3,
            'shelf_location' => 'Rak B-2',
            'is_available' => true,
        ]);

        $updateData = [
            'category_id' => $this->category->id,
            'title' => 'Judul Diperbarui',
            'isbn' => '978-1111111111',
            'author' => 'Penulis Baru',
            'publisher' => 'Penerbit Lama',
            'publication_year' => 2021,
            'stock' => 6,
            'shelf_location' => 'Rak C-3',
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.books.update', $book->id), $updateData);

        $response->assertRedirect(route('admin.books.index'));
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'Judul Diperbarui',
            'stock' => 6,
            'available_stock' => 6,
        ]);
    }

    public function test_admin_can_delete_a_book(): void
    {
        $book = Book::create([
            'category_id' => $this->category->id,
            'title' => 'Buku yang akan Dihapus',
            'slug' => 'buku-dihapus',
            'isbn' => '978-2222222222',
            'author' => 'Penulis X',
            'publisher' => 'Penerbit Y',
            'publication_year' => 2019,
            'stock' => 1,
            'available_stock' => 1,
            'shelf_location' => 'Rak D-1',
            'is_available' => true,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.books.destroy', $book->id));

        $response->assertRedirect(route('admin.books.index'));
        $this->assertDatabaseMissing('books', [
            'id' => $book->id,
        ]);
    }
}

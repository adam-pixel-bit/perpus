<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BorrowingFlowTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $user;

    private Book $book;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->user = User::factory()->create([
            'role' => 'user',
            'is_active' => true,
        ]);

        $category = Category::create([
            'name' => 'Sains',
            'slug' => 'sains',
        ]);

        $this->book = Book::create([
            'category_id' => $category->id,
            'title' => 'Buku Fisika Kuantum',
            'slug' => 'fisika-kuantum',
            'isbn' => '978-3333333333',
            'author' => 'Dr. Richard',
            'publisher' => 'Science Press',
            'publication_year' => 2022,
            'stock' => 2,
            'available_stock' => 2,
            'shelf_location' => 'Rak S-01',
            'is_available' => true,
        ]);
    }

    public function test_user_can_submit_borrowing_request(): void
    {
        $response = $this->actingAs($this->user)->post(route('user.borrowings.store'), [
            'book_id' => $this->book->id,
            'duration_days' => 7,
            'notes' => 'Untuk penelitian ilmiah.',
        ]);

        $response->assertRedirect(route('user.borrowings.index'));
        $this->assertDatabaseHas('borrowings', [
            'user_id' => $this->user->id,
            'book_id' => $this->book->id,
            'status' => 'pending',
        ]);
    }

    public function test_admin_can_approve_borrowing_and_decrement_stock(): void
    {
        $borrowing = Borrowing::create([
            'user_id' => $this->user->id,
            'book_id' => $this->book->id,
            'borrowing_code' => 'BRW-TEST-001',
            'borrow_date' => now()->toDateString(),
            'return_due_date' => now()->addDays(7)->toDateString(),
            'status' => 'pending',
        ]);

        $this->assertEquals(2, $this->book->fresh()->available_stock);

        $response = $this->actingAs($this->admin)->patch(route('admin.borrowings.approve', $borrowing->id));

        $response->assertSessionHas('success');
        $this->assertEquals('borrowed', $borrowing->fresh()->status);
        // Stock must be decremented by 1
        $this->assertEquals(1, $this->book->fresh()->available_stock);
    }

    public function test_admin_can_process_return_and_increment_stock(): void
    {
        // Set book stock to 1 because 1 is borrowed
        $this->book->update(['available_stock' => 1]);

        $borrowing = Borrowing::create([
            'user_id' => $this->user->id,
            'book_id' => $this->book->id,
            'borrowing_code' => 'BRW-TEST-002',
            'borrow_date' => now()->subDays(5)->toDateString(),
            'return_due_date' => now()->addDays(2)->toDateString(),
            'status' => 'borrowed',
            'approved_by' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->patch(route('admin.borrowings.return', $borrowing->id), [
            'admin_note' => 'Buku kembali mulus.',
            'fines' => 0,
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals('returned', $borrowing->fresh()->status);
        // Stock must be restored to 2
        $this->assertEquals(2, $this->book->fresh()->available_stock);
    }
}

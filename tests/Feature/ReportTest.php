<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_access_report_index(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.reports.index'));

        $response->assertStatus(200);
        $response->assertSee('Laporan');
    }

    public function test_admin_can_export_csv_without_errors(): void
    {
        $user = User::factory()->create(['role' => 'user', 'is_active' => true]);
        $category = Category::create([
            'name' => 'Teknologi',
            'slug' => 'teknologi',
            'description' => 'Buku seputar IT',
        ]);

        $book = Book::create([
            'category_id' => $category->id,
            'title' => 'Buku Pemrograman Web',
            'slug' => 'buku-pemrograman-web',
            'isbn' => '978-6020000002',
            'author' => 'Author Test',
            'publisher' => 'Publisher Test',
            'publication_year' => 2023,
            'stock' => 5,
            'available_stock' => 4,
            'is_available' => true,
        ]);

        Borrowing::create([
            'borrowing_code' => 'BRW-TEST-001',
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => 'borrowed',
            'borrow_date' => Carbon::now()->startOfMonth(),
            'return_due_date' => Carbon::now()->addDays(7),
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.reports.export', [
            'start_date' => Carbon::now()->startOfMonth()->toDateString(),
            'end_date' => Carbon::now()->toDateString(),
        ]));

        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('content-type'));
    }
}

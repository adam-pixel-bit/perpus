<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Book;
use App\Models\BookRequest;
use App\Models\Borrowing;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdvancedFeaturesTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $user;

    private Category $category;

    private Book $book;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->user = User::create([
            'name' => 'Member Test',
            'email' => 'member@test.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'is_active' => true,
        ]);

        $this->category = Category::create([
            'name' => 'Teknologi',
            'slug' => 'teknologi',
        ]);

        $this->book = Book::create([
            'category_id' => $this->category->id,
            'title' => 'Laravel Advanced',
            'slug' => 'laravel-advanced',
            'isbn' => '978-1234567890',
            'author' => 'Taylor Otwell',
            'publisher' => 'Laravel LLC',
            'publication_year' => 2024,
            'stock' => 5,
            'available_stock' => 5,
            'shelf_location' => 'Rak A-1',
            'is_available' => true,
        ]);
    }

    public function test_login_rate_limiting_triggers_after_repeated_failures(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email' => 'member@test.com',
                'password' => 'wrongpassword',
            ]);
        }

        // 6th attempt must be throttled
        $response = $this->post('/login', [
            'email' => 'member@test.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertTrue(AuditLog::where('action', 'login_throttled')->exists());
    }

    public function test_user_can_request_and_reset_password_via_token(): void
    {
        // 1. Request reset link
        $reqResponse = $this->post('/forgot-password', [
            'email' => 'member@test.com',
        ]);

        $reqResponse->assertSessionHas('status');
        $demoLink = session('demo_reset_link');
        $this->assertNotNull($demoLink);

        // Extract token from demo link
        parse_str(parse_url($demoLink, PHP_URL_QUERY), $queryParams);
        $token = basename(parse_url($demoLink, PHP_URL_PATH));

        // 2. Submit new password
        $resetResponse = $this->post('/reset-password', [
            'token' => $token,
            'email' => 'member@test.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $resetResponse->assertRedirect('/login');
        $resetResponse->assertSessionHas('success');

        // Verify user can now log in with the new password
        $this->user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $this->user->password));
    }

    public function test_member_can_renew_active_borrowing_once(): void
    {
        $borrowing = Borrowing::create([
            'user_id' => $this->user->id,
            'book_id' => $this->book->id,
            'borrowing_code' => 'BRW-RENEW-001',
            'borrow_date' => Carbon::today(),
            'return_due_date' => Carbon::today()->addDays(7),
            'status' => 'borrowed',
            'renewed_count' => 0,
        ]);

        // Renew +7 days
        $response = $this->actingAs($this->user)->post("/user/borrowings/{$borrowing->id}/renew");
        $response->assertSessionHas('success');

        $borrowing->refresh();
        $this->assertEquals(1, $borrowing->renewed_count);
        $this->assertEquals(Carbon::today()->addDays(14)->toDateString(), $borrowing->return_due_date->toDateString());

        // Attempting to renew a second time must fail
        $secondResponse = $this->actingAs($this->user)->post("/user/borrowings/{$borrowing->id}/renew");
        $secondResponse->assertSessionHas('error');
    }

    public function test_member_can_submit_book_review(): void
    {
        Borrowing::create([
            'user_id' => $this->user->id,
            'book_id' => $this->book->id,
            'borrowing_code' => 'BRW-REV-001',
            'borrow_date' => Carbon::today()->subDays(10),
            'return_due_date' => Carbon::today()->subDays(3),
            'actual_return_date' => Carbon::today()->subDays(2),
            'status' => 'returned',
        ]);

        $response = $this->actingAs($this->user)->post("/user/catalog/{$this->book->id}/reviews", [
            'rating' => 5,
            'comment' => 'Buku yang sangat luar biasa dan sangat bermanfaat!',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('reviews', [
            'user_id' => $this->user->id,
            'book_id' => $this->book->id,
            'rating' => 5,
        ]);

        $this->book->refresh();
        $this->assertEquals(5.0, $this->book->average_rating);
    }

    public function test_calculate_fines_artisan_command_updates_overdue_fines(): void
    {
        // Create an overdue borrowing (5 days overdue)
        $borrowing = Borrowing::create([
            'user_id' => $this->user->id,
            'book_id' => $this->book->id,
            'borrowing_code' => 'BRW-FINE-001',
            'borrow_date' => Carbon::today()->subDays(12),
            'return_due_date' => Carbon::today()->subDays(5),
            'status' => 'borrowed',
            'fines' => 0,
        ]);

        Artisan::call('perpus:calculate-fines');

        $borrowing->refresh();
        // 5 days * Rp 1.000 = Rp 5.000
        $this->assertEquals(5000, $borrowing->fines);
        $this->assertTrue(AuditLog::where('action', 'cron_calculate_fines')->exists());
    }

    public function test_admin_can_access_audit_logs_and_scanner_pages(): void
    {
        $this->actingAs($this->admin)->get('/admin/audit-logs')->assertStatus(200);
        $this->actingAs($this->admin)->get('/admin/scanner')->assertStatus(200);
        $this->actingAs($this->admin)->get('/admin/scanner/lookup?code=BRW-FINE-001')->assertStatus(200);
    }

    public function test_user_can_update_profile_and_password(): void
    {
        $this->actingAs($this->user)->get('/profile')->assertStatus(200);

        // Update profile
        $response = $this->actingAs($this->user)->put('/profile', [
            'name' => 'Member Updated Name',
            'email' => 'member@test.com',
            'phone' => '081299998888',
            'address' => 'Jl. Baru No. 10',
        ]);
        $response->assertSessionHas('success');
        $this->user->refresh();
        $this->assertEquals('Member Updated Name', $this->user->name);

        // Change password
        $pwdResponse = $this->actingAs($this->user)->put('/profile/password', [
            'current_password' => 'password123',
            'password' => 'brandnewpass123',
            'password_confirmation' => 'brandnewpass123',
        ]);
        $pwdResponse->assertSessionHas('success');
        $this->user->refresh();
        $this->assertTrue(Hash::check('brandnewpass123', $this->user->password));
    }

    public function test_user_can_view_member_card_and_circulation_receipt(): void
    {
        $borrowing = Borrowing::create([
            'user_id' => $this->user->id,
            'book_id' => $this->book->id,
            'borrowing_code' => 'BRW-CARD-001',
            'borrow_date' => Carbon::today(),
            'return_due_date' => Carbon::today()->addDays(7),
            'status' => 'borrowed',
        ]);

        $this->actingAs($this->user)->get('/profile/card')->assertStatus(200);
        $this->actingAs($this->user)->get("/borrowings/{$borrowing->id}/receipt")->assertStatus(200);
    }

    public function test_admin_can_receive_fine_payment(): void
    {
        $borrowing = Borrowing::create([
            'user_id' => $this->user->id,
            'book_id' => $this->book->id,
            'borrowing_code' => 'BRW-FINE-PAY',
            'borrow_date' => Carbon::today()->subDays(10),
            'return_due_date' => Carbon::today()->subDays(3),
            'status' => 'returned',
            'fines' => 6000,
            'fine_payment_status' => 'unpaid',
        ]);

        $response = $this->actingAs($this->admin)->post("/admin/borrowings/{$borrowing->id}/pay-fine");
        $response->assertSessionHas('success');

        $borrowing->refresh();
        $this->assertEquals('paid', $borrowing->fine_payment_status);
        $this->assertNotNull($borrowing->fine_paid_at);
        $this->assertEquals($this->admin->id, $borrowing->fine_received_by);
    }

    public function test_book_request_lifecycle(): void
    {
        // 1. Member submits book request
        $response = $this->actingAs($this->user)->post('/user/book-requests', [
            'title' => 'Clean Architecture',
            'author' => 'Robert C. Martin',
            'publisher' => 'Prentice Hall',
            'publication_year' => 2017,
            'reason' => 'Sangat penting untuk pembelajaran arsitektur sistem.',
        ]);
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('book_requests', [
            'user_id' => $this->user->id,
            'title' => 'Clean Architecture',
            'status' => 'pending',
        ]);

        $req = BookRequest::where('title', 'Clean Architecture')->first();

        // 2. Admin reviews & approves
        $adminResp = $this->actingAs($this->admin)->patch("/admin/book-requests/{$req->id}/status", [
            'status' => 'approved',
            'admin_note' => 'Akan dipesan bulan ini.',
        ]);
        $adminResp->assertSessionHas('success');
        $req->refresh();
        $this->assertEquals('approved', $req->status);
    }

    public function test_admin_can_access_official_printable_report(): void
    {
        $this->actingAs($this->admin)->get('/admin/reports/print')->assertStatus(200);
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Selamat Datang Kembali');
    }

    public function test_admin_can_authenticate_and_redirect_to_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@test.com',
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($admin);
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_user_can_authenticate_and_redirect_to_user_dashboard(): void
    {
        $user = User::factory()->create([
            'email' => 'user@test.com',
            'role' => 'user',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'user@test.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('user.dashboard'));
    }

    public function test_user_cannot_access_admin_routes(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class SocialAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_unsupported_provider_redirects_back_with_error(): void
    {
        $response = $this->get(route('auth.social.redirect', 'github'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', 'Penyedia layanan login tidak didukung.');
    }

    public function test_unconfigured_provider_redirects_back_with_error(): void
    {
        config(['services.google.client_id' => null]);
        config(['services.google.client_secret' => null]);

        $response = $this->get(route('auth.social.redirect', 'google'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error');
    }

    public function test_callback_creates_new_user_and_authenticates(): void
    {
        config(['services.google.client_id' => 'fake-client-id']);
        config(['services.google.client_secret' => 'fake-client-secret']);

        $abstractUser = Mockery::mock(SocialiteUser::class);
        $abstractUser->shouldReceive('getId')->andReturn('google-12345');
        $abstractUser->shouldReceive('getEmail')->andReturn('john.doe@example.com');
        $abstractUser->shouldReceive('getName')->andReturn('John Doe');
        $abstractUser->shouldReceive('getNickname')->andReturn('johndoe');
        $abstractUser->shouldReceive('getAvatar')->andReturn('https://example.com/avatar.jpg');

        $providerMock = Mockery::mock('Laravel\Socialite\Two\GoogleProvider');
        $providerMock->shouldReceive('user')->andReturn($abstractUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($providerMock);

        $response = $this->get(route('auth.social.callback', 'google'));

        $response->assertRedirect(route('user.dashboard'));
        $this->assertAuthenticated();

        $user = User::where('email', 'john.doe@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('google-12345', $user->google_id);
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('user', $user->role);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'social_login',
        ]);
    }

    public function test_callback_links_existing_user_by_email(): void
    {
        config(['services.facebook.client_id' => 'fake-fb-id']);
        config(['services.facebook.client_secret' => 'fake-fb-secret']);

        $existingUser = User::factory()->create([
            'email' => 'jane.doe@example.com',
            'name' => 'Jane Doe',
            'facebook_id' => null,
            'is_active' => true,
        ]);

        $abstractUser = Mockery::mock(SocialiteUser::class);
        $abstractUser->shouldReceive('getId')->andReturn('fb-998877');
        $abstractUser->shouldReceive('getEmail')->andReturn('jane.doe@example.com');
        $abstractUser->shouldReceive('getName')->andReturn('Jane Doe FB');
        $abstractUser->shouldReceive('getNickname')->andReturn(null);
        $abstractUser->shouldReceive('getAvatar')->andReturn('https://example.com/fb-avatar.jpg');

        $providerMock = Mockery::mock('Laravel\Socialite\Two\FacebookProvider');
        $providerMock->shouldReceive('user')->andReturn($abstractUser);

        Socialite::shouldReceive('driver')->with('facebook')->andReturn($providerMock);

        $response = $this->get(route('auth.social.callback', 'facebook'));

        $response->assertRedirect(route('user.dashboard'));
        $this->assertAuthenticatedAs($existingUser);

        $existingUser->refresh();
        $this->assertEquals('fb-998877', $existingUser->facebook_id);
    }

    public function test_callback_denies_deactivated_user(): void
    {
        config(['services.google.client_id' => 'fake-client-id']);
        config(['services.google.client_secret' => 'fake-client-secret']);

        User::factory()->create([
            'email' => 'blocked@example.com',
            'google_id' => 'google-blocked-id',
            'is_active' => false,
        ]);

        $abstractUser = Mockery::mock(SocialiteUser::class);
        $abstractUser->shouldReceive('getId')->andReturn('google-blocked-id');
        $abstractUser->shouldReceive('getEmail')->andReturn('blocked@example.com');
        $abstractUser->shouldReceive('getName')->andReturn('Blocked User');
        $abstractUser->shouldReceive('getNickname')->andReturn(null);
        $abstractUser->shouldReceive('getAvatar')->andReturn(null);

        $providerMock = Mockery::mock('Laravel\Socialite\Two\GoogleProvider');
        $providerMock->shouldReceive('user')->andReturn($abstractUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($providerMock);

        $response = $this->get(route('auth.social.callback', 'google'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', 'Akun Anda telah dinonaktifkan oleh Administrator.');
        $this->assertGuest();
    }
}

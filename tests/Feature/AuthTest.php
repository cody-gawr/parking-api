<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_register()
    {
        $payload = [
            'name'                  => 'Test User',
            'email'                 => 'test@example.com',
            'password'              => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->postJson('/api/auth/register', $payload);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'user' => ['id', 'name', 'email', 'created_at', 'updated_at'],
                     'access_token',
                     'token_type'
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
    }

    #[Test]
    public function login_returns_token_for_valid_credentials()
    {
        User::factory()->create([
            'email'    => 'login@example.com',
            'password' => bcrypt('secret123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email'    => 'login@example.com',
            'password' => 'secret123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                    'user' => ['id','name', 'email', 'email_verified_at', 'created_at', 'updated_at'],
                    'access_token',
                    'token_type'
                ]);
    }

    #[Test]
    public function login_fails_with_invalid_credentials()
    {
        User::factory()->create([
            'email'    => 'fail@example.com',
            'password' => bcrypt('rightpass'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email'    => 'fail@example.com',
            'password' => 'wrongpass',
        ]);

        $response->assertStatus(401)
                 ->assertExactJson(['message' => 'Invalid credentials']);
    }

    #[Test]
    public function authenticated_user_can_get_their_profile_and_logout()
    {
        // 1) Create a user and issue a Sanctum token
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        // 2) Use that token to hit /api/user
        $this->withHeader('Authorization', "Bearer {$token}")
             ->getJson('/api/user')
             ->assertStatus(200)
             ->assertJsonFragment(['email' => $user->email]);

        // 3) Logout (correct endpoint) and expect 200
        $this->withHeader('Authorization', "Bearer {$token}")
             ->postJson('/api/auth/logout')
             ->assertStatus(200)
             ->assertExactJson([
                'success' => true,
                'message' => 'Logged out successfully.'
            ]);
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register(): void
    {
        $userData = [
            'login' => 'Masmissien',
            'password' => 'password123',
            'email' => 'masmis@example.com',
            'last_name' => 'Lemasmis',
            'first_name' => 'Masmis',
        ];

        $response = $this->postJson('/api/signup', $userData);

        $this->assertDatabaseHas('users', [
            'login' => 'Masmissien'
        ]);
    }

    public function test_register_fails_if_missing_fields()
    {
        $response = $this->postJson('/api/signup', [
            'login' => '',
            'password' => '',
            'email' => '',
            'last_name' => '',
            'first_name' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'login',
            'password',
            'email',
            'last_name',
            'first_name'
        ]);
    }

    public function test_register_fails_if_login_already_taken()
    {
        User::factory()->create(['login' => 'Masmissien']);

        $response = $this->postJson('/api/signup', [
            'login' => 'Masmissien',
            'password' => 'securepassword',
            'email' => 'masmis@example.com',
            'last_name' => 'Lemasmis',
            'first_name' => 'Masmis',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['login']);
    }

    public function test_register_rate_limiting_exceeded(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $response = $this->postJson('/api/signup', [
                'login' => 'Masmissien' . $i,
                'password' => 'securepassword',
                'email' => "masmis{$i}@example.com",
                'last_name' => 'Lemasmis',
                'first_name' => 'Masmis',
            ]);

            $response->assertStatus(201);
        }

        $response = $this->postJson('/api/signup', [
            'login' => 'Masmissien6',
            'password' => 'securepassword',
            'email' => 'masmis6@example.com',
            'last_name' => 'Lemasmis',
            'first_name' => 'Masmis',
        ]);

        $response->assertStatus(429);
    }


    public function test_user_can_login_with_correct_credentials(): void
    {
        $user = User::factory()->create([
            'login' => 'Masmissien',
            'password' => bcrypt('securepassword'),
        ]);
    
        $response = $this->postJson('/api/signin', [
            'login' => 'Masmissien',
            'password' => 'securepassword',
        ]);
    
        $response->assertStatus(200)
                 ->assertJsonStructure(['message', 'token', 'user']);
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'login' => 'Masmissien',
            'password' => bcrypt('securepassword'),
        ]);
    
        $response = $this->postJson('/api/signin', [
            'login' => 'Masmissien',
            'password' => 'wrongpassword',
        ]);
    
        $response->assertStatus(401)
                 ->assertJson(['message' => 'Login ou mot de passe incorrect']);
    }

    public function test_login_rate_limiting_exceeded(): void
    {
        $user = User::factory()->create([
            'login' => 'Masmissien',
            'password' => bcrypt('securepassword'),
        ]);
    
        for ($i = 0; $i < 5; $i++) {
            $response = $this->postJson('/api/signin', [
                'login' => 'Masmissien',
                'password' => 'wrongpassword',
            ]);
            $response->assertStatus(401);
        }
    
        $response = $this->postJson('/api/signin', [
            'login' => 'Masmissien',
            'password' => 'wrongpassword',
        ]);
    
        $response->assertStatus(429);
    }

    public function test_logout_successfully_revokes_token(): void
    {
        $user = User::factory()->create();
    
        $this->actingAs($user, 'sanctum');
    
        $response = $this->postJson('/api/signout');
    
        $response->assertNoContent();
    
        $this->assertCount(0, $user->tokens);
    }
    
    public function test_logout_fails_if_not_authenticated(): void
    {
        $response = $this->postJson('/api/signout');
    
        $response->assertStatus(401);
    }

    public function test_logout_rate_limiting_exceeded(): void
    {
        $user = User::factory()->create();
    
        $this->actingAs($user, 'sanctum');
    
        for ($i = 0; $i < 5; $i++) {
            $response = $this->postJson('/api/signout');
            $response->assertNoContent();
        }
    
        $response = $this->postJson('/api/signout');
        $response->assertStatus(429);
    }
    
}

<?php

namespace Tests\Feature;

use App\Models\User;
use App\Rules\Recaptcha;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    public function testRegister()
    {
        // Test registration
        $this->post(
            '/api/auth/create',
            [
                'username' => 'username',
                'email' => 'Test@EXAMPLE.com',
                'password' => 'password123',
                'password_confirm' => 'password123',
                'recaptcha' => 'some-token',
            ]
        )->assertJsonStructure(['status', 'token', 'user']);

        $this->assertDatabaseHas('accounts', ['email' => 'test@example.com']);
    }

    public function testRegisterValidation()
    {
        // Test registration with invalid data
        $this->post(
            '/api/auth/create',
            [
                'email' => 'notOkayEmail',
                'password' => 'password123',
                'password_confirm' => 'password123',
            ]
        )->assertJson([
            'status' => 'validation',
            'errors' => [
                'email' => ['The email must be a valid email address.'],
                'username' => ['The username field is required.'],
            ],
        ]);
    }

    public function testLogin()
    {
        $user = factory(User::class)->create();

        // Try Valid login
        $this->post('/api/auth/login', ['email' => $user->email, 'password' => 'password'])
            ->assertJsonStructure(['status', 'token', 'user']);

        // Try Invalid Login
        $this->post('/api/auth/login', ['email' => 'test@example.com', 'password' => 'wrongPassword'])
            ->assertJson(['status' => 'authentication', 'errors' => ['credentials' => 'invalid credentials']]);

        // Try Missing Field
        $this->post('/api/auth/login', ['email' => 'test@example.com'])
            ->assertJson(['status' => 'validation', 'errors' => ['password' => []]]);
    }

    public function testGetUserDevices()
    {
        $user = factory(User::class)->create();

        // explicitly create a token since actingAs does not do this
        $resp = $this->post('/api/auth/login', ['email' => $user->email, 'password' => 'password']);
        $token = $resp['token'];
        $this->get('/api/auth/devices', [
            'Authorization' => "Bearer $token",
        ])->assertJsonStructure([
            'status',
            'tokens' => [
                '*' => [
                    'name', 'last_used_at',
                ],
            ],
        ]);
    }

    public function testRevokeall()
    {
        $user = factory(User::class)->create();

        // explicitly create tokens since actingAs does not do this
        $this->post('/api/auth/login', ['email' => $user->email, 'password' => 'password']);
        $this->post('/api/auth/login', ['email' => $user->email, 'password' => 'password']);
        $resp = $this->post('/api/auth/login', ['email' => $user->email, 'password' => 'password']);
        $token = $resp['token'];

        $existing_tokens = $user->tokens()->get()->pluck('id')->toArray();
        foreach ($existing_tokens as $existing_token) {
            $this->assertDatabaseHas('personal_access_tokens', ['id' => $existing_token]);
        }

        $this->post('/api/auth/revokeAll', [], [
            'Authorization' => "Bearer $token",
        ])->assertJsonStructure([
            'status',
            'tokens' => [
                '*' => [
                    'name', 'last_used_at',
                ],
            ],
        ]);

        $this->assertDeleted('personal_access_tokens', ['id' => $existing_tokens[0]]);
        $this->assertDeleted('personal_access_tokens', ['id' => $existing_tokens[1]]);
        $this->assertDatabaseHas('personal_access_tokens', ['id' => $existing_tokens[2]]);
    }

    public function testLogout()
    {
        $user = factory(User::class)->create();

        // explicitly create tokens since actingAs does not do this
        $resp = $this->post('/api/auth/login', ['email' => $user->email, 'password' => 'password']);
        $token = $resp['token'];

        $existing_tokens = $user->tokens()->get()->pluck('id')->toArray();
        $this->assertDatabaseHas('personal_access_tokens', ['id' => $existing_tokens[0]]);

        $this->post('/api/auth/logout', [], [
            'Authorization' => "Bearer $token",
        ])->assertJson(['status' => 'success']);

        $this->assertDeleted('personal_access_tokens', ['id' => $existing_tokens[0]]);
    }
}

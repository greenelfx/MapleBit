<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    public function testStore()
    {
        $user = factory(User::class)->create();
        Sanctum::actingAs($user, ['*']);
        $user->loggedin = 1;
        $user->save();

        $this->post('/api/user/disconnect')->assertJsonStructure(['status', 'message']);
        $this->assertEquals($user->loggedin, 0);
    }

    public function testPasswordChangeValidation()
    {
        $user = factory(User::class)->create();
        Sanctum::actingAs($user, ['*']);
        $rand_new_pass = Str::random(12);
        $this->post('/api/user/update')->assertJson([
            'status' => 'validation',
            'errors' => [
                'password' => ['The password field is required.'],
                'new_password' => ['The new password field is required.'],
                'new_verify_password' => ['The new verify password field is required.'],
            ],
        ]);
    }

    public function testPasswordChangeMismatch()
    {
        $user = factory(User::class)->create();
        Sanctum::actingAs($user, ['*']);
        $rand_new_pass = Str::random(12);
        $this->post('/api/user/update', [
            'password' => $user->password,
            'new_password' => $rand_new_pass,
            'new_verify_password' => 'some-mismatched-password',
        ])->assertJson([
            'status' => 'validation',
            'errors' => [
                'new_verify_password' => ['The new verify password and new password must match.'],
            ],
        ]);
    }

    public function testPasswordInvalidCurrentPassword()
    {
        $user = factory(User::class)->create();
        Sanctum::actingAs($user, ['*']);
        $rand_new_pass = Str::random(12);
        $this->post('/api/user/update', [
            'password' => 'some-random-password',
            'new_password' => $rand_new_pass,
            'new_verify_password' => $rand_new_pass,
        ])->assertJson([
            'status' => 'invalid_info',
        ]);
    }

    public function testPasswordChange()
    {
        $user = factory(User::class)->create();
        Sanctum::actingAs($user, ['*']);
        $rand_new_pass = Str::random(12);
        $this->post('/api/user/update', [
            'password' => 'password',
            'new_password' => $rand_new_pass,
            'new_verify_password' => $rand_new_pass,
        ])->assertJson([
            'status' => 'success',
        ]);

        // assert the site_password field has been updated to the hashed $rand_new_pass
        $this->assertTrue(Hash::check($rand_new_pass, $user->site_password));
    }

    public function testGetMe()
    {
        $user = factory(User::class)->create();
        Sanctum::actingAs($user, ['*']);
        $rand_new_pass = Str::random(12);
        $this->get('/api/user/me')->assertJsonStructure([
            'status',
            'user',
        ]);
    }
}

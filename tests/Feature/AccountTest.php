<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}

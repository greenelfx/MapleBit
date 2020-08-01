<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function testStore()
    {
        $user = factory(User::class)->create();
        Sanctum::actingAs($user, ['*']);
        $data = [
            'age' => 10,
            'country' => 'United States',
        ];
        $this->post(
            '/api/user/profile/store',
            $data
        )->assertJson([
            'status' => 'success',
            'profile' => [
                'age' => 10,
                'country' => 'United States',
            ]
        ]);
    }


    public function testStoreCountryValidation()
    {
        $user = factory(User::class)->create();
        Sanctum::actingAs($user, ['*']);
        $data = [
            'age' => 10,
            'country' => 'some-country',
        ];
        $this->post(
            '/api/user/profile/store',
            $data
        )->assertJson([
            'status' => 'validation',
            'errors' => [
                'country' => ['The country was not valid.'],
            ]
        ]);
    }
    
    public function testViewNotFoundProfile()
    {
        $user = factory(User::class)->create();
        Sanctum::actingAs($user, ['*']);        
        $this->get('/api/user/profile/view/someprofile')->assertStatus(404);
    }

    public function testViewProfile()
    {
        $user = factory(User::class)->create();
        $profile = factory(Profile::class)->create();
        Sanctum::actingAs($user, ['*']);        
        $this->get('/api/user/profile/view/' . $profile->name)->assertJson([
            'status' => 'success',
            'profile' => [
                'name' => $profile->name,
                'country' => $profile->country,
                'motto' => $profile->motto,
                'about' => $profile->about,
            ]
        ]);
    }    
}

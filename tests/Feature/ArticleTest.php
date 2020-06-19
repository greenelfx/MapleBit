<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Vinkla\Hashids\Facades\Hashids;

class ArticleTest extends TestCase
{
    use RefreshDatabase;
    public function setUp(): void
    {
        // TODO: extract this logic into an extensible authenticated TestCase class
        parent::setUp();
        $this->seed('RolesAndPermissionsSeeder');
        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->registerPermissions();
    }

    public function testStore()
    {
        $user = factory(User::class)->create();
        $user->assignRole('admin');
        Sanctum::actingAs($user, ['*']);
        $data = [
            'title' => 'test article',
            'content' => 'some test content',
            'category' => 'some category',
        ];
        $this->post(
            '/api/articles/store',
            $data
        )->assertJsonStructure(['status', 'article']);
        $this->assertDatabaseHas('articles', $data);
    }

    public function testStoreValidation()
    {
        $user = factory(User::class)->create();
        $user->assignRole('admin');
        Sanctum::actingAs($user, ['*']);
        $this->post(
            '/api/articles/store',
            []
        )->assertJson([
            'status' => 'validation',
            'errors' => [
                'category' => ['The category field is required.'],
                'title' => ['The title field is required.'],
                'content' => ['The content field is required.']
            ]
        ]);
    }

    public function testStoreArticleWithoutPermissions()
    {
        $user = factory(User::class)->create();
        Sanctum::actingAs($user, ['*']);
        $slug = $this->post(
            '/api/articles/store',
            []
        )->assertStatus(403);
    }
    
    public function testViewArticle()
    {
        $user = factory(User::class)->create();
        $user->assignRole('admin');
        Sanctum::actingAs($user, ['*']);
        $data = [
            'title' => 'test article',
            'content' => 'some test content',
            'category' => 'some category',
        ];
        $slug = $this->post(
            '/api/articles/store',
            $data
        )->decodeResponseJson()['article']['slug'];
        $this->get('/api/articles/' . $slug)->assertJsonStructure([
            'title',
            'content',
            'category',
            'slug',
            'created_at',
            'updated_at',
        ]);
    }

    public function testViewNotFoundArticle()
    {
        $this->get('/api/articles/something')->assertStatus(404);
    }

    public function testUpdate()
    {
        $user = factory(User::class)->create();
        $user->assignRole('admin');
        Sanctum::actingAs($user, ['*']);
        $updateData = [
            'title' => 'updated article',
            'content' => 'some updated content',
            'category' => 'some updated category',
        ];
        $slug = $this->post(
            '/api/articles/store',
            [
                'title' => 'test article',
                'content' => 'some test content',
                'category' => 'some category',
            ]
        )->decodeResponseJson()['article']['slug'];
        $this->put(
            '/api/articles/update/' . $slug,
            $updateData
        );

        $this->assertDatabaseHas('articles', $updateData);
    }

    public function testUpdateArticleWithoutPermissions()
    {
        $admin = factory(User::class)->create();
        $user = factory(User::class)->create();
        $admin->assignRole('admin');
        Sanctum::actingAs($admin, ['*']);
        $slug = $this->post(
            '/api/articles/store',
            [
                'title' => 'test article',
                'content' => 'some test content',
                'category' => 'some category',
            ]
        )->decodeResponseJson()['article']['slug'];

        Sanctum::actingAs($user, ['*']);
        $this->put(
            '/api/articles/update/' . $slug,
            []
        )->assertStatus(403);
    }
    
    public function testUpdateValidation()
    {
        $user = factory(User::class)->create();
        $user->assignRole('admin');
        Sanctum::actingAs($user, ['*']);
        $slug = $this->post(
            '/api/articles/store',
            [
                'title' => 'test article',
                'content' => 'some test content',
                'category' => 'some category',
            ]
        )->decodeResponseJson()['article']['slug'];
        $this->put(
            '/api/articles/update/' . $slug,
            []
        )->assertJson([
            'status' => 'validation',
            'errors' => [
                'category' => ['The category field is required.'],
                'title' => ['The title field is required.'],
                'content' => ['The content field is required.']
            ]
        ]);
    }

    public function testDestroyArticle()
    {
        $user = factory(User::class)->create();
        $user->assignRole('admin');
        Sanctum::actingAs($user, ['*']);
        $slug = $this->post(
            '/api/articles/store',
            [
                'title' => 'test article',
                'content' => 'some test content',
                'category' => 'some category',
            ]
        )->decodeResponseJson()['article']['slug'];
        $this->delete(
            '/api/articles/' . $slug,
            []
        )->assertJson(['status' => 'success']);
    }

    public function testDestroyArticleWithoutPermissions()
    {
        $admin = factory(User::class)->create();
        $user = factory(User::class)->create();
        $admin->assignRole('admin');
        Sanctum::actingAs($admin, ['*']);
        $slug = $this->post(
            '/api/articles/store',
            [
                'title' => 'test article',
                'content' => 'some test content',
                'category' => 'some category',
            ]
        )->decodeResponseJson()['article']['slug'];

        Sanctum::actingAs($user, ['*']);
        $this->delete(
            '/api/articles/' . $slug,
            []
        )->assertStatus(403);
    }    
}

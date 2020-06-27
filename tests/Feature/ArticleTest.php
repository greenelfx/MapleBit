<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

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
            'category' => 'some-category',
        ];
        $this->post(
            '/api/articles/store',
            $data
        )->assertJsonStructure(['status', 'article']);
        $this->assertDatabaseHas('articles', $data);
    }

    public function testStoreWithExplicitSlug()
    {
        $user = factory(User::class)->create();
        $user->assignRole('admin');
        Sanctum::actingAs($user, ['*']);
        $data = [
            'title' => 'test article',
            'content' => 'some test content',
            'category' => 'some-category',
            'slug' => 'explicitly-defined-slug'
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
            [
                'category' => 'some category',
            ]
        )->assertJson([
            'status' => 'validation',
            'errors' => [
                'category' => ['The category may only contain letters, numbers, dashes and underscores.'],
                'title' => ['The title field is required.'],
                'content' => ['The content field is required.'],
            ],
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
        $slug = factory(Article::class)->create()['slug'];
        $this->get('/api/articles/view/'.$slug)->assertJsonStructure([
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
        $this->get('/api/articles/view/something')->assertStatus(404);
    }

    public function testUpdate()
    {
        $user = factory(User::class)->create();
        $user->assignRole('admin');
        Sanctum::actingAs($user, ['*']);
        $updateData = [
            'title' => 'updated article',
            'content' => 'some updated content',
            'category' => 'some-updated-category',
            'slug' => 'some-updated-slug',
            'locked' => 1,
        ];
        $slug = factory(Article::class)->create()['slug'];
        $this->put(
            '/api/articles/update/'.$slug,
            $updateData
        );

        $this->assertDatabaseHas('articles', $updateData);
    }

    public function testUpdateArticleWithoutPermissions()
    {
        $user = factory(User::class)->create();
        Sanctum::actingAs($user, ['*']);

        $slug = factory(Article::class)->create()['slug'];
        $this->put('/api/articles/update/'.$slug, [])->assertStatus(403);
    }

    public function testUpdateValidation()
    {
        $user = factory(User::class)->create();
        $user->assignRole('admin');
        Sanctum::actingAs($user, ['*']);
        $slug = factory(Article::class)->create()['slug'];
        $this->put(
            '/api/articles/update/'.$slug,
            [
                'category' => 'some category',
            ]
        )->assertJson([
            'status' => 'validation',
            'errors' => [
                'category' => ['The category may only contain letters, numbers, dashes and underscores.'],
            ],
        ]);
    }

    public function testDestroyArticle()
    {
        $user = factory(User::class)->create();
        $user->assignRole('admin');
        Sanctum::actingAs($user, ['*']);
        $slug = factory(Article::class)->create()['slug'];
        $this->delete(
            '/api/articles/delete/'.$slug,
            []
        )->assertJson(['status' => 'success']);
    }

    public function testDestroyArticleWithoutPermissions()
    {
        $user = factory(User::class)->create();
        Sanctum::actingAs($user, ['*']);
        $slug = factory(Article::class)->create()['slug'];
        $this->delete(
            '/api/articles/delete/'.$slug,
            []
        )->assertStatus(403);
    }

    public function testList()
    {
        factory(Article::class)->create();
        $this->get(
            '/api/articles/list',
            []
        )->assertJsonStructure([
            'data',
        ]);
    }

    public function testListWithCategory()
    {
        $article = factory(Article::class)->create();
        $this->get(
            '/api/articles/list/'.$article['category'],
            []
        )->assertJsonStructure([
            'data',
        ]);
    }
}

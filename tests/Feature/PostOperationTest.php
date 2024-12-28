<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;

class PostOperationTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $post;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->post = ['title' => 'テスト', 'content' => 'テスト'];
    }

    public function test_user_can_access_posts_index()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);
        $this->actingAs($this->user)
            ->get(route('posts.index'))
            ->assertStatus(200);
    }

    public function test_user_can_access_posts_create()
    {
        $this->actingAs($this->user)
            ->get(route('posts.create'))
            ->assertStatus(200);
    }

    public function test_user_can_store_post()
    {
        $this->actingAs($this->user)
            ->post(route('posts.store'), $this->post)
            ->assertRedirect(route('posts.index'));
    }

    public function test_user_can_update_own_post()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);
        $this->actingAs($this->user)
            ->patch(route('posts.update', $post), $this->post)
            ->assertRedirect(route('posts.show', $post));
    }
} 
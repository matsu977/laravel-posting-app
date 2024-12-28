<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;

class PostAuthTest extends TestCase
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

    public function test_guest_cannot_access_posts_index()
    {
        $this->get(route('posts.index'))->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_posts_create()
    {
        $this->get(route('posts.create'))->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_posts_store()
    {
        $this->post(route('posts.store'), $this->post)->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_posts_edit()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);
        $this->get(route('posts.edit', $post))->assertRedirect(route('login'));
    }
} 
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;

class PostTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $post;

    protected function setUp(): void
    {
        parent::setUp();
        // テストで共通して使用するデータを一度だけ作成
        $this->user = User::factory()->create();
        $this->post = [
            'title' => 'テストタイトル',
            'content' => 'テスト本文'
        ];
    }

    /*public function test_guest_cannot_access_posts_index()
    {
        $this->get(route('posts.index'))
            ->assertRedirect(route('login'));
    }

    public function test_user_can_access_posts_index()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);
        
        $this->actingAs($this->user)
            ->get(route('posts.index'))
            ->assertStatus(200)
            ->assertSee($post->title);
    }

    public function test_guest_cannot_access_posts_create()
    {
        $this->get(route('posts.create'))
            ->assertRedirect(route('login'));
    }

    public function test_user_can_access_posts_create()
    {
        $this->actingAs($this->user)
            ->get(route('posts.create'))
            ->assertStatus(200);
    }

    public function test_guest_cannot_access_posts_store()
    {
        $this->post(route('posts.store'), $this->post)
            ->assertRedirect(route('login'));
        $this->assertDatabaseMissing('posts', $this->post);
    }

    public function test_user_can_access_posts_store()
    {
        $this->actingAs($this->user)
            ->post(route('posts.store'), $this->post)
            ->assertRedirect(route('posts.index'));
        $this->assertDatabaseHas('posts', $this->post);
    }

    public function test_guest_cannot_access_posts_edit()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);
        
        $this->get(route('posts.edit', $post))
            ->assertRedirect(route('login'));
    }

    public function test_user_cannot_access_others_posts_edit()
    {
        $other_user = User::factory()->create();
        $others_post = Post::factory()->create(['user_id' => $other_user->id]);

        $this->actingAs($this->user)
            ->get(route('posts.edit', $others_post))
            ->assertRedirect(route('posts.index'));
    }

    public function test_user_can_access_own_posts_edit()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user)
            ->get(route('posts.edit', $post))
            ->assertStatus(200);
    }

    public function test_guest_cannot_update_post()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $this->patch(route('posts.update', $post), $this->post)
            ->assertRedirect(route('login'));
        $this->assertDatabaseMissing('posts', $this->post);
    }

    public function test_user_cannot_update_others_post()
    {
        $other_user = User::factory()->create();
        $others_post = Post::factory()->create(['user_id' => $other_user->id]);

        $this->actingAs($this->user)
            ->patch(route('posts.update', $others_post), $this->post)
            ->assertRedirect(route('posts.index'));
        $this->assertDatabaseMissing('posts', $this->post);
    }

    public function test_user_can_update_own_post()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user)
            ->patch(route('posts.update', $post), $this->post)
            ->assertRedirect(route('posts.show', $post));
        $this->assertDatabaseHas('posts', $this->post);
    }
    */

    // 未ログインのユーザーは投稿を削除できない
    public function test_guest_cannot_destroy_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->delete(route('posts.destroy', $post));

        $this->assertDatabaseHas('posts', ['id' => $post->id]);
        $response->assertRedirect(route('login'));
    }

    // ログイン済みのユーザーは他人の投稿を削除できない
    public function test_user_cannot_destroy_others_post()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();
        $others_post = Post::factory()->create(['user_id' => $other_user->id]);

        $response = $this->actingAs($user)->delete(route('posts.destroy', $others_post));

        $this->assertDatabaseHas('posts', ['id' => $others_post->id]);
        $response->assertRedirect(route('posts.index'));
    }

    // ログイン済みのユーザーは自身の投稿を削除できる
    public function test_user_can_destroy_own_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete(route('posts.destroy', $post));

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
        $response->assertRedirect(route('posts.index'));
    }

}

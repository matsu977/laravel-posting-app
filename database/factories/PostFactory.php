<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 1, // usersテーブルにidカラムの値が1のユーザーが存在することが前提
            'title' => fake()->realText(20, 5),
            'content' => fake()->realText(200, 5)
        ];
    }

     // 未ログインのユーザーは投稿詳細ページにアクセスできない
     public function test_guest_cannot_access_posts_show()
     {
         $user = User::factory()->create();
         $post = Post::factory()->create(['user_id' => $user->id]);
 
         $response = $this->get(route('posts.show', $post));
 
         $response->assertRedirect(route('login'));
     }
 
     // ログイン済みのユーザーは投稿詳細ページにアクセスできる
     public function test_user_can_access_posts_show()
     {
         $user = User::factory()->create();
         $post = Post::factory()->create(['user_id' => $user->id]);
 
         $response = $this->actingAs($user)->get(route('posts.show', $post));
 
         $response->assertStatus(200);
         $response->assertSee($post->title);
     }
}

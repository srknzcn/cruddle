<?php

namespace Tests\Unit;

use App\Models\Posts;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostsTest extends TestCase
{
    use WithFaker, DatabaseTransactions;


    public function test_create_post(): void
    {
        $data = [
            'email' => $this->faker->unique()->safeEmail,
            'post' => $this->faker->paragraph()
        ];

        $this->post(route('posts.store'), $data)
            ->assertJson([
                'success' => true,
                'data' => $data
            ]);
    }

    public function test_invalid_data(): void
    {
        $this->post(route('posts.store'), [
            'email' => $this->faker->word,
            'post' => $this->faker->paragraph()
        ])->assertJson([
            'success' => false,
        ]);
    }

    public function test_update_post(): void
    {
        $post = Posts::factory()->create();
        $data = [
            'email' => $this->faker->unique()->safeEmail,
            'post' => $this->faker->paragraph()
        ];

        $this->put(route('posts.update', $post->id), $data)
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);

        $this->assertDatabaseHas('posts', $data);
    }

    public function test_delete_post(): void
    {
        $post = Posts::factory()->create();

        $this->delete(route('posts.destroy', $post->id))
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);

        $this->assertDeleted($post);
    }

    public function test_show_post(): void
    {
        $post = Posts::factory()->create();
        $this->get(route('posts.show', $post->id))
            ->assertStatus(200)
            ->assertJson($post->toArray());
    }
}


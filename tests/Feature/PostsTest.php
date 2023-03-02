<?php

namespace Tests\Feature;

use App\Models\Posts;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class PostsTest extends TestCase
{
    use DatabaseTransactions;

    public function test_store(): void
    {
        $postData = [
            'email' => 'test@testet.com',
            'post' => 'Test me if u can!'
        ];

        $response = $this->postJson('/api/posts', $postData);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'success' => true,
            'data' => [
                'email' => $postData['email'],
                'post' => $postData['post'],
            ]
        ]);

        $this->assertDatabaseHas('posts', $postData);
    }

    public function test_show(): void
    {
        $post = Posts::factory()->create();
        $response = $this->getJson('/api/posts/' . $post->id);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'email' => $post->email,
            'post' => $post->post
        ]);
    }

    public function test_update(): void
    {
        $post = Posts::factory()->create();
        $updatedData = [
            'email' => 'serkanozcan@gmail.com',
            'post' => 'Test is the best :)'
        ];

        $response = $this->putJson('/api/posts/' . $post->id, $updatedData);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'success' => true
        ]);

        $this->assertDatabaseHas('posts', $updatedData);
    }

    public function test_destroy(): void
    {
        $post = Posts::factory()->create();
        $response = $this->deleteJson('/api/posts/' . $post->id);
        $response->assertJson([
            'success' => true
        ]);

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }
}

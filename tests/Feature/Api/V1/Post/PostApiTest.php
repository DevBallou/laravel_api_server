<?php

namespace Tests\Feature\Api\V1\Post;

use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class PostApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test index.
     */
    public function test_index(): void
    {
        // load data in DB
        $posts = Post::factory(10)->create();
        $postIds = $posts->map(fn ($post) => $post->id);

        // call index endpoint
        $response = $this->json('get', '/api/v1/posts');

        // assert status
        $response->assertStatus(200);

        // verify records
        $data = $response->json('data');
        // dump($data);

        // assert true
        collect($data)->each(fn ($post) => $this->assertTrue(in_array($post['id'], $postIds->toArray())));
    }

    public function test_show(): void
    {
        // load data in DB
        $dummy = Post::factory()->create();
        // call show endpoint
        $response = $this->json('get', '/api/v1/posts/' . $dummy->id);
        // assert status & verify records
        $result = $response->assertStatus(200)->json('data');
        // assert equals
        $this->assertEquals(data_get($result, 'id'), $dummy->id, 'Response ID not the same as model id.');
    }

    public function test_create(): void
    {
        // Event::fake();
        // load data in DB
        $dummy = Post::factory()->make();
        // call create endpoint
        $response = $this->json('post', '/api/v1/posts', $dummy->toArray());
        // assert status
        $result = $response->assertStatus(201)->json('data');
        // Event::assertDispatched(PostCreated::class);

        $result = collect($result)->only(array_keys($dummy->getAttributes()));

        // $result->each(function ($value, $field) use ($dummy) {
        //     $this->assertSame(data_get($dummy, $field), $value, 'Fillable is not the same.');
        // });
    }

    public function test_update(): void
    {
        // load data in DB
        $dummy = Post::factory()->create();
        $dummy2 = Post::factory()->make();

        $fillables = collect((new Post())->getFillable());

        $fillables->each(function ($toUpdate) use ($dummy, $dummy2) {
            // call update endpoint
            $response = $this->json('patch', '/api/v1/posts/' . $dummy->id, [
                $toUpdate => data_get($dummy2, $toUpdate),
            ]);

            // assert status & verify records
            $result = $response->assertStatus(200)->json('data');

            $this->assertSame(data_get($dummy2, $toUpdate), data_get($dummy->refresh(), $toUpdate), 'Failed to update model.');
        });
    }

    public function test_delete(): void
    {
        // Event::fake();
        // load data in DB
        $dummy = Post::factory()->create();
        // call delete endpoint
        $response = $this->json('delete', '/api/v1/posts/' . $dummy->id);
        // assert status
        $result = $response->assertStatus(200);
        // Event::assertDispatched(PostDeleted::class);
        $this->expectException(ModelNotFoundException::class);
        Post::query()->findOrFail($dummy->id);
    }
}

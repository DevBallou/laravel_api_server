<?php

namespace Tests\Feature\Api\V1\User;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;
    protected $uri = '/api/v1/users';

    /**
     * A basic feature test index.
     */
    public function test_index(): void
    {
        // load data in DB
        $users = User::factory(10)->create();
        $userIds = $users->map(fn ($user) => $user->id);

        // call index endpoint
        $response = $this->json('get', $this->uri);

        // assert status
        $response->assertStatus(200);

        // verify records
        $data = $response->json('data');
        collect($data)->each(fn ($user) => $this->assertTrue(in_array($user['id'], $userIds->toArray())));
    }

    public function test_show(): void
    {
        // load data in DB
        $dummy = User::factory()->create();
        // call show endpoint
        $response = $this->json('get', $this->uri . '/' . $dummy->id);
        // assert status & verify records
        $result = $response->assertStatus(200)->json('data');
        // assert equals
        $this->assertEquals(data_get($result, 'id'), $dummy->id, 'Response ID not the same as model id.');
    }

    public function test_create(): void
    {
        // load data in DB
        $dummy = User::factory()->make();
        // call create endpoint
        $response = $this->json('post', $this->uri, $dummy->toArray());
        // assert status
        $result = $response->assertStatus(201)->json('data');

        $result = collect($result)->only(array_keys($dummy->getAttributes()));
    }

    public function test_update(): void
    {
        // load data in DB
        $dummy = User::factory()->create();
        $dummy2 = User::factory()->make();

        $fillables = collect((new User())->getFillable());

        $fillables->each(function ($toUpdate) use ($dummy, $dummy2) {
            // call update endpoint
            $response = $this->json('patch', $this->uri . '/' . $dummy->id, [
                $toUpdate => data_get($dummy2, $toUpdate),
            ]);

            // assert status & verify records
            $result = $response->assertStatus(200)->json('data');

            $this->assertSame(data_get($dummy2, $toUpdate), data_get($dummy->refresh(), $toUpdate), 'Failed to update model.');
        });
    }

    public function test_delete(): void
    {
        // load data in DB
        $dummy = User::factory()->create();
        // call delete endpoint
        $response = $this->json('delete', $this->uri . '/' . $dummy->id);
        // assert status
        $result = $response->assertStatus(200);

        $this->expectException(ModelNotFoundException::class);
        User::query()->findOrFail($dummy->id);
    }
}

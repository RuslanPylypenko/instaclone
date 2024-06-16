<?php

namespace Tests\Feature;

use App\Models\User\UserEntity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_users_index(): void
    {
        $user = UserEntity::factory()->active()->create();

        UserEntity::factory(10)->create();
        $response = $this->actingAs($user, 'sanctum')
        ->get(route('users.index'));

        $response->assertOk();

        $users = UserEntity::query()->get();

        $response->assertJson([
            'data' => $users->map(fn ($item) => [
                'id'         => $item->id,
                'first_name' => $item->first_name,
                'last_name'  => $item->last_name,
                'avatar'     => $item->avatar,
                'nick'       => $item->nick
            ])->toArray(),
            'meta' => [
                'total'        => $users->count(),
                'per_page'     => 15,
                'current_page' => 1,
                'last_page'    => 1,
                'from'         => 1,
                'to'           => 11,
            ]
        ]);
    }
}

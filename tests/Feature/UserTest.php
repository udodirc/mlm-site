<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class UserTest extends BaseTest
{
    public function testCreateUser(): void
    {
        $this->auth('create-users');

        $data = [
            'name' => 'Test User',
            'email' => 'user@test.test',
            'password' => '12345678',
            'role' => 'manager'
        ];

        $response = $this->postJson(route('users.store'), $data);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonFragment(['name' => 'Test User']);
    }

    /*
     * @return void
     */
    public function testUpdateUser(): void
    {
        $user = $this->auth('update-users');

        $data = [
            'name' => 'Updated User',
            'email' => 'updated@test.test',
            'password' => '123456789',
            'role' => 'manager'
        ];

        $response = $this->putJson(route('users.update', $user->id), $data);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment(['name' => 'Updated User']);
    }

    /**
     * @return void
     */
    public function testDeleteUser(): void
    {
        $user = $this->auth('delete-users');

        $response = $this->deleteJson(route('users.destroy', $user));

        $response->assertStatus(200);
    }


    /**
     * @return void
     */
    public function testUsersList(): void
    {
        $this->auth('view-users');

        User::factory()->count(3)->create();

        $response = $this->getJson(route('users.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(4, 'data');
    }

    /**
     * @return void
     */
    public function testSingleUser(): void
    {
        $this->auth('view-users');

        $user = User::factory()->create();

        $response = $this->getJson(route('users.show', $user->id));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment(['name' => $user->name]);
    }
}

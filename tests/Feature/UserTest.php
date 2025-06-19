<?php

namespace Tests\Feature;

use App\Enums\PermissionsEnum;
use App\Models\User;
use Illuminate\Http\Response;

class UserTest extends BaseTest
{
    public function testCreateUser(): void
    {
        $this->auth(PermissionsEnum::UserCreate->value);

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
        $user = $this->auth(PermissionsEnum::UserUpdate->value);

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
        $user = $this->auth(PermissionsEnum::UserDelete->value);

        $response = $this->deleteJson(route('users.destroy', $user));

        $response->assertStatus(200);
    }


    /**
     * @return void
     */
    public function testUsersList(): void
    {
        $this->auth(PermissionsEnum::UserView->value);

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
        $this->auth(PermissionsEnum::UserView->value);

        $user = User::factory()->create();

        $response = $this->getJson(route('users.show', $user->id));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment(['name' => $user->name]);
    }
}

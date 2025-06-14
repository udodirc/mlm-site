<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /*
     * @return void
     */
    public function testCreateUser(): void
    {
        Permission::create([
            'name' => 'create-users',
            'guard_name' => 'api',
        ]);

        $admin = User::factory()->create();
        $admin->givePermissionTo('create-users');
        $this->actingAs($admin);

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
        Permission::create([
            'name' => 'update-users',
            'guard_name' => 'api',
        ]);

        $user = User::factory()->create();
        $user->givePermissionTo('update-users');
        $this->actingAs($user);

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
        Permission::create([
            'name' => 'delete-users',
            'guard_name' => 'api',
        ]);
        $user = User::factory()->create();
        $user->givePermissionTo('delete-users');
        $this->actingAs($user);

        $response = $this->deleteJson(route('users.destroy', $user));

        $response->assertStatus(200);
    }


    /**
     * @return void
     */
    public function testUsersList(): void
    {
        Permission::create([
            'name' => 'view-users',
            'guard_name' => 'api',
        ]);

        $admin = User::factory()->create();
        $admin->givePermissionTo('view-users');

        $this->actingAs($admin, 'api');

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
        Permission::create([
            'name' => 'view-users',
            'guard_name' => 'api',
        ]);

        $admin = User::factory()->create();
        $admin->givePermissionTo('view-users');

        $this->actingAs($admin, 'api');

        $user = User::factory()->create();

        $response = $this->getJson(route('users.show', $user->id));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment(['name' => $user->name]);
    }
}

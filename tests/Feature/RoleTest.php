<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Factories\RoleFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * @return void
     */
    public function testCreateRole(): void
    {
        Permission::create([
            'name' => 'create-roles',
            'guard_name' => 'api',
        ]);

        $role = Role::create([
            'name' => 'admin',
            'guard_name' => 'api',
        ]);
        $role->givePermissionTo('create-roles');
        $user = User::factory()->create();
        $user->assignRole($role);

        $this->actingAs($user, 'api');

        $data = [
            'name' => 'manager'
        ];

        $response = $this->postJson(route('roles.store'), $data);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonFragment(['name' => 'manager']);
    }

    /**
     * @return void
     */
    public function testUpdateRole(): void
    {
        Permission::create([
            'name' => 'update-roles',
            'guard_name' => 'api',
        ]);

        $role = Role::create([
            'name' => 'admin',
            'guard_name' => 'api',
        ]);
        $role->givePermissionTo('update-roles');
        $user = User::factory()->create();
        $user->assignRole($role);

        $this->actingAs($user, 'api');

        $data = [
            'name' => 'Editor'
        ];

        $response = $this->putJson(route('roles.update', $user->id), $data);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment(['name' => 'Editor']);
    }

    /**
     * @return void
     */
    public function testDeleteRole(): void
    {
        Permission::create([
            'name' => 'delete-roles',
            'guard_name' => 'api',
        ]);

        $role = Role::create([
            'name' => 'admin',
            'guard_name' => 'api',
        ]);
        $role->givePermissionTo('delete-roles');

        $user = User::factory()->create();
        $user->assignRole($role);

        $this->actingAs($user, 'api');

        $response = $this->deleteJson(route('roles.destroy', $role->id));

        $response->assertStatus(200);

        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }

    public function testRolesList(): void
    {
        Permission::create([
            'name' => 'create-roles',
            'guard_name' => 'api',
        ]);

        $role = Role::create([
            'name' => 'admin',
            'guard_name' => 'api',
        ]);
        $role->givePermissionTo('create-roles');
        $user = User::factory()->create();
        $user->assignRole($role);

        $this->actingAs($user, 'api');

        RoleFactory::new()->count(3)->create();

        $response = $this->getJson(route('roles.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(4, 'data');

        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name'],
            ],
        ]);
    }
}

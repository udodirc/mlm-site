<?php

namespace Tests\Feature;

use App\Enums\PermissionsEnum;
use App\Enums\RolesEnum;
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
            'name' => PermissionsEnum::RoleCreate->value,
            'guard_name' => RolesEnum::Guard->value,
        ]);

        $role = Role::create([
            'name' => 'admin',
            'guard_name' => RolesEnum::Guard->value,
        ]);
        $role->givePermissionTo(PermissionsEnum::RoleCreate->value);
        $user = User::factory()->create();
        $user->assignRole($role);

        $this->actingAs($user, RolesEnum::Guard->value);

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
            'name' => PermissionsEnum::RoleUpdate->value,
            'guard_name' => RolesEnum::Guard->value,
        ]);

        $role = Role::create([
            'name' => 'admin',
            'guard_name' => RolesEnum::Guard->value,
        ]);
        $role->givePermissionTo(PermissionsEnum::RoleUpdate->value);
        $user = User::factory()->create();
        $user->assignRole($role);

        $this->actingAs($user, RolesEnum::Guard->value);

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
            'name' => PermissionsEnum::RoleDelete->value,
            'guard_name' => RolesEnum::Guard->value,
        ]);

        $role = Role::create([
            'name' => 'admin',
            'guard_name' => RolesEnum::Guard->value,
        ]);
        $role->givePermissionTo(PermissionsEnum::RoleDelete->value);

        $user = User::factory()->create();
        $user->assignRole($role);

        $this->actingAs($user, RolesEnum::Guard->value);

        $response = $this->deleteJson(route('roles.destroy', $role->id));

        $response->assertStatus(200);

        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }

    public function testRolesList(): void
    {
        Permission::create([
            'name' => PermissionsEnum::RoleView->value,
            'guard_name' => RolesEnum::Guard->value,
        ]);

        $role = Role::create([
            'name' => 'admin',
            'guard_name' => RolesEnum::Guard->value,
        ]);
        $role->givePermissionTo(PermissionsEnum::RoleView->value);
        $user = User::factory()->create();
        $user->assignRole($role);

        $this->actingAs($user, RolesEnum::Guard->value);

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

    /**
     * @return void
     */
    public function testRolePermissions(): void
    {
        Permission::create([
            'name' => PermissionsEnum::RoleCreate->value,
            'guard_name' => RolesEnum::Guard->value,
        ]);

        $role = Role::create([
            'name' => RolesEnum::Admin->value,
            'guard_name' => RolesEnum::Guard->value,
        ]);
        $role->givePermissionTo(PermissionsEnum::RoleCreate->value);
        $user = User::factory()->create();
        $user->assignRole($role);

        $this->actingAs($user, RolesEnum::Guard->value);

        $data = [
            'role' => RolesEnum::Admin->value,
            'permission' => [
                ['name' => PermissionsEnum::RoleCreate->value],
                ['name' => PermissionsEnum::RoleUpdate->value],
                ['name' => PermissionsEnum::RoleDelete->value],
            ],
        ];

        $response = $this->postJson(route('roles.assign-permissions'), $data);

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @return void
     */
    public function testRoleAssign(): void
    {
        Permission::create([
            'name' => PermissionsEnum::RoleCreate->value,
            'guard_name' => RolesEnum::Guard->value,
        ]);

        $role = Role::create([
            'name' => RolesEnum::Admin->value,
            'guard_name' => RolesEnum::Guard->value,
        ]);
        $role->givePermissionTo(PermissionsEnum::RoleCreate->value);
        $user = User::factory()->create();
        $user->assignRole($role);

        $this->actingAs($user, RolesEnum::Guard->value);

        $data = [
            'role' => RolesEnum::Admin->value
        ];

        $response = $this->postJson(route('roles.assign-role'), $data);

        $response->assertStatus(Response::HTTP_OK);
    }
}

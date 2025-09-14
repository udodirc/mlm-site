<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Enums\RolesEnum;
use App\Models\Role;
use App\Models\User;
use Tests\TestCase;

abstract class BaseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    protected function auth(string $permission): User
    {
        Role::firstOrCreate([
            'name' => 'manager',
            'guard_name' => 'api',
        ]);

        Permission::firstOrCreate([
            'name' => $permission,
            'guard_name' => RolesEnum::Guard->value,
        ]);

        $admin = User::factory()
            ->superAdmin()
            ->create();
        $admin->givePermissionTo($permission);
        $this->actingAs($admin);

        return $admin;
    }

    /**
     * Универсальный метод для CRUD действий
     *
     * @param 'create'|'update'|'delete'|'list'|'show' $action
     * @param string $route
     * @param array $data
     * @param string|null $table
     * @param int|null $id
     * @param array $expectedJson
     * @param int|null $expectedCount
     */
    protected function performAction(
        string $action,
        string $route,
        array $data = [],
        ?string $table = null,
        ?int $id = null,
        array $expectedJson = [],
        ?int $expectedCount = null
    ): TestResponse {
        switch ($action) {
            case 'create':
                $response = $this->postJson(route($route), $data);
                $response->assertCreated();
                $table && $this->assertDatabaseHas($table, $data);
                $expectedJson && $response->assertJsonFragment($expectedJson);
                break;

            case 'update':
                $response = $this->putJson(route($route, $id), $data);
                $response->assertOk();
                $table && $this->assertDatabaseHas($table, $data);
                $expectedJson && $response->assertJsonFragment($expectedJson);
                break;

            case 'delete':
                $response = $this->deleteJson(route($route, $id));
                $response->assertOk();
                $table && $this->assertDatabaseMissing($table, ['id' => $id]);
                break;

            case 'list':
                $response = $this->getJson(route($route));
                $response->assertOk();
                $expectedCount !== null && $response->assertJsonCount($expectedCount, 'data');
                break;

            case 'show':
                $response = $this->getJson(route($route, $id));
                $response->assertOk();
                $expectedJson && $response->assertJsonFragment($expectedJson);
                break;

            default:
                throw new \InvalidArgumentException("Unsupported action: $action");
        }

        return $response;
    }
}

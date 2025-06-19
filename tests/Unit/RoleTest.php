<?php

namespace Tests\Unit;

use App\Data\Admin\Role\AssignRoleData;
use App\Data\Admin\Role\RoleCreateData;
use App\Data\Admin\Role\RoleUpdateData;
use App\Enums\PermissionsEnum;
use App\Enums\RolesEnum;
use App\Models\User;
use App\Repositories\RoleRepository;
use App\Services\RoleService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\MockObject\MockObject;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class RoleTest extends BaseTest
{
    protected function getServiceClass(): string
    {
        return RoleService::class;
    }

    protected function getRepositoryClass(): string
    {
        return RoleRepository::class;
    }

    public function testCreateRole(): void
    {
        $dto = new RoleCreateData(
            name: 'manager'
        );

        $role = new Role([
            'name' => 'manager'
        ]);

        $this->assertCreateEntity(
            createDto: $dto,
            expectedInput: [
                'name' => 'manager'
            ],
            expectedModel: $role
        );
    }

    public function testUpdateRole(): void
    {
        $dto = new RoleUpdateData(
            name: 'editor'
        );

        $role = new Role([
            'id' => 1,
            'name' => 'manager'
        ]);

        $role->name = 'editor';

        $this->assertUpdateEntity(
            model: $role,
            updateDto: $dto,
            expectedInput: [
                'name' => 'editor'
            ],
            expectedModel: $role
        );
    }

    public function testDeleteRole(): void
    {
        $role = new Role([
            'id' => 1,
            'name' => 'manager'
        ]);

        $this->assertDeleteEntity(
            model: $role
        );
    }

    public function testListRoles(): void
    {
        $roles = new Collection([
            new Role([
                'name' => 'admin'
            ]),
            new Role([
                'name' => 'manager'
            ]),
        ]);

        $this->assertListItemsEntity(
            model: $roles,
            items: ['admin', 'manager']
        );
    }

    public function testShowRole(): void
    {
        $this->assertShowItemEntity(
            PermissionsEnum::RoleView->value,
            'roles.show',
            true
        );
    }

    public function testAssignRoleToUser(): void
    {
        $roleRepository = new RoleRepository(new Role());
        $roleService = new RoleService($roleRepository);

        Artisan::call('permission:cache-reset');

        Role::create([
            'name' => RolesEnum::Admin->value,
            'guard_name' => RolesEnum::Guard->value,
        ]);

        $user = User::factory()->create();

        $data = new AssignRoleData(role: RolesEnum::Admin->value);

        $result = $roleService->assignRole($user, $data);

        $this->assertTrue($result, __('messages.assign_role'));
        $this->assertTrue($user->hasRole(RolesEnum::Admin->value), __('messages.assign_admin_role'));
    }

    public function testRolePermissions(): void
    {
        $this->auth('view-permissions', true);

        $data = [
            'role' => RolesEnum::Admin->value,
            'permission' => [
                ['name' => PermissionsEnum::RoleCreate->value],
                ['name' => PermissionsEnum::RoleUpdate->value],
                ['name' => PermissionsEnum::RoleDelete->value],
            ],
        ];

        $response = $this->postJson(route('roles.assign-permissions'), $data);
        $response->assertOk();
    }
}

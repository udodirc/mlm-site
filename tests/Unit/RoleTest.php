<?php

namespace Tests\Unit;

use App\Data\Admin\Role\AssignRoleData;
use App\Data\Admin\Role\RoleCreateData;
use App\Data\Admin\Role\RoleUpdateData;
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
//    use RefreshDatabase;
//
//    /** @var RoleRepository|MockObject */
//    protected $roleRepository;
//
//    /** @var RoleService */
//    protected $roleService;
//
//    protected function setUp(): void
//    {
//        parent::setUp();
//
//        $this->roleRepository = $this->createMock(RoleRepository::class);
//        $this->roleService = new RoleService($this->roleRepository);
//    }

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
            'view-permissions',
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
            'name' => 'admin',
            'guard_name' => 'api',
        ]);

        $user = User::factory()->create();

        $data = new AssignRoleData(role: 'admin');

        $result = $roleService->assignRole($user, $data);

        $this->assertTrue($result, 'Метод assignRole вернул false');
        $this->assertTrue($user->hasRole('admin'), 'Роль admin не была назначена пользователю');
    }

    public function testRolePermissions(): void
    {
        $this->auth('view-permissions', true);

        $data = [
            'role' => 'admin',
            'permission' => [
                ['name' => 'create-roles'],
                ['name' => 'update-roles'],
                ['name' => 'delete-roles'],
            ],
        ];

        $response = $this->postJson(route('roles.assign-permissions'), $data);
        $response->assertOk();
    }
}

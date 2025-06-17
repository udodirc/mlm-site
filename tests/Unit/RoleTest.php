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

class RoleTest extends TestCase
{
    use RefreshDatabase;

    /** @var RoleRepository|MockObject */
    protected $roleRepository;

    /** @var RoleService */
    protected $roleService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->roleRepository = $this->createMock(RoleRepository::class);
        $this->roleService = new RoleService($this->roleRepository);
    }

    public function testCreateRole(): void
    {
        $data = new RoleCreateData(
            name: 'manager'
        );

        $role = new Role([
            'name' => 'manager'
        ]);

        $this->roleRepository
            ->expects($this->once())
            ->method('create')
            ->with(
                $this->callback(fn(array $data) => $data['name'] === 'manager')
            )
            ->willReturn($role);

        $createdRole = $this->roleService->create($data);

        $this->assertEquals('manager', $createdRole->name);
    }

    public function testUpdateRole(): void
    {
        $data = new RoleUpdateData(
            name: 'manager'
        );

        $role = new Role([
            'name' => 'manager'
        ]);

        $this->roleRepository
            ->expects($this->once())
            ->method('update')
            ->with(
                $this->equalTo($role),
                $this->callback(function ($array) {
                    return $array['name'] === 'manager';
                })
            )
            ->willReturn($role);

        $updateRole = $this->roleService->update($role, $data);

        $this->assertSame($role, $updateRole);
    }

    public function testDeleteRole(): void
    {
        $role = new Role([
            'id' => 1,
            'name' => 'manager'
        ]);

        $this->roleRepository
            ->expects($this->once())
            ->method('delete')
            ->with($this->equalTo($role))
            ->willReturn(true);

        $result = $this->roleService->delete($role);

        $this->assertTrue($result);
    }

    public function testListRoles(): void
    {
        $users = new Collection([
            new Role([
                'name' => 'admin'
            ]),
            new Role([
                'name' => 'manager'
            ]),
        ]);

        $this->roleRepository
            ->expects($this->once())
            ->method('all')
            ->willReturn($users);

        $result = $this->roleService->all();

        $this->assertCount(2, $result);
        $this->assertEquals('admin', $result[0]->name);
        $this->assertEquals('manager', $result[1]->name);
    }

    public function testShowRole(): void
    {
        Permission::create([
            'name' => 'view-permissions',
            'guard_name' => 'api',
        ]);

        $admin = User::factory()->create([
            'name' => 'Alice',
            'email' => 'alice@test.test',
        ]);

        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo('view-permissions');
        $admin->assignRole('admin');

        $this->actingAs($admin, 'api');

        $response = $this->getJson(route('roles.show', $role->id));

        $response->assertOk();
        $response->assertJson([
            'data' => [
                'id' => $role->id,
                'name' => 'admin',
            ]
        ]);
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
        Permission::create([
            'name' => 'view-permissions',
            'guard_name' => 'api',
        ]);

        $admin = User::factory()->create([
            'name' => 'Alice',
            'email' => 'alice@test.test',
        ]);

        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo('view-permissions');
        $admin->assignRole('admin');

        $this->actingAs($admin, 'api');

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

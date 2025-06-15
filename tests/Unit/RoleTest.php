<?php

namespace Tests\Unit;

use App\Data\Admin\Role\RoleCreateData;
use App\Data\Admin\Role\RoleUpdateData;
use App\Repositories\RoleRepository;
use App\Services\RoleService;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoleTest extends TestCase
{
    protected $roleRepository;
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

    public function testDeleteUser(): void
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
}

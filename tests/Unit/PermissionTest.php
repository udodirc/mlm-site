<?php

namespace Tests\Unit;

use App\Repositories\PermissionRepository;
use App\Services\PermissionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    /** @var PermissionRepository|MockObject */
    protected $permissionRepository;

    /** @var PermissionService */
    protected $permissionService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->permissionRepository = $this->createMock(PermissionRepository::class);
        $this->permissionService = new PermissionService($this->permissionRepository);
    }

    public function testCreatePermissions(): void
    {
        ;$this->permissionRepository
            ->expects($this->once())
            ->method('createPermissions')
            ->willReturn(true);

        $result = $this->permissionService->createPermissions();

        $this->assertTrue($result);
    }
}

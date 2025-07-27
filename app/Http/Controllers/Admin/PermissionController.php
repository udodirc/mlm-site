<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Resource\PermissionResource;
use App\Services\PermissionService;
use Spatie\Permission\Models\Permission;

/**
 * @extends BaseController<PermissionService, Permission, PermissionResource>
 */
class PermissionController extends BaseController
{
    public function __construct(PermissionService $service)
    {
        parent::__construct(
            $service,
            PermissionResource::class,
            Permission::class,
            null,
            null,
        );
    }

    public function createPermissions(): ?bool
    {
        return $this->service->createPermissions();
    }
}

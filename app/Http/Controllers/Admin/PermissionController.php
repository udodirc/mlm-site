<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\PermissionService;
use Spatie\Permission\Models\Permission;

class PermissionController extends BaseController
{
    public function __construct(PermissionService $service)
    {
        parent::__construct(
            $service,
            null,
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

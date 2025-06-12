<?php

namespace App\Repositories;

use App\Enums\PermissionsEnum;
use App\Repositories\Contracts\PermissionRepositoryInterface;
use Spatie\Permission\Models\Permission;

class PermissionRepository extends AbstractRepository implements PermissionRepositoryInterface
{
    public function __construct(Permission $permission)
    {
        parent::__construct($permission);
    }
    public function createPermissions(): ?bool
    {
        foreach (PermissionsEnum::cases() as $permissionEnum) {
            $permissionName = $permissionEnum->value;

            $this->model->firstOrCreate([
                'name' => $permissionName,
            ]);
        }

        return true;
    }
}

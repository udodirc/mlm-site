<?php

namespace App\Repositories\Contracts;

use App\Data\Admin\Role\RoleAssignPermissionsData;
use Spatie\Permission\Models\Role;

interface RoleRepositoryInterface extends BaseRepositoryInterface
{
    public function existRole(string $role): Role|null;
    public function assignPermissions(RoleAssignPermissionsData $data, Role $role): ?bool;
}

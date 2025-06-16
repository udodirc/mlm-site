<?php

namespace App\Repositories\Contracts;

use App\Data\Admin\Role\AssignRoleData;
use App\Data\Admin\Role\RoleAssignPermissionsData;
use App\Models\User;
use Spatie\Permission\Models\Role;

interface RoleRepositoryInterface extends BaseRepositoryInterface
{
    public function existRole(string $role): Role|null;
    public function assignPermissions(RoleAssignPermissionsData $data, Role $role): ?bool;
    public function assignRole(User $user, AssignRoleData $data): bool;
}

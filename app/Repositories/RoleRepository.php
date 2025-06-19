<?php

namespace App\Repositories;

use App\Data\Admin\Role\AssignRoleData;
use App\Data\Admin\Role\RoleAssignPermissionsData;
use App\Enums\RolesEnum;
use App\Models\User;
use App\Repositories\Contracts\RoleRepositoryInterface;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleRepository extends AbstractRepository implements RoleRepositoryInterface
{
    public function __construct(Role $role)
    {
        parent::__construct($role);
    }

    public function existRole(string $role): Role|null
    {
        return Role::where('name', $role)->first();
    }

    public function assignPermissions(RoleAssignPermissionsData $data, Role $role): ?bool
    {
        $permissionNames = array_column($data->permissions, 'name');
        $permissions = Permission::whereIn('name', $permissionNames)->pluck('name')->toArray();

        if (!empty($permissions)) {
            $assignPermissions = array_intersect($permissionNames, $permissions);
            $role->syncPermissions($assignPermissions);
            return true;
        }

        return false;
    }

    public function assignRole(User $user, AssignRoleData $data): bool
    {
        $role = Role::where('name', $data->role)
            ->where('guard_name', RolesEnum::Guard->value)
            ->first();

        if (! $role) {
            return false;
        }

        $user->assignRole($role);

        return $user->hasRole($data->role, RolesEnum::Guard->value);
    }
}

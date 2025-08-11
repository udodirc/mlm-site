<?php

namespace App\Repositories;

use App\Data\Admin\Role\AssignRoleData;
use App\Data\Admin\Role\RoleAssignPermissionsData;
use App\Enums\RolesEnum;
use App\Models\User;
use App\Repositories\Contracts\RoleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Permission;
use App\Models\Role;

class RoleRepository extends AbstractRepository implements RoleRepositoryInterface
{
    public function __construct(Role $role)
    {
        parent::__construct($role);
    }

    /**
     * @param array $filters
     * @return Collection
     */
    public function all(array $filters = []): Collection
    {
        return $this->model
            ->newQuery()
            ->filter($filters)
            ->get();
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

        $user->syncRoles([$role]);

        return $user->hasRole($data->role, RolesEnum::Guard->value);
    }
}

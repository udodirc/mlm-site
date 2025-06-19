<?php

namespace App\Services;

use App\Data\Admin\Role\AssignRoleData;
use App\Data\Admin\Role\RoleAssignPermissionsData;
use App\Data\Admin\Role\RoleCreateData;
use App\Data\Admin\Role\RoleUpdateData;
use App\Models\User;
use Spatie\LaravelData\Data;
use App\Repositories\Contracts\RoleRepositoryInterface;
use Spatie\Permission\Models\Role;

/**
 * @extends BaseService<RoleRepositoryInterface, RoleCreateData, RoleUpdateData, Role>
 */
class RoleService extends BaseService
{
    public function __construct(RoleRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    protected function toCreateArray(Data $data): array
    {
        /** @var RoleCreateData $data */
        return [
            'name' => $data->name
        ];
    }

    protected function toUpdateArray(Data $data): array
    {
        /** @var RoleUpdateData $data */
        return [
            'name' => $data->name
        ];
    }

    public function existRole(string $role): Role|null
    {
        return $this->repository->existRole($role);
    }

    public function assignPermissions(RoleAssignPermissionsData $data, Role $role): ?bool
    {
        return $this->repository->assignPermissions($data, $role);
    }

    public function assignRole(User $user, AssignRoleData $data): bool
    {
        return $this->repository->assignRole($user, $data);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Role\RoleCreateData;
use App\Data\Admin\Role\RoleUpdateData;
use App\Data\Admin\Role\RoleAssignPermissionsData;
use App\Http\Controllers\BaseController;
use App\Resource\RoleResource;
use App\Services\RoleService;
use Spatie\Permission\Models\Role;
use Illuminate\Http\JsonResponse;

/**
 * @extends BaseController<RoleService, Role, RoleResource, RoleCreateData, RoleUpdateData>
 */
class RoleController extends BaseController
{
    public function __construct(RoleService $service)
    {
        parent::__construct(
            $service,
            RoleResource::class,
            Role::class,
            RoleCreateData::class,
            RoleUpdateData::class
        );
    }

    public function assignPermissions(RoleAssignPermissionsData $data): bool|JsonResponse
    {
        $role = $this->service->existRole($data->role);
        if (!$role) {
            return response()->json(['error' => true], 404);
        }

        $role->load('permissions');

        return $this->service->assignPermissions($data, $role);
    }
}

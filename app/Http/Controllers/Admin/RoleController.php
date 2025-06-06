<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Role\RoleCreateData;
use App\Data\Admin\Role\RoleUpdateData;
use App\Http\Controllers\BaseController;
use App\Resource\RoleResource;
use App\Services\RoleService;
use Spatie\Permission\Models\Role;

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
}

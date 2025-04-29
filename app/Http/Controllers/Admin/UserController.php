<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\User\UserCreateData;
use App\Data\Admin\User\UserUpdateData;
use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Resource\UserResource;
use App\Services\UserService;

/**
 * @extends BaseController<UserService, User, UserResource, UserCreateData, UserUpdateData>
 */
class UserController extends BaseController
{
    public function __construct(UserService $service)
    {
        parent::__construct(
            $service,
            UserResource::class,
            User::class,
            UserCreateData::class,
            UserUpdateData::class
        );
    }
}

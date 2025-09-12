<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\User\ProfileData;
use App\Data\Admin\User\UserFilterData;
use App\Data\Admin\User\UserCreateData;
use App\Data\Admin\User\UserUpdateData;
use App\Enums\PaginationEnum;
use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Resource\UserResource;
use App\Services\UserService;

/**
 * @extends BaseController<UserService, User, UserResource, UserCreateData, UserUpdateData>
 */
class UserController extends BaseController
{
    protected ?string $filterDataClass = UserFilterData::class;
    protected string $perPageConfigKey = PaginationEnum::User->value;

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

    public function profile(ProfileData $data): UserResource
    {
        return new UserResource(
            $this->service->profile($data)
        );
    }

    public function profileUser(): UserResource
    {
        return new UserResource(
            auth()->user()
        );
    }
}

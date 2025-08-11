<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\User\UserFilterData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
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

    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        $filters = UserFilterData::from($request);
        $filtersArray = $filters->toArray();

        return ($this->resourceClass)::collection(
            $this->service->all($filtersArray)
        );
    }
}

<?php
namespace App\Http\Controllers;

use App\Data\Admin\User\UserCreateData;
use App\Data\Admin\User\UserUpdateData;
use App\Models\User;
use App\Resource\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    public function index(): AnonymousResourceCollection|JsonResponse
    {
        return UserResource::collection(
            $this->userService->all()
        );
    }

    public function show(User $user): UserResource|JsonResponse
    {
        return new UserResource(
            $user
        );
    }

    public function store(UserCreateData $data): UserResource|JsonResponse
    {
        return new UserResource(
            $this->userService->create($data)
        );
    }

    public function update(User $user, UserUpdateData $data): UserResource|JsonResponse
    {
        return new UserResource(
            $this->userService->update($user, $data)
        );
    }

    public function destroy($id)
    {
        return response()->json(['deleted' => $this->userService->delete($id)]);
    }
}

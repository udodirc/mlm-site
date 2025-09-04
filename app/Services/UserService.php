<?php

namespace App\Services;

use App\Data\Admin\User\UserCreateData;
use App\Data\Admin\User\UserUpdateData;
use Spatie\LaravelData\Data;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Models\User;

/**
 * @extends BaseService<UserRepositoryInterface, UserCreateData, UserUpdateData, User>
 */
class UserService extends BaseService
{
    public function __construct(UserRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    protected function toCreateArray(Data $data): array
    {
        /** @var UserCreateData $data */
        return [
            'email' => $data->email,
            'name' => $data->name,
            'password' => bcrypt($data->password),
            'role' => $data->role,
        ];
    }

    protected function toUpdateArray(Data $data): array
    {
        $user = [
            'email' => $data->email,
            'name' => $data->name,
            'role' => $data->role,
            'status' => $data->status,
        ];

        if($data->password){
            $user['password'] = bcrypt($data->password);
        }

        /** @var UserUpdateData $data */
        return $user;
    }
}

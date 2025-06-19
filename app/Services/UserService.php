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
        ];
    }

    protected function toUpdateArray(Data $data): array
    {
        /** @var UserUpdateData $data */
        return [
            'email' => $data->email,
            'name' => $data->name,
            'password' => bcrypt($data->password)
        ];
    }
}

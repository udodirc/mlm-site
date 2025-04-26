<?php
namespace App\Services;

use App\Data\Admin\User\UserCreateData;
use App\Data\Admin\User\UserUpdateData;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function all(): Collection
    {
        return $this->userRepository->all();
    }

    public function create(UserCreateData $data): User
    {
        $data = [
            'email' => $data->email,
            'name' => $data->name,
            'password' => bcrypt($data->password),
            //'role' => $data->role,
            //'email_verified_at' => $data->emailVerifiedAt ?? now(),
        ];

        return $this->userRepository->create($data);
    }

    public function update(User $user, UserUpdateData $data): User
    {
        $data = [
            'email' => $data->email,
            'name' => $data->name,
            'password' => bcrypt($data->password),
            //'role' => $data->role,
            //'email_verified_at' => $data->emailVerifiedAt ?? now(),
        ];

        return $this->userRepository->update($user, $data);
    }

    public function delete(User $model): bool
    {
        return $this->userRepository->delete($model);
    }
}

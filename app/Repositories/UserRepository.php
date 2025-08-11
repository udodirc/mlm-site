<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    /**
     * @param array $filters
     * @return Collection
     */
    public function all(array $filters = []): Collection
    {
        return $this->model
            ->newQuery()
            ->filter($filters)  // кастомный метод из UserQueryBuilder
            ->get();
    }

    public function update(Model $model, array $data): ?Model
    {
        $model->fill(Arr::only($data, ['name', 'email']));

        if (!empty($data['password'])) {
            $model->password = Hash::make($data['password']);
        }

        $model->save();

        if (!empty($data['role'])) {
            $model->syncRoles([$data['role']]);
        }

        return $model;
    }
}

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
        $query = $this->model->newQuery();

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['email'])) {
            $query->where('email', $filters['email']);
        }

        if (!empty($filters['role'])) {
            $query->whereHas('roles', function (Builder $q) use ($filters) {
                $q->where('name', $filters['role']);
            });
        }

        if (!empty($filters['created_from']) && !empty($filters['created_to'])) {
            $filters['created_from'] = Carbon::createFromFormat('Y-m-d', $filters['created_from'])->startOfDay();
            $filters['created_to'] = Carbon::createFromFormat('Y-m-d', $filters['created_to'])->endOfDay();
            $query->whereBetween('created_at', [$filters['created_from'], $filters['created_to']]);
        } else {
            if (!empty($filters['created_from'])) {
                $filters['created_from'] = Carbon::createFromFormat('Y-m-d', $filters['created_from'])->startOfDay();
                $query->where('created_at', '>=', $filters['created_from']);
            }

            if (!empty($filters['created_to'])) {
                $filters['created_to'] = Carbon::createFromFormat('Y-m-d', $filters['created_to'])->endOfDay();
                $query->where('created_at', '<=', $filters['created_to']);
            }
        }

        return $query->get();
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

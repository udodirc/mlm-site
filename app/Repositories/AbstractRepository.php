<?php

namespace App\Repositories;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

abstract class AbstractRepository implements BaseRepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all($paginate = true, array $filters = [], $paginationKey = ''): LengthAwarePaginator|Collection
    {
        if ($paginate) {
            return $this->model
                ->newQuery()
                ->filter($filters)
                ->paginate(config('app.settings.'.$paginationKey) ?? config('app.default_pagination'));
        } else {
            return $this->model->all();
        }
    }

    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(Model $model, array $data): ?Model
    {
        $model->update($data);

        return $model instanceof Model ? $model : null;
    }

    public function delete(Model $model): bool
    {
        return (bool) $model->delete();
    }

    public function toggleStatus(Model $model, string $column = 'status'): ?Model
    {
        if (! $model->isFillable($column) && ! array_key_exists($column, $model->getAttributes())) {
            throw new \InvalidArgumentException("Column '{$column}' does not exist on model " . get_class($model));
        }

        $model->{$column} = !$model->{$column};
        $model->save();

        return $model;
    }
}

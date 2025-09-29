<?php

namespace App\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Data;

/**
 * @template TRepository
 * @template TData of \Spatie\LaravelData\Data
 * @template TModel of \Illuminate\Database\Eloquent\Model
 */
abstract class BaseService
{
    /**
     * @var TRepository
     */
    protected mixed $repository;

    public function __construct(mixed $repository)
    {
        $this->repository = $repository;
    }

    public function all($paginate = true, array $filters = [], $paginationKey = ''): LengthAwarePaginator|Collection
    {
        return $this->repository->all($paginate, $filters, $paginationKey);
    }

    public function allWithStatus(): LengthAwarePaginator|Collection
    {
        return $this->repository->allWithStatus();
    }

    /**
     * @param int $id
     * @return TModel|null
     */
    public function find(int $id): ?Model
    {
        return $this->repository->find($id);
    }

    /**
     * @param TData $data
     * @return TModel
     */
    public function create(mixed $data): Model
    {
        return $this->repository->create($this->toCreateArray($data));
    }

    /**
     * @param TModel $model
     * @param TData $data
     * @return TModel
     */
    public function update(Model $model, mixed $data): Model
    {
        return $this->repository->update($model, $this->toUpdateArray($data));
    }

    /**
     * @param TModel $model
     */
    public function delete(Model $model): bool
    {
        return $this->repository->delete($model);
    }

    /**
     * @param TModel $model
     * @param string $column
     * @return TModel|null
     */
    public function toggleStatus(Model $model, string $column = 'status'): ?Model
    {
        return $this->repository->toggleStatus($model, $column);
    }

    /**
     * Преобразование DTO в массив для создания.
     *
     * @param TData $data
     */
    abstract protected function toCreateArray(Data $data): array;

    /**
     * Преобразование DTO в массив для обновления.
     *
     * @param TData $data
     */
    abstract protected function toUpdateArray(Data $data): array;
}

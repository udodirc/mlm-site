<?php

namespace App\Services;

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

    public function all(): Collection
    {
        return $this->repository->all();
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

<?php

namespace App\Repositories;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

abstract class AbstractRepository implements BaseRepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    protected function isCacheable(): bool
    {
        return property_exists($this->model, 'cache') && $this->model::$cache === true;
    }

    protected function getTtl(): \DateTimeInterface
    {
        // Если у модели явно задан $ttl
        if (property_exists($this->model, 'ttl') && !is_null($this->model::$ttl)) {
            return now()->addDays($this->model::$ttl);
        }

        // Берем из конфига, если есть
        $modelName = class_basename($this->model);

        $days = (int) config("cache.models.$modelName.ttl_days")
            ?? config('cache.models.default_ttl_days', 7);

        return now()->addDays($days);
    }

    public function all(bool $paginate = true, array $filters = [], string $paginationKey = ''): LengthAwarePaginator|Collection
    {
        if (!$this->isCacheable()) {
            return $this->queryAll($paginate, $filters, $paginationKey);
        }

        $cacheKey = $this->getCacheKey('all', $filters);

        return Cache::remember($cacheKey, $this->getTtl(), fn() => $this->queryAll($paginate, $filters, $paginationKey));
    }

    protected function queryAll(bool $paginate, array $filters, string $paginationKey): LengthAwarePaginator|Collection
    {
        if ($paginate) {
            return $this->model
                ->newQuery()
                ->filter($filters)
                ->paginate(config('app.settings.' . $paginationKey) ?? config('app.default_pagination'));
        }

        return $this->model->all();
    }

    public function find(int $id): ?Model
    {
        if (!$this->isCacheable()) {
            return $this->model->find($id);
        }

        $cacheKey = $this->getCacheKey($id);

        return Cache::remember($cacheKey, $this->getTtl(), fn() => $this->model->find($id));
    }

    public function create(array $data): Model
    {
        $model = $this->model->create($data);

        if ($this->isCacheable()) {
            Cache::put($this->getCacheKey($model->getKey()), $model, $this->getTtl());
            $this->clearListCache();
        }

        return $model;
    }

    public function update(Model $model, array $data): ?Model
    {
        $model->update($data);

        if ($this->isCacheable()) {
            Cache::put($this->getCacheKey($model->getKey()), $model, $this->getTtl());
            $this->clearListCache();
        }

        return $model;
    }

    public function delete(Model $model): bool
    {
        $deleted = (bool) $model->delete();

        if ($deleted && $this->isCacheable()) {
            Cache::forget($this->getCacheKey($model->getKey()));
            $this->clearListCache();
        }

        return $deleted;
    }

    public function toggleStatus(Model $model, string $column = 'status'): ?Model
    {
        if (!$model->isFillable($column) && !array_key_exists($column, $model->getAttributes())) {
            throw new \InvalidArgumentException("Column '{$column}' does not exist on model " . get_class($model));
        }

        $model->{$column} = !$model->{$column};
        $model->save();

        if ($this->isCacheable()) {
            Cache::put($this->getCacheKey($model->getKey()), $model, $this->getTtl());
            $this->clearListCache();
        }

        return $model;
    }

    protected function getCacheKey(mixed $identifier, array $filters = []): string
    {
        $key = 'model:' . class_basename($this->model) . ':' . $identifier;

        if (!empty($filters)) {
            $key .= ':' . md5(json_encode($filters));
        }

        return $key;
    }

    protected function clearListCache(): void
    {
        if ($this->isCacheable()) {
            Cache::tags($this->getTag())->flush();
        }
    }

    protected function getTag(): string
    {
        return 'model:' . class_basename($this->model);
    }
}

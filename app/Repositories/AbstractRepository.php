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
        if (property_exists($this->model, 'ttl') && !is_null($this->model::$ttl)) {
            return now()->addDays($this->model::$ttl);
        }

        $modelName = class_basename($this->model);
        $days = (int) config("cache.models.$modelName.ttl_days")
            ?? config('cache.models.default_ttl_days', 7);

        return now()->addDays($days);
    }

    protected function getTag(): string
    {
        return 'model:' . class_basename($this->model);
    }

    protected function getCacheKey(
        mixed $identifier,
        array $filters = [],
        ?int $page = null,
        ?int $perPage = null
    ): string {
        $key = 'model:' . class_basename($this->model) . ':' . $identifier;

        if (!empty($filters)) {
            $key .= ':f' . md5(json_encode($filters));
        }

        if ($page !== null) {
            $key .= ':p' . $page;
        }

        if ($perPage !== null) {
            $key .= ':pp' . $perPage;
        }

        return $key;
    }

    protected function clearListCache(): void
    {
        if ($this->isCacheable()) {
            try {
                Cache::tags($this->getTag())->flush();
            } catch (\Exception $e) {
                logger()->warning("Failed to flush cache tags for " . $this->getTag() . ": " . $e->getMessage());
            }
        }
    }

    public function all(
        bool $paginate = true,
        array $filters = [],
        string $paginationKey = ''
    ): LengthAwarePaginator|Collection {
        if (!$this->isCacheable()) {
            return $this->queryAll($paginate, $filters, $paginationKey);
        }

        $page = request()->integer('page', 1);
        $perPage = request()->integer(
            'per_page',
            setting($paginationKey, config('app.default_pagination'))
        );

        $cacheKey = $this->getCacheKey('all', $filters, $page, $perPage);

        return Cache::tags($this->getTag())->remember(
            $cacheKey,
            $this->getTtl(),
            fn() => $this->queryAll($paginate, $filters, $paginationKey, $perPage)
        );
    }

    protected function queryAll(
        bool $paginate,
        array $filters,
        string $paginationKey,
        ?int $perPage = null
    ): LengthAwarePaginator|Collection {
        if ($paginate) {
            $perPage ??= request()->integer(
                'per_page',
                setting($paginationKey, config('app.default_pagination'))
            );

            return $this->model
                ->newQuery()
                ->filter($filters)
                ->paginate($perPage);
        }

        return $this->model->all();
    }

    public function find(int $id): ?Model
    {
        if (!$this->isCacheable()) {
            return $this->model->find($id);
        }

        $cacheKey = $this->getCacheKey($id);

        return Cache::tags($this->getTag())->remember($cacheKey, $this->getTtl(), fn() => $this->model->find($id));
    }

    public function create(array $data): Model
    {
        $model = $this->model->create($data);

        if ($this->isCacheable()) {
            Cache::tags($this->getTag())->put($this->getCacheKey($model->getKey()), $model, $this->getTtl());
            $this->clearListCache();
        }

        $this->invalidateSettingCache($model);

        return $model;
    }

    public function update(Model $model, array $data): ?Model
    {
        $model->update($data);

        if ($this->isCacheable()) {
            Cache::tags($this->getTag())->put($this->getCacheKey($model->getKey()), $model, $this->getTtl());
            $this->clearListCache();
        }

        $this->invalidateSettingCache($model);

        return $model;
    }

    public function delete(Model $model): bool
    {
        $deleted = (bool) $model->delete();

        if ($deleted && $this->isCacheable()) {
            Cache::tags($this->getTag())->forget($this->getCacheKey($model->getKey()));
            $this->clearListCache();
        }

        if ($deleted) {
            $this->invalidateSettingCache($model);
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
            Cache::tags($this->getTag())->put($this->getCacheKey($model->getKey()), $model, $this->getTtl());
            $this->clearListCache();
        }

        $this->invalidateSettingCache($model);

        return $model;
    }

    protected function invalidateSettingCache(Model $model): void
    {
        if ($model instanceof \App\Models\Setting && array_key_exists('key', $model->getAttributes())) {
            Cache::forget("setting:{$model->key}");
        }
    }
}

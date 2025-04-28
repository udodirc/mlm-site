<?php
namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\LaravelData\Data;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @template TService
 * @template TModel of \Illuminate\Database\Eloquent\Model
 * @template TResource of \Illuminate\Http\Resources\Json\JsonResource
 * @template TCreateData of \Spatie\LaravelData\Data
 * @template TUpdateData of \Spatie\LaravelData\Data
 */
abstract class BaseController extends Controller
{
    /**
     * @var TService
     */
    protected mixed $service;

    /**
     * @var class-string<TResource>
     */
    protected string $resourceClass;

    public function __construct(mixed $service, string $resourceClass)
    {
        $this->service = $service;
        $this->resourceClass = $resourceClass;
    }

    public function index(): AnonymousResourceCollection|JsonResponse
    {
        return ($this->resourceClass)::collection(
            $this->service->all()
        );
    }

    public function show(Model $model): JsonResource|JsonResponse
    {
        return new $this->resourceClass($model);
    }

    public function store(Data $data): JsonResource|JsonResponse
    {
        $model = $this->service->create($data);

        return new $this->resourceClass($model);
    }

    public function update(Model $model, Data $data): JsonResource|JsonResponse
    {
        $model = $this->service->update($model, $data);

        return new $this->resourceClass($model);
    }

    public function destroy(Model $model): bool
    {
        return $this->service->delete($model);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
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
    protected ?string $resourceClass;

    /**
     * @var class-string<TModel>
     */
    protected string $modelClass;

    /**
     * @var class-string<TCreateData>
     */
    protected ?string $createDataClass;

    /**
     * @var class-string<TUpdateData>
     */
    protected ?string $updateDataClass;

    public function __construct(
        mixed $service,
        ?string $resourceClass,
        string $modelClass,
        ?string $createDataClass,
        ?string $updateDataClass,
    ) {
        $this->service = $service;
        $this->resourceClass = $resourceClass;
        $this->modelClass = $modelClass;
        $this->createDataClass = $createDataClass;
        $this->updateDataClass = $updateDataClass;
    }

    protected function resolveModel(mixed $key): Model
    {
        return $this->modelClass::findOrFail($key);
    }

    public function index(): AnonymousResourceCollection|JsonResponse
    {
        return ($this->resourceClass)::collection(
            $this->service->all()
        );
    }

    public function show(mixed $model): JsonResource
    {
        if (!$model instanceof Model) {
            $model = $this->resolveModel($model);
        }

        return new $this->resourceClass($model);
    }

    public function store(Request $request): JsonResource
    {
        /** @var TCreateData $data */
        $data = $this->createDataClass::from($request);
        $model = $this->service->create($data);

        return new $this->resourceClass($model);
    }

    public function update(mixed $model, Request $request): JsonResource
    {
        if (!$model instanceof Model) {
            $model = $this->resolveModel($model);
        }

        /** @var TUpdateData $data */
        $data = $this->updateDataClass::from($request);
        $model = $this->service->update($model, $data);

        return new $this->resourceClass($model);
    }

    public function destroy(mixed $model): JsonResponse
    {
        if (!$model instanceof Model) {
            $model = $this->resolveModel($model);
        }

        $deleted = $this->service->delete($model);

        return response()->json([
            'success' => $deleted,
        ]);
    }
}

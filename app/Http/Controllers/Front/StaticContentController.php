<?php

namespace App\Http\Controllers\Front;

use App\Data\Admin\StaticContent\StaticContentByNamesData;
use App\Http\Controllers\Controller;

use App\Resource\StaticContentResource;
use App\Services\StaticContentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class StaticContentController extends Controller
{
    private StaticContentService $service;

    public function __construct(StaticContentService $service)
    {
        $this->service = $service;
    }

    public function contentByName(string $name): StaticContentResource|JsonResponse
    {
        $content = $this->service->contentByName($name);

        if (!$content) {
            return response()->json(['message' => 'Content not found'], 404);
        }

        return new StaticContentResource($content);
    }

    public function contentByNames(StaticContentByNamesData $names): JsonResponse|AnonymousResourceCollection
    {
        $contents = $this->service->getContentByNames($names);

        if ($contents->isEmpty()) {
            return response()->json(['message' => 'Content not found'], 404);
        }

        return (StaticContentResource::class)::collection(
            $contents
        );
    }
}

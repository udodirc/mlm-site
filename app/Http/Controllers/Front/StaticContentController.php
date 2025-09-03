<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

use App\Resource\StaticContentResource;
use App\Services\StaticContentService;
use Illuminate\Http\JsonResponse;

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
}

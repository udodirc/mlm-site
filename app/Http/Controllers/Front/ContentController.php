<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Resource\ContentResource;
use App\Services\ContentService;
use Illuminate\Http\JsonResponse;

class ContentController extends Controller
{
    private ContentService $service;

    public function __construct(ContentService $service)
    {
        $this->service = $service;
    }

    public function contentByMenu(string $slug): ContentResource|JsonResponse
    {
        $content = $this->service->contentByMenu($slug);

        if (!$content) {
            return response()->json(['message' => 'Content not found'], 404);
        }

        return new ContentResource($content);
    }
}

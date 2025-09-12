<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Resource\ProjectResource;
use App\Services\ProjectService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProjectController extends Controller
{
    private ProjectService $service;

    public function __construct(ProjectService $service)
    {
        $this->service = $service;
    }

    public function index(): AnonymousResourceCollection
    {
        return ProjectResource::collection(
            $this->service->all()
        );
    }

    public function projectByUrl(string $slug): ProjectResource
    {
        return new ProjectResource(
            $this->service->projectByUrl($slug)
        );
    }
}

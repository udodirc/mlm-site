<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
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
            $this->service->allWithStatus()
        );
    }

    public function projectByUrl(string $slug): ProjectResource
    {
        $project = $this->service->projectByUrl($slug);

        if (!$project){
            abort('404', trans('project.not_found'));
        }

        return new ProjectResource(
            $project
        );
    }
}

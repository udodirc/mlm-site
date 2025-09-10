<?php

namespace App\Observers;

use App\Enums\UploadEnum;
use App\Models\Project;
use App\Services\UploadService;

class ProjectObserver
{
    protected UploadService $imageService;

    public function __construct(UploadService $service)
    {
        $this->imageService = $service;
    }

    /**
     * Handle the Project "created" event.
     */
    public function created(Project $project): void
    {
        $this->handleImages($project);
    }

    /**
     * Handle the Project "updated" event.
     */
    public function updated(Project $project): void
    {
        $this->handleImages($project);
    }

    /**
     * Handle the Project "deleted" event.
     */
    public function deleted(Project $project): void
    {
        $this->imageService->delete(UploadEnum::ProjectsDir->value, $project->id);
    }

    /**
     * Handle the Project "restored" event.
     */
    public function restored(Project $project): void
    {
        //
    }

    /**
     * Handle the Project "force deleted" event.
     */
    public function forceDeleted(Project $project): void
    {
        $this->imageService->delete(UploadEnum::ProjectsDir->value, $project->id);
    }

    protected function handleImages(Project $project): void
    {
        if (request()->hasFile('images')) {
            $this->imageService->delete(UploadEnum::ProjectsDir->value, $project->id);
            $this->imageService->upload(request()->file('images'), UploadEnum::ProjectsDir->value, $project->id);
        }
    }
}

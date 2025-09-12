<?php

namespace App\Observers;

use App\Enums\UploadEnum;
use App\Models\Project;
use App\Services\FileService;

class ProjectObserver
{
    protected FileService $fileService;

    public function __construct(FileService $service)
    {
        $this->fileService = $service;
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
        $this->fileService->deleteFolder(UploadEnum::ProjectsDir->value, $project->id);
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
        $this->fileService->deleteFolder(UploadEnum::ProjectsDir->value, $project->id);
    }

    protected function handleImages(Project $project): void
    {
        if (request()->hasFile('images')) {
            $this->fileService->upload(
                request()->file('images'),
                UploadEnum::ProjectsDir->value,
                $project->id
            );
        }

        if (request()->has('main_page')) {
            $mainIndex = (int) request()->input('main_page');
            $files = $this->fileService->files(UploadEnum::ProjectsDir->value, $project->id);

            if (isset($files[$mainIndex])) {
                $project->main_page = $files[$mainIndex];
                $project->saveQuietly();
            }
        }
    }
}

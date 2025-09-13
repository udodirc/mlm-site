<?php

namespace App\Observers;

use App\Enums\UploadEnum;
use App\Jobs\ProjectFilesUploadJob;
use App\Models\Project;
use App\Services\FileService;

class ProjectObserver
{
    public function created(Project $project): void
    {
        $this->dispatchFilesJob($project);
    }

    public function updated(Project $project): void
    {
        $this->dispatchFilesJob($project);
    }

    public function deleted(Project $project): void
    {
        dispatch(function () use ($project) {
            app(\App\Services\FileService::class)
                ->deleteFolder(UploadEnum::ProjectsDir->value, $project->id);
        });
    }

    public function forceDeleted(Project $project): void
    {
        dispatch(function () use ($project) {
            app(\App\Services\FileService::class)
                ->deleteFolder(UploadEnum::ProjectsDir->value, $project->id);
        });
    }

    protected function dispatchFilesJob(Project $project): void
    {
        $request = request();

        if (!$request->hasFile('images') && !$request->has('main_page')) {
            return;
        }

        $tempPaths = FileService::uploadInTemp($request);

        $mainIndex = $request->input('main_page') !== null
            ? (int) $request->input('main_page')
            : null;

        if (!empty($tempPaths) || $mainIndex !== null) {
            ProjectFilesUploadJob::dispatch($tempPaths, $project, $mainIndex);
        }
    }
}

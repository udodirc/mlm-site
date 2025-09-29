<?php

namespace App\Observers;

use App\Enums\UploadEnum;
use App\Jobs\DeleteFilesJob;
use App\Jobs\ProjectFilesUploadJob;
use App\Models\Project;
use App\Services\FileService;
use Illuminate\Support\Facades\Storage;

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
        DeleteFilesJob::dispatch(
            $project->id,
            [UploadEnum::All->value, UploadEnum::OgImagesDir->value],
            UploadEnum::ProjectsDir
        );
    }

    public function forceDeleted(Project $project): void
    {
        DeleteFilesJob::dispatch(
            $project->id,
            [UploadEnum::All->value, UploadEnum::OgImagesDir->value],
            UploadEnum::ProjectsDir
        );
    }

    protected function dispatchFilesJob(Project $project): void
    {
        $request = request();

        $hasImages = $request->hasFile('images');
        $hasOgImage = $request->hasFile('og_image');
        $mainPageInput = $request->input('main_page');
        $ogImageInput = $request->input('og_image');

        if (!$hasImages && !$hasOgImage && $mainPageInput === null && $ogImageInput === null) {
            return;
        }

        $tempFolder = UploadEnum::UploadsDir->value . '/' .
            UploadEnum::ProjectsDir->value . '/' .
            UploadEnum::TempDir->value;

        $tempPaths = FileService::uploadInTemp($request, $tempFolder);

        if ($hasOgImage) {
            $dir = Storage::disk(config('filesystems.default'))->path(
                UploadEnum::UploadsDir->value . '/' . UploadEnum::ProjectsDir->value . '/' . UploadEnum::OgImagesDir->value . "/{$project->id}"
            );
            FileService::clearDir($dir);
        }

        $mainIndex = null;
        if ($mainPageInput && preg_match('/^img_(\d+)$/', $mainPageInput, $matches)) {
            $mainIndex = (int) $matches[1];
        } else {
            $mainIndex = (int) mb_substr($mainPageInput, 4, 1);
        }

        ProjectFilesUploadJob::dispatch($tempPaths, $project, $mainIndex);

        if (!$hasImages && !$hasOgImage) {
            $updated = false;

            if ($mainPageInput) {
                $project->main_page = $mainPageInput;
                $updated = true;
            }

            if ($ogImageInput) {
                $project->og_image = $ogImageInput;
                $updated = true;
            }

            if ($updated) {
                $project->saveQuietly();
            }
        }
    }
}

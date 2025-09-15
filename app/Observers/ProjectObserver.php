<?php

namespace App\Observers;

use App\Enums\UploadEnum;
use App\Jobs\DeleteProjectFilesJob;
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
        DeleteProjectFilesJob::dispatch($project->id, [UploadEnum::All->value, UploadEnum::OgImagesDir->value]);
    }

    public function forceDeleted(Project $project): void
    {
        DeleteProjectFilesJob::dispatch($project->id, [UploadEnum::All->value, UploadEnum::OgImagesDir->value]);
    }

    protected function dispatchFilesJob(Project $project): void
    {
        $request = request();

        $hasImages = $request->hasFile('images');
        $hasOgImage = $request->hasFile('og_image');
        $mainPageInput = $request->input('main_page');
        $ogImageInput = $request->input('og_image');

        // Если вообще нет данных — выходим
        if (!$hasImages && !$hasOgImage && $mainPageInput === null && $ogImageInput === null) {
            return;
        }

        // Загружаем новые файлы в temp (images + og_image)
        $tempPaths = FileService::uploadInTemp($request);

        if ($hasOgImage) {
            $dir = Storage::disk(config('filesystems.default'))->path(
                UploadEnum::UploadsDir->value . '/' . UploadEnum::ProjectsDir->value . '/' . UploadEnum::OgImagesDir->value . "/{$project->id}"
            );
            FileService::clearDir($dir);
        }

        // Определяем индекс main_page, если указан
        $mainIndex = null;
        if ($mainPageInput && preg_match('/^img_(\d+)$/', $mainPageInput, $matches)) {
            $mainIndex = (int) $matches[1];
        }

        // Диспатчим Job для обработки всех файлов
        ProjectFilesUploadJob::dispatch($tempPaths, $project, $mainIndex);

        // Если нет новых файлов, но приходят строки с main_page или og_image
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

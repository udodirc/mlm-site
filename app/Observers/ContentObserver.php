<?php

namespace App\Observers;

use App\Enums\UploadEnum;
use App\Jobs\ContentFilesUploadJob;
use App\Jobs\DeleteContentFilesJob;
use App\Models\Content;
use App\Services\FileService;
use Illuminate\Support\Facades\Storage;

class ContentObserver
{
    /**
     * Handle the Content "created" event.
     */
    public function created(Content $content): void
    {
        $this->dispatchFilesJob($content);
    }

    /**
     * Handle the Content "updated" event.
     */
    public function updated(Content $content): void
    {
        $this->dispatchFilesJob($content);
    }

    /**
     * Handle the Content "deleted" event.
     */
    public function deleted(Content $content): void
    {
        DeleteContentFilesJob::dispatch($content->id, [UploadEnum::All->value, UploadEnum::OgImagesDir->value]);
    }

    /**
     * Handle the Content "restored" event.
     */
    public function restored(Content $content): void
    {
        //
    }

    /**
     * Handle the Content "force deleted" event.
     */
    public function forceDeleted(Content $content): void
    {
        DeleteContentFilesJob::dispatch($content->id, [UploadEnum::All->value, UploadEnum::OgImagesDir->value]);
    }

    protected function dispatchFilesJob(Content $content): void
    {
        $request = request();

        $hasOgImage = $request->hasFile('og_image');
        $ogImageInput = $request->input('og_image');

        if (!$hasOgImage && $ogImageInput === null) {
            return;
        }

        $tempFolder = UploadEnum::UploadsDir->value . '/' .
            UploadEnum::ContentDir->value . '/' .
            UploadEnum::TempDir->value;

        $tempPaths = FileService::uploadInTemp($request, $tempFolder);

        if ($hasOgImage) {
            $dir = Storage::disk(config('filesystems.default'))->path(
                UploadEnum::UploadsDir->value . '/' . UploadEnum::ContentDir->value . '/' . UploadEnum::OgImagesDir->value . "/{$content->id}"
            );
            FileService::clearDir($dir);
        }

        ContentFilesUploadJob::dispatch($tempPaths, $content);

        if (!$hasOgImage) {
            $updated = false;

            if ($ogImageInput) {
                $content->og_image = $ogImageInput;
                $updated = true;
            }

            if ($updated) {
                $content->saveQuietly();
            }
        }
    }
}

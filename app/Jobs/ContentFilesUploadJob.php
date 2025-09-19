<?php

namespace App\Jobs;

use AllowDynamicProperties;
use App\Enums\UploadEnum;
use App\Models\Content;
use App\Services\FileService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

#[AllowDynamicProperties]
class ContentFilesUploadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $filePaths;
    public Content $content;

    public function __construct(array $filePaths, Content $content)
    {
        $this->filePaths = $filePaths;
        $this->content = $content;
    }

    public function handle(): void
    {
        $dirsMap = [
            'og_image' => UploadEnum::ContentDir->value . '/' . UploadEnum::OgImagesDir->value,
        ];

        $uploaded = FileService::uploadFromTemp($this->filePaths, $this->content->id, $dirsMap);

        $tempDir = Storage::disk(config('filesystems.default'))->path(
            UploadEnum::UploadsDir->value
            . '/' . UploadEnum::ContentDir->value
            . '/' . UploadEnum::TempDir->value
        );

        FileService::clearDir($tempDir);

        if (!empty($uploaded['og_image'])) {
            $this->content->og_image = $uploaded['og_image'];
        }

        $this->content->saveQuietly();
    }
}

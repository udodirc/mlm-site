<?php

namespace App\Jobs;

use App\Enums\UploadEnum;
use App\Services\FileService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteProjectFilesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $projectId;

    public function __construct(int $projectId)
    {
        $this->projectId = $projectId;
    }

    public function handle(FileService $fileService)
    {
        $fileService->deleteFolder(UploadEnum::ProjectsDir->value, $this->projectId);
    }
}

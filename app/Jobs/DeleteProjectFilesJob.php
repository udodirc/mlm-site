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
    public array $dir;

    public function __construct(int $projectId, array $dir)
    {
        $this->projectId = $projectId;
        $this->dir = $dir;
    }

    public function handle(FileService $fileService)
    {
        foreach ($this->dir as $dir) {
            $fileService->deleteFolder(UploadEnum::ProjectsDir->value, $this->projectId, $dir);
        }
    }
}

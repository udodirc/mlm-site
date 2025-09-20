<?php

namespace App\Jobs;

use App\Enums\UploadEnum;
use App\Services\FileService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DeleteFilesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $entityId;
    public array $dir;
    public UploadEnum $baseDir;

    public function __construct(int $entityId, array $dir, UploadEnum $baseDir)
    {
        $this->entityId = $entityId;
        $this->dir = $dir;
        $this->baseDir = $baseDir;
    }

    public function handle(FileService $fileService): void
    {
        foreach ($this->dir as $dir) {
           $fileService->deleteFolder($this->baseDir->value, $this->entityId, $dir);
        }
    }
}

<?php

namespace App\Jobs;

use App\Enums\UploadEnum;
use App\Services\FileService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteContentFilesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $conteId;
    public array $dir;

    public function __construct(int $conteId, array $dir)
    {
        $this->conteId = $conteId;
        $this->dir = $dir;
    }

    public function handle(FileService $fileService)
    {
        foreach ($this->dir as $dir) {
            $fileService->deleteFolder(UploadEnum::ContentDir->value, $this->conteId, $dir);
        }
    }
}

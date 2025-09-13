<?php

namespace App\Jobs;

use AllowDynamicProperties;
use App\Services\FileService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Project;
use Illuminate\Support\Facades\Log;

#[AllowDynamicProperties] class ProjectFilesUploadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $filePaths;
    public Project $project;
    public ?int $mainIndex;

    public function __construct(array $filePaths, Project $project, ?int $mainIndex = null)
    {
        $this->filePaths = $filePaths;
        $this->project = $project;
        $this->mainIndex = $mainIndex;
    }

    public function handle(): void
    {
        $uploadedFiles = FileService::uploadFromTemp($this->filePaths, $this->project->id);
        FileService::clearTempDir();

        if ($this->mainIndex !== null) {
            if ($this->project) {
                $this->project->main_page = $uploadedFiles[$this->mainIndex];
                $this->project->saveQuietly();
            }
        }
    }
}

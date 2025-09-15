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

#[AllowDynamicProperties]
class ProjectFilesUploadJob implements ShouldQueue
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
        // Загружаем все файлы из temp в проект
        $uploaded = FileService::uploadFromTemp($this->filePaths, $this->project->id);

        FileService::clearTempDir();

        // --- images ---
        if (!empty($uploaded['images'])) {
            //$this->project->images = $uploaded['images'];

            if ($this->mainIndex !== null && isset($uploaded['images'][$this->mainIndex])) {
                $this->project->main_page = $uploaded['images'][$this->mainIndex];
            }
        }

        // --- og_image ---
        if (!empty($uploaded['og_image'])) {
            $this->project->og_image = $uploaded['og_image'];
        }

        $this->project->saveQuietly();
    }
}

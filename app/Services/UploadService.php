<?php

namespace App\Services;

use App\Enums\UploadEnum;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UploadService
{
    public function upload(array|UploadedFile $files, string $entity, int|string $entityId): void
    {
        $files = is_array($files) ? $files : [$files];

        foreach ($files as $file) {
            $file->store(UploadEnum::UploadsDir->value."/{$entity}/{$entityId}", 'public');
        }
    }

    public function delete(string $entity, int|string $entityId): void
    {
        $folder = UploadEnum::UploadsDir->value."/{$entity}/{$entityId}";

        if (Storage::exists($folder)) {
            Storage::deleteDirectory($folder);
        }
    }

    public static function files(string $entity, int|string $entityId): array
    {
        $folder = storage_path("app/public/".UploadEnum::UploadsDir->value."/{$entity}/{$entityId}");

        if (!is_dir($folder)) {
            return [];
        }

        $files = scandir($folder);

        return array_values(array_filter($files, fn($file) => !in_array($file, ['.', '..'])));
    }
}

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
            $file->store(UploadEnum::UploadsDir->value."/{$entity}/{$entityId}");
        }
    }

    public function delete(string $entity, int|string $entityId): void
    {
        $folder = UploadEnum::UploadsDir->value."/{$entity}/{$entityId}";

        if (Storage::exists($folder)) {
            Storage::deleteDirectory($folder);
        }
    }
}

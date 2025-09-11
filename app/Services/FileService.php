<?php

namespace App\Services;

use App\Enums\UploadEnum;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class FileService
{
    /**
     * Загрузка файлов
     */
    public function upload(array|UploadedFile $files, string $entity, int|string $entityId): void
    {
        $files = is_array($files) ? $files : [$files];

        foreach ($files as $file) {
            $file->store($this->getFolderPath($entity, $entityId), 'public');
        }
    }

    /**
     * Удаление всей папки
     */
    public function deleteFolder(string $entity, int|string $entityId): void
    {
        $folder = $this->getFolderPath($entity, $entityId);

        if (Storage::disk('public')->exists($folder)) {
            Storage::disk('public')->deleteDirectory($folder);
        }
    }

    /**
     * Получить список файлов
     */
    public function files(string $entity, int|string $entityId): array
    {
        $folder = $this->getFolderPath($entity, $entityId);

        if (!Storage::disk('public')->exists($folder)) {
            return [];
        }

        $files = Storage::disk('public')->files($folder);

        return array_map(fn($file) => basename($file), $files);
    }

    /**
     * Удаление одного файла
     */
    public function deleteFile(string $entity, int|string $entityId, string $filename): void
    {
        $filePath = $this->getFolderPath($entity, $entityId) . '/' . $filename;

        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        } else {
            abort(Response::HTTP_NOT_FOUND, 'Файл не найден');
        }
    }

    /**
     * Получить публичный URL папки
     */
    public function folderUrl(string $entity, int|string $entityId): string
    {
        $folder = $this->getFolderPath($entity, $entityId);
        return asset("storage/{$folder}");
    }

    /**
     * Получение пути к папке
     */
    private function getFolderPath(string $entity, int|string $entityId): string
    {
        $base = UploadEnum::UploadsDir->value;

        $entityDir = match ($entity) {
            'projects' => UploadEnum::ProjectsDir->value,
            default => $entity
        };

        return "{$base}/{$entityDir}/{$entityId}";
    }
}

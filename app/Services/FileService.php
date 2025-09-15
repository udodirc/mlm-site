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
            $file->store($this->getFolderPath($entity, $entityId), config('filesystems.default'));
        }
    }

    /**
     * Удаление всей папки
     */
    public function deleteFolder(string $entity, int|string $entityId): void
    {
        $folder = $this->getFolderPath($entity, $entityId);

        if (Storage::disk(config('filesystems.default'))->exists($folder)) {
            Storage::disk(config('filesystems.default'))->deleteDirectory($folder);
        }
    }

    /**
     * Получить список файлов
     */
    public function files(string $entity, int|string $entityId): array
    {
        $folder = $this->getFolderPath($entity, $entityId);

        if (!Storage::disk(config('filesystems.default'))->exists($folder)) {
            return [];
        }

        $files = Storage::disk(config('filesystems.default'))->files($folder);

        return array_map(fn($file) => basename($file), $files);
    }

    /**
     * Удаление одного файла
     */
    public function deleteFile(string $entity, int|string $entityId, string $filename): void
    {
        $filePath = $this->getFolderPath($entity, $entityId) . '/' . $filename;

        if (Storage::disk(config('filesystems.default'))->exists($filePath)) {
            Storage::disk(config('filesystems.default'))->delete($filePath);
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

    public static function uploadInTemp($request): array
    {
        $tempPaths = [
            'images' => [],
            'og_image' => null,
        ];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $tempPaths['images'][] = $file->store(
                    UploadEnum::UploadsDir->value . '/' . UploadEnum::ProjectsDir->value . '/' . UploadEnum::TempDir->value,
                    config('filesystems.default')
                );
            }
        }

        if ($request->hasFile('og_image')) {
            $tempPaths['og_image'] = $request->file('og_image')->store(
                UploadEnum::UploadsDir->value . '/' . UploadEnum::ProjectsDir->value . '/' . UploadEnum::TempDir->value,
                config('filesystems.default')
            );
        }

        return $tempPaths;
    }

    public static function uploadFromTemp(array $filePaths, int $projectId): array
    {
        $uploadedFiles = [
            'images' => [],
            'og_image' => null,
        ];

        // images
        if (!empty($filePaths['images'])) {
            foreach ($filePaths['images'] as $path) {
                $finalName = self::moveFromTemp($path, $projectId);
                if ($finalName) {
                    $uploadedFiles['images'][] = $finalName;
                }
            }
        }

        // og_image
        if (!empty($filePaths['og_image'])) {
            $finalName = self::moveFromTemp($filePaths['og_image'], $projectId);
            if ($finalName) {
                $uploadedFiles['og_image'] = $finalName;
            }
        }

        return $uploadedFiles;
    }

    private static function moveFromTemp(string $path, int $projectId): ?string
    {
        $fullPath = Storage::disk(config('filesystems.default'))->path($path);

        if (!file_exists($fullPath)) {
            return null;
        }

        $targetDir = Storage::disk(config('filesystems.default'))->path(
            UploadEnum::UploadsDir->value . '/' . UploadEnum::ProjectsDir->value . "/{$projectId}"
        );

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $finalName = basename($fullPath);
        copy($fullPath, $targetDir . '/' . $finalName);

        Storage::disk(config('filesystems.default'))->delete($path);

        return $finalName;
    }

    public static function clearTempDir(): void
    {
        $tempDir = Storage::disk(config('filesystems.default'))->path(UploadEnum::UploadsDir->value . '/' . UploadEnum::ProjectsDir->value . "/".UploadEnum::TempDir->value);

        if (is_dir($tempDir)) {
            $files = glob($tempDir . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }
    }
}

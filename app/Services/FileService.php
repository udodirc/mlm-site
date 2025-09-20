<?php

namespace App\Services;

use App\Enums\UploadEnum;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class FileService
{
    /**
     * Диск по умолчанию (взятый из config)
     */
    private static function disk()
    {
        return Storage::disk(config('filesystems.default'));
    }

    /**
     * Загрузка массива/файла в папку entity/{entityId}[/subDir]
     *
     * @param array|UploadedFile $files
     * @param string $entity
     * @param int|string $entityId
     * @param string|null $subDir подпапка внутри uploads (например UploadEnum::All->value или UploadEnum::OgImagesDir->value)
     * @return void
     */
    public static function upload(array|UploadedFile $files, string $entity, int|string $entityId, ?string $subDir = null): void
    {
        $files = is_array($files) ? $files : [$files];

        foreach ($files as $file) {
            /** @var UploadedFile $file */
            $folder = self::getFolderPath($entity, $entityId, $subDir);
            $file->store($folder, config('filesystems.default'));
        }
    }

    /**
     * Удаление всей папки (entity/entityId[/subDir])
     */
    public static function deleteFolder(string $entity, int|string $entityId, ?string $subDir = null): void
    {
        $folder = self::getFolderPath($entity, $entityId, $subDir);

        if (self::disk()->exists($folder)) {
            self::disk()->deleteDirectory($folder);
        }
    }

    /**
     * Получить список файлов в папке (basename)
     */
    public static function files(string $entity, int|string $entityId, ?string $subDir = null): array
    {
        $folder = self::getFolderPath($entity, $entityId, $subDir);

        if (!self::disk()->exists($folder)) {
            return [];
        }

        $files = self::disk()->files($folder);

        return array_map(fn($file) => basename($file), $files);
    }

    /**
     * Удаление одного файла в указанной папке
     */
    public static function deleteFile(string $entity, int|string $entityId, string $filename, ?string $subDir = null): void
    {
        $filePath = self::getFolderPath($entity, $entityId, $subDir) . '/' . $filename;

        if (self::disk()->exists($filePath)) {
            self::disk()->delete($filePath);
        } else {
            abort(Response::HTTP_NOT_FOUND, 'Файл не найден');
        }
    }

    /**
     * Получить публичный URL папки
     */
    public static function folderUrl(string $entity, int|string $entityId, ?string $subDir = null): string
    {
        $folder = self::getFolderPath($entity, $entityId, $subDir);
        return asset("storage/{$folder}");
    }

    /**
     * Построить путь к папке в storage (relative path относительно storage/app)
     *
     * Формат: uploads/{subDir}/{entityDir}/{entityId}
     * Если subDir не передан — используется UploadEnum::All
     */
    public static function getFolderPath(string $entity, int|string $entityId, ?string $subDir = null): string
    {
        $base = UploadEnum::UploadsDir->value;
        $entityDir = match ($entity) {
            'projects' => UploadEnum::ProjectsDir->value,
            default => $entity
        };

        $subDir = $subDir ?? UploadEnum::All->value;

        return "{$base}/{$entityDir}/{$subDir}/{$entityId}";
    }

    /**
     * Загрузить временно пришедшие файлы (из Request) в папку uploads/projects/temp
     * Возвращает массив путей (относительных для Storage), например:
     * [
     *   'images' => ['uploads/projects/temp/xxx.jpg', ...],
     *   'og_image' => 'uploads/projects/temp/og.jpg' | null,
     * ]
     */
    public static function uploadInTemp(Request $request, string $tempFolder): array
    {
        $tempPaths = [
            'images' => [],
            'og_image' => null,
        ];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                /** @var UploadedFile $file */
                $tempPaths['images'][] = $file->store($tempFolder, config('filesystems.default'));
            }
        }

        if ($request->hasFile('og_image')) {
            $tempPaths['og_image'] = $request->file('og_image')->store($tempFolder, config('filesystems.default'));
        }

        return $tempPaths;
    }

    public static function uploadFromTemp(array $filePaths, int $projectId, array $dirsMap): array
    {
        $uploadedFiles = [];

        foreach ($dirsMap as $key => $subDir) {
            if (empty($filePaths[$key])) {
                $uploadedFiles[$key] = is_array($filePaths[$key] ?? null) ? [] : null;
                continue;
            }

            $paths = is_array($filePaths[$key]) ? $filePaths[$key] : [$filePaths[$key]];

            foreach ($paths as $path) {
                $finalName = self::moveFromTemp($path, $projectId, $subDir);
                if ($finalName) {
                    if ($key === 'images') {
                        $uploadedFiles[$key][] = $finalName;
                    } else {
                        $uploadedFiles[$key] = $finalName;
                    }
                }
            }
        }

        return $uploadedFiles;
    }

    /**
     * Переместить один файл из temp (путь относительный, как вернул Storage::put) в целевую папку
     *
     * @param string $path относительный путь внутри storage (например 'uploads/projects/temp/xxx.jpg')
     * @param int $projectId
     * @param string|null $subDir целевая подпапка внутри uploads (например 'all' или 'og_images')
     * @return string|null итоговое имя файла (basename) или null, если не найден
     */
    private static function moveFromTemp(string $path, int $projectId, ?string $subDir = null): ?string
    {
        $fullPath = self::disk()->path($path);

        if (!file_exists($fullPath)) {
            return null;
        }

        //$subDir = $subDir ?? UploadEnum::All->value;

        $targetDir = self::disk()->path(
            UploadEnum::UploadsDir->value . '/' . $subDir . "/{$projectId}"
        );

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $finalName = basename($fullPath);
        copy($fullPath, $targetDir . '/' . $finalName);

        // удалить временный файл (относительный путь)
        if (self::disk()->exists($path)) {
            self::disk()->delete($path);
        } else {
            // На всякий случай — если диск не управляет этим путем, пробуем unlink напрямую
            if (file_exists($fullPath)) {
                @unlink($fullPath);
            }
        }

        return $finalName;
    }

    /**
     * Очистить временную директорию (полный путь в файловой системе)
     */
    public static function clearDir(string $dir): void
    {
        if (is_dir($dir)) {
            $files = glob(rtrim($dir, '/') . '/*');

            foreach ($files as $file) {
                if (is_file($file)) {
                    @unlink($file);
                }
            }
        }
    }

    /**
     * Очистить стандартную temp папку uploads/projects/temp
     */
    public static function clearTempDir(): void
    {
        $tempDir = self::disk()->path(
            UploadEnum::UploadsDir->value . '/' . UploadEnum::ProjectsDir->value . '/' . UploadEnum::TempDir->value
        );

        self::clearDir($tempDir);
    }
}

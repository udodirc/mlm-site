<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\FileService;
use Symfony\Component\HttpKernel\Exception\HttpException;

class FileController extends Controller
{
    public function __construct(private readonly FileService $fileService)
    {
    }

    /**
     * Список файлов в папке
     */
    public function index(string $entity, int|string $entityId): JsonResponse
    {
        $files = $this->fileService->files($entity, $entityId);
        $folderUrl = $this->fileService->folderUrl($entity, $entityId);

        return response()->json([
            'files' => $files,
            'folder_url' => $folderUrl,
        ]);
    }

    /**
     * Удаление конкретного файла
     */
    public function destroy(Request $request, string $entity, int|string $entityId): JsonResponse
    {
        $request->validate([
            'filename' => 'required|string',
        ]);

        try {
            $this->fileService->deleteFile($entity, $entityId, $request->input('filename'));
            return response()->json(['message' => 'Файл удален']);
        } catch (HttpException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getStatusCode());
        }
    }
}

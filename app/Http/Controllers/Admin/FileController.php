<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Enums\UploadEnum;
use Symfony\Component\HttpFoundation\Response;

class FileController extends Controller
{
    public function index(string $entity, int|string $entityId): JsonResponse
    {
        $folder = $this->getFolderPath($entity, $entityId);

        if (!Storage::disk('public')->exists($folder)) {
            return response()->json(['files' => [], 'folder_url' => asset("storage/{$folder}")]);
        }

        $files = Storage::disk('public')->files($folder);

        return response()->json([
            'files' => array_map(fn($file) => basename($file), $files),
            'folder_url' => asset("storage/{$folder}"),
        ]);
    }

    public function destroy(Request $request, string $entity, int|string $entityId): JsonResponse
    {
        $request->validate([
            'filename' => 'required|string',
        ]);

        $folder = $this->getFolderPath($entity, $entityId);
        $filePath = "{$folder}/{$request->input('filename')}";

        if (!Storage::disk('public')->exists($filePath)) {
            return response()->json(['message' => 'Файл не найден'], Response::HTTP_NOT_FOUND);
        }

        Storage::disk('public')->delete($filePath);

        return response()->json(['message' => 'Файл удален']);
    }

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

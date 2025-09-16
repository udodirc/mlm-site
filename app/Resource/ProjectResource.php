<?php

namespace App\Resource;

use App\Enums\UploadEnum;
use App\Models\Project;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Project
 */
class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $fileService = app(FileService::class);
        $entity = UploadEnum::ProjectsDir->value;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'content' => $this->content,
            'url' => $this->url,
            'status' => $this->status,
            'title' => $this->title,
            'meta_description' => $this->meta_description,
            'meta_keywords' => $this->meta_keywords,
            'og_title' => $this->og_title,
            'og_description' => $this->og_description,
            'og_image' => $this->og_image,
            'og_type' => $this->og_type,
            'og_url' => $this->og_url,
            'canonical_url' => $this->canonical_url,
            'robots' => $this->robots,
            'image_url' =>  asset("storage/" .
                UploadEnum::UploadsDir->value .
                "/{$entity}/".
                UploadEnum::All->value.
                "/{$this->id}"),
            'image_og_url' =>  asset("storage/" .
                UploadEnum::UploadsDir->value .
                "/{$entity}/".
                UploadEnum::OgImagesDir->value.
                "/{$this->id}"),
            'images' => $fileService->files($entity, $this->id),
            'image_dir' => $entity,
            'image_all_dir' => UploadEnum::All->value,
            'image_og_dir' => UploadEnum::OgImagesDir->value,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}

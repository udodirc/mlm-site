<?php

namespace App\Resource;

use App\Enums\UploadEnum;
use App\Models\Project;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
/**
 * @mixin Project
 */
class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
       return [
            'id' => $this->id,
            'name' => $this->name,
            'content' => $this->content,
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
            'image_url' => asset("storage/" . UploadEnum::UploadsDir->value . "/" . UploadEnum::ProjectsDir->value . "/$this->id"),
            'images' => UploadService::files(UploadEnum::ProjectsDir->value, $this->id),
            'image_dir' => UploadEnum::ProjectsDir->value,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}

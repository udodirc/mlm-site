<?php

namespace App\Resource;

use App\Enums\UploadEnum;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Content
 */
class ContentResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $entity = UploadEnum::ContentDir->value;

        return [
            'id' => $this->id,
            'menu_id' => $this->menu_id,
            'menu_name' => $this->menu?->name,
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
            'image_og_url' =>  asset("storage/" .
                UploadEnum::UploadsDir->value .
                "/{$entity}/".
                UploadEnum::OgImagesDir->value.
                "/{$this->id}"),
            'image_dir' => $entity,
            'image_og_dir' => UploadEnum::OgImagesDir->value,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}

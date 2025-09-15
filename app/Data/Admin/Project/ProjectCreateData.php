<?php

namespace App\Data\Admin\Project;

use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Illuminate\Http\UploadedFile;

class ProjectCreateData extends Data
{
    public string $name;
    public string $content;
    public string $url;
    public string|Optional|null $title;
    public string|Optional|null $meta_description;
    public string|Optional|null $meta_keywords;
    public string|Optional|null $og_title;
    public string|Optional|null $og_description;
    public ?string $og_image;
    public string|Optional|null $og_url;
    public string $og_type;
    public string|Optional|null $canonical_url;
    public string $robots;
    public string|array|Optional|null $images;
    public ?string $main_page;

    public function __construct(
        string $name,
        string $content,
        string $url,
        ?string $title,
        ?string $meta_description,
        ?string $meta_keywords,
        ?string $og_title,
        ?string $og_description,
        ?string $og_image = null,
        ?string $og_url,
        string $og_type = 'website',
        ?string $canonical_url,
        string $robots = 'index, follow',
        string|array|Optional|null $images = null,
        ?string $main_page
    ){
        $this->name = $name;
        $this->content = $content;
        $this->url = $url;
        $this->title = $title;
        $this->meta_description = $meta_description;
        $this->meta_keywords = $meta_keywords;
        $this->og_title = $og_title;
        $this->og_description = $og_description;
        $this->og_image = $og_image;
        $this->og_url = $og_url;
        $this->og_type = $og_type;
        $this->canonical_url = $canonical_url;
        $this->robots = $robots;
        $this->images = empty($images) || $images === '' ? null : (is_array($images) ? $images : [$images]);
        $this->main_page = $main_page;
    }

    public static function rules(...$args): array
    {
        return [
            'name' => [
                new Required(),
                new StringType(),
                new Max(100)
            ],
            'content' => [
                new Required(),
                new StringType(),
            ],
            'url' => [
                new Required(),
                new StringType(),
                new Max(100)
            ],
            'title' => [
                new Nullable(),
                new StringType(),
            ],
            'meta_description' => [
                new Nullable(),
                new StringType(),
            ],
            'meta_keywords' => [
                new Nullable(),
                new StringType(),
            ],
            'og_title' => [
                new Nullable(),
                new StringType(),
            ],
            'og_description' => [
                new Nullable(),
                new StringType(),
            ],
            'og_image' => [
                new Nullable(),
                function ($attribute, $value, $fail) {
                    if ($value instanceof UploadedFile) {
                        if (!in_array($value->getClientMimeType(), ['image/jpeg', 'image/png', 'image/webp'])) {
                            $fail("The {$attribute} must be a file of type: jpeg, png, webp.");
                        }
                        if ($value->getSize() > 2 * 1024 * 1024) { // 2MB
                            $fail("The {$attribute} file is too large (max 2MB).");
                        }
                    }
                },
            ],
            'og_url' => [
                new Nullable(),
                new StringType(),
            ],
            'og_type' => [
                new Required(),
                new StringType(),
            ],
            'canonical_url' => [
                new Nullable(),
                new StringType(),
            ],
            'robots' => [
                new Required(),
                new StringType(),
            ],
            'images' => [
                new Nullable(),
                function ($attribute, $value, $fail) {
                    if (is_array($value)) {
                        foreach ($value as $file) {
                            if (!($file instanceof UploadedFile)) {
                                continue;
                            }
                            if (!in_array($file->getClientMimeType(), ['image/jpeg', 'image/png', 'image/webp'])) {
                                $fail("The {$attribute} must be a file of type: jpeg, png, webp.");
                            }
                            if ($file->getSize() > 2 * 1024 * 1024) { // 2MB
                                $fail("The {$attribute} file is too large (max 2MB).");
                            }
                        }
                    }
                },
            ],
            'main_page' => [
                new Nullable(),
                new StringType(),
            ],
        ];
    }
}

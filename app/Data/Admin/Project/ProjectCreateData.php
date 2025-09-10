<?php

namespace App\Data\Admin\Project;

use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class ProjectCreateData extends Data
{
    public string $name;
    public string $content;
    public string|Optional|null $title;
    public string|Optional|null $meta_description;
    public string|Optional|null $meta_keywords;
    public string|Optional|null $og_title;
    public string|Optional|null $og_description;
    public string|Optional|null $og_image;
    public string|Optional|null $og_url;
    public string $og_type;
    public string|Optional|null $canonical_url;
    public string $robots;

    public function __construct(
        string $name,
        string $content,
        ?string $title,
        ?string $meta_description,
        ?string $meta_keywords,
        ?string $og_title,
        ?string $og_description,
        ?string $og_image,
        ?string $og_url,
        string $og_type = 'website',
        ?string $canonical_url,
        string $robots = 'index, follow',
    ){
        $this->name = $name;
        $this->content = $content;
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
                new StringType(),
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
            ]
        ];
    }
}

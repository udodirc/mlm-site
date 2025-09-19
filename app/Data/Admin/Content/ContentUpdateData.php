<?php

namespace App\Data\Admin\Content;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class ContentUpdateData extends Data
{
    public string $content;
    public bool|Optional|null $status;
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

    public function __construct(
        string $content,
        bool|Optional|null $status = null,
        ?string $title = null,
        ?string $meta_description = null,
        ?string $meta_keywords = null,
        ?string $og_title = null,
        ?string $og_description = null,
        ?string $og_image = null,
        ?string $og_url = null,
        string $og_type = 'website',
        ?string $canonical_url = null,
        string $robots = 'index, follow',
    ) {
        $this->content = $content;
        $this->status = $status;
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
            'content' => [
                new StringType(),
                new Required(),
            ],
            'status' => [
                new Nullable(),
                new BooleanType(),
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
            ]
        ];
    }
}

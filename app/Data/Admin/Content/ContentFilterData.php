<?php

namespace App\Data\Admin\Content;

use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class ContentFilterData extends Data
{
    public int|Optional|null $menu_id;

    public bool|Optional|null $status;

    public string|Optional|null $created_from;

    public string|Optional|null $created_to;

    public function __construct(
        int|Optional|null $menu_id = null,
        bool|Optional|null $status = null,
        string|Optional|null $created_from = null,
        string|Optional|null $created_to = null,
    ) {
        $this->menu_id = $menu_id ?? new Optional();
        $this->status = $status ?? new Nullable();
        $this->created_from = $created_from ?? new Optional();
        $this->created_to = $created_to ?? new Optional();
    }

    public static function rules(): array
    {
        return [
            'menu_id' => [
                new IntegerType(),
                new Nullable(),
            ],
            'status' => [
                new Nullable(),
                new BooleanType(),
            ],
            'created_from' => [
                new StringType(),
                new Nullable(),
            ],
            'created_to' => [
                new StringType(),
                new Nullable(),
            ],
        ];
    }
}

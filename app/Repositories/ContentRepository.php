<?php

namespace App\Repositories;

use App\Models\Content;
use App\Repositories\Contracts\ContentRepositoryInterface;

class ContentRepository extends AbstractRepository implements ContentRepositoryInterface
{
    public function __construct(Content $content)
    {
        parent::__construct($content);
    }
}

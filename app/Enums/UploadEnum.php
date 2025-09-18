<?php

namespace App\Enums;

enum UploadEnum: string
{
    case UploadsDir = 'uploads';

    case TempDir = 'temp';

    case All = 'all';

    case OgImagesDir = 'og_images';

    case ProjectsDir = 'projects';

    case ContentDir = 'content';
}

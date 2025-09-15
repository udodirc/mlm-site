<?php

namespace App\Enums;

enum UploadEnum: string
{
    case UploadsDir = 'uploads';

    case TempDir = 'temp';

    case OgImagesDir = 'og_images';

    case ProjectsDir = 'projects';
}

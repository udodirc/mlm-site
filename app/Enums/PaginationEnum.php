<?php

namespace App\Enums;

enum PaginationEnum: string
{
    case User = 'per_page_users';

    case Role = 'per_page_roles';

    case Menu = 'per_page_menus';

    case Content = 'per_page_content';
}


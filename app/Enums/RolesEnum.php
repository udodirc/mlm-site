<?php

namespace App\Enums;

enum RolesEnum: string
{
    case Guard = 'api';

    case Admin = 'admin';

    case Manager = 'manager';
}

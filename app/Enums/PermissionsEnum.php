<?php

namespace App\Enums;

enum PermissionsEnum: string
{
    case PermissionView = 'view-permissions';
    case UserCreate = 'create-users';
    case UserUpdate = 'update-users';
    case UserView = 'view-users';
    case UserDelete = 'delete-users';

    case RoleCreate = 'create-roles';
    case RoleUpdate = 'update-roles';
    case RoleView = 'view-roles';
    case RoleDelete = 'delete-roles';

    case MenuCreate = 'create-menu';
    case MenuUpdate = 'update-menu';
    case MenuView = 'view-menu';
    case MenuDelete = 'delete-menu';
}

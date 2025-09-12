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

    case ContentCreate = 'create-content';
    case ContentUpdate = 'update-content';
    case ContentView = 'view-content';
    case ContentDelete = 'delete-content';

    case StaticContentCreate = 'create-static-content';
    case StaticContentUpdate = 'update-static-content';
    case StaticContentView = 'view-static-content';
    case StaticContentDelete = 'delete-static-content';

    case SettingsCreate = 'create-settings';
    case SettingsUpdate = 'update-settings';
    case SettingsView = 'view-settings';
    case SettingsDelete = 'delete-settings';

    case ProjectCreate = 'create-project';
    case ProjectUpdate = 'update-project';
    case ProjectView = 'view-project';
    case ProjectDelete = 'delete-project';
}

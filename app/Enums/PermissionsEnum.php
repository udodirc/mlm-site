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

    case PartnerCreate = 'create-partners';
    case PartnerUpdate = 'update-partners';
    case PartnerView = 'view-partners';
    case PartnerDelete = 'delete-partners';

    case PackagesCreate = 'create-packages';
    case PackagesUpdate = 'update-packages';
    case PackagesView = 'view-packages';
    case PackagesDelete = 'delete-packages';

    case TransactionsUpdate = 'update-transactions';
    case TransactionsView = 'view-transactions';

    case PartnerBalanceUpdate = 'update-balance';

    case PartnerPackageDelete = 'delete-partner-package';

    case ViewAdminLogs = 'view-admin-logs';

    case ViewClientLogs = 'view-client-logs';
    case ViewStats = 'view-statistics';

    case ViewClientProfile = 'view-client-profile';
}

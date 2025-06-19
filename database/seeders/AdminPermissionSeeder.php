<?php

namespace Database\Seeders;

use App\Enums\RolesEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Enums\PermissionsEnum;

class AdminPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = PermissionsEnum::cases();
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission->value,
                'guard_name' => 'api',
            ]);
        }

        $adminRole = Role::where('name', RolesEnum::Admin)->first();

        if (!$adminRole) {
            $this->command->error(__('messages.no_roles'));
            return;
        }

        $adminRole->syncPermissions($permissions);
        $adminUser = User::find(1);

        if ($adminUser) {
            $adminUser->assignRole('admin');
        } else {
            $password = Str::random(12);
            $adminUser = User::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt($password),
            ]);
            $adminUser->assignRole(RolesEnum::Admin);
        }

        $this->command->info(__('messages.admin_seeder').' '.$password.'.');
    }
}

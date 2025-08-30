<?php

namespace Database\Seeders;

use App\Enums\RolesEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = array_filter(RolesEnum::cases(), fn($role) => $role !== RolesEnum::Guard);

        foreach ($permissions as $permission) {
            Role::firstOrCreate(['name' => $permission->value]);
        }
    }
}

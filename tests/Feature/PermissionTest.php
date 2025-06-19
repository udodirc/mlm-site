<?php

namespace Tests\Feature;

use App\Enums\PermissionsEnum;
use App\Enums\RolesEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function testCreatePermissions(): void
    {
        // Создаём permissions из enum, если ещё не созданы
        foreach (PermissionsEnum::cases() as $permissionEnum) {
            Permission::firstOrCreate([
                'name' => $permissionEnum->value,
                'guard_name' => RolesEnum::Guard->value,
            ]);
        }

        $role = Role::create([
            'name' => RolesEnum::Admin->value,
            'guard_name' => RolesEnum::Guard->value,
        ]);

        $role->givePermissionTo(PermissionsEnum::RoleCreate->value); // используем уже существующее

        $user = User::factory()->create();
        $user->assignRole($role);

        $this->actingAs($user, RolesEnum::Guard->value);

        $response = $this->get('/');
        $response->assertStatus(200);
    }
}

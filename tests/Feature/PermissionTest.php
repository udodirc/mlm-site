<?php

namespace Tests\Feature;

use App\Enums\PermissionsEnum;
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
                'guard_name' => 'api',
            ]);
        }

        $role = Role::create([
            'name' => 'admin',
            'guard_name' => 'api',
        ]);

        $role->givePermissionTo('create-roles'); // используем уже существующее

        $user = User::factory()->create();
        $user->assignRole($role);

        $this->actingAs($user, 'api');

        $response = $this->get('/');
        $response->assertStatus(200);
    }
}

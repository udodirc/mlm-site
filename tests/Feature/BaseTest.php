<?php

namespace Tests\Feature;

use App\Enums\RolesEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

abstract class BaseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    protected function auth(
        string $permission
    ): User
    {
        Permission::create([
            'name' => $permission,
            'guard_name' => RolesEnum::Guard->value,
        ]);

        $admin = User::factory()->create();
        $admin->givePermissionTo($permission);
        $this->actingAs($admin);

        return $admin;
    }
}

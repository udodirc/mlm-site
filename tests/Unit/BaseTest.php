<?php

namespace Tests\Unit;

use App\Enums\RolesEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\MockObject\MockObject;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * @template TService
 * @template TRepository
 */
abstract class BaseTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var TService
     */
    protected mixed $service;

    /**
     * @var TRepository|MockObject
     */
    protected mixed $repository;

    abstract protected function getServiceClass(): string;

    abstract protected function getRepositoryClass(): string;

    protected function setUp(): void
    {
        parent::setUp();

        $repositoryClass = $this->getRepositoryClass();
        $this->repository = $this->createMock($repositoryClass);

        $serviceClass = $this->getServiceClass();
        $this->service = new $serviceClass($this->repository);
    }

    protected function assertCreateEntity(
        object $createDto,
        array $expectedInput,
        object $expectedModel,
        string $method = 'create'
    ): void {
        $this->repository
            ->expects($this->once())
            ->method($method)
            ->with(
                $this->callback(function (array $data) use ($expectedInput) {
                    foreach ($expectedInput as $key => $value) {
                        if (!array_key_exists($key, $data)) {
                            return false;
                        }

                        if ($key === 'password') {
                            if (!Hash::check($value, $data[$key])) {
                                return false;
                            }
                        } else {
                            if ($data[$key] !== $value) {
                                return false;
                            }
                        }
                    }

                    return true;
                })
            )
            ->willReturn($expectedModel);

        $result = $this->service->$method($createDto);

        foreach ($expectedInput as $key => $value) {
            $this->assertEquals($value, $result->$key);
        }
    }
    protected function assertUpdateEntity(
        object $model,
        object $updateDto,
        array $expectedInput,
        object $expectedModel,
        string $method = 'update'
    ): void {
        $this->repository
            ->expects($this->once())
            ->method($method)
            ->with(
                $this->equalTo($model),
                $this->callback(function (array $data) use ($expectedInput) {
                    foreach ($expectedInput as $key => $value) {
                        if (!array_key_exists($key, $data)) {
                            return false;
                        }

                        if ($key === 'password') {
                            if (!Hash::check($value, $data[$key])) {
                                return false;
                            }
                        } else {
                            if ($data[$key] !== $value) {
                                return false;
                            }
                        }
                    }

                    return true;
                })
            )
            ->willReturn($expectedModel);

        $result = $this->service->$method($model, $updateDto);

        foreach ($expectedInput as $key => $value) {
            $actual = $result->getAttribute($key);

            if ($key === 'password') {
                $this->assertTrue(Hash::check($value, $actual));
            } else {
                $this->assertEquals($value, $actual);
            }
        }
    }

    protected function assertDeleteEntity(
        object $model,
        string $method = 'delete'
    ): void {
        $this->repository
            ->expects($this->once())
            ->method($method)
            ->with($this->equalTo($model))
            ->willReturn(true);

        $result = $this->service->$method($model);
        $this->assertTrue($result);
    }

    protected function assertListItemsEntity(
        object $model,
        array $items,
        string $method = 'all',
    ): void {
        $this->repository
            ->expects($this->once())
            ->method($method)
            ->willReturn($model);

        $result = $this->service->$method();

        $this->assertCount(2, $result);
        $this->assertEquals($items[0], $result[0]->name);
        $this->assertEquals($items[1], $result[1]->name);
    }

    protected function assertShowItemEntity(
        string $permission,
        string $route,
        bool $role = false,
    ): void {

        $adminRole = $this->auth($permission, $role);
        $response = $this->getJson(route($route, $adminRole->id));

        if ($role) {
            $data = [
                'id' => $adminRole->id,
                'name' => RolesEnum::Admin->value,
            ];
        } else {
            $data = [
                'id' => $adminRole->id,
                'name' => 'Alice',
                'email' => 'alice@test.test',
            ];
        }

        $response->assertOk();
        $response->assertJson([
            'data' => $data
        ]);
    }

    protected function auth(
        string $permission,
        bool $role = false
    ): Role|User {
        Permission::create([
            'name' => $permission,
            'guard_name' => RolesEnum::Guard->value,
        ]);

        $user = User::factory()->create([
            'name' => 'Alice',
            'email' => 'alice@test.test'
        ]);

        if ($role) {
            $adminRole = Role::create(['name' => RolesEnum::Admin->value, 'guard_name' => RolesEnum::Guard->value]);
            $adminRole->givePermissionTo($permission);
            $user->assignRole(RolesEnum::Admin->value);
            $this->actingAs($user, RolesEnum::Guard->value);

        } else {
            $user->givePermissionTo($permission);
            $this->actingAs($user, RolesEnum::Guard->value);
            $adminRole = $user;
        }

        return $adminRole;
    }
}

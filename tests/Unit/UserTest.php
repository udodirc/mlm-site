<?php

namespace Tests\Unit;

use App\Data\Admin\User\UserCreateData;
use App\Data\Admin\User\UserUpdateData;
use App\Enums\PermissionsEnum;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Collection;

class UserTest extends BaseTest
{
    protected function getServiceClass(): string
    {
        return UserService::class;
    }

    protected function getRepositoryClass(): string
    {
        return UserRepository::class;
    }

    public function testCreateUser(): void
    {
        $dto = new UserCreateData(
            email: 'user@test.test',
            name: 'user',
            password: '12345678',
            role: 'manager'
        );

        $user = new User([
            'name' => 'user',
            'email' => 'user@test.test',
            'password' => '12345678',
            'role' => 'manager'
        ]);

        $this->assertCreateEntity(
            createDto: $dto,
            expectedInput: [
                'name' => 'user',
                'email' => 'user@test.test'
            ],
            expectedModel: $user
        );
    }

    public function testUpdateUser(): void
    {
        $dto = new UserUpdateData(
            email: 'updated@test.test',
            name: 'Updated Name',
            password: '123456789',
            role: 'editor'
        );

        $user = new User([
            'id' => 1,
            'name' => 'user',
            'email' => 'user@test.test',
            'password' => bcrypt('old_password'),
        ]);

        $user->email = 'updated@test.test';
        $user->name = 'Updated Name';
        $user->password = bcrypt('123456789');

        $this->assertUpdateEntity(
            model: $user,
            updateDto: $dto,
            expectedInput: [
                'email' => 'updated@test.test',
                'name' => 'Updated Name',
                'password' => '123456789',
            ],
            expectedModel: $user
        );
    }

    public function testDeleteUser(): void
    {
        $user = new User([
            'id' => 1,
            'name' => 'user',
            'email' => 'user@test.test'
        ]);

        $this->assertDeleteEntity(
            model: $user
        );
    }

    public function testListUsers(): void
    {
        $users = new Collection([
            new User([
                'id' => 1,
                'name' => 'Alice',
                'email' => 'alice@test.test',
                'role' => 'admin'
            ]),
            new User([
                'id' => 2,
                'name' => 'Bob',
                'email' => 'bob@test.test',
                'role' => 'manager'
            ]),
        ]);

        $this->assertListItemsEntity(
            model: $users,
            items: ['Alice', 'Bob']
        );
    }

    public function testShowUser(): void
    {
        $this->assertShowItemEntity(
            PermissionsEnum::UserView->value,
            'users.show'
        );
    }
}

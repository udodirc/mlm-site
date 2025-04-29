<?php

namespace Tests\Unit;

use App\Data\Admin\User\UserCreateData;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\UserService;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class UserTest extends TestCase
{
    /** @var UserRepository|MockObject */
    protected $userRepository;

    /** @var UserService */
    protected $userService;

    protected function setUp(): void
    {
        parent::setUp();

        // Мокируем репозиторий
        $this->userRepository = $this->createMock(UserRepository::class);

        // Создаем экземпляр UserService, передавая мок репозитория
        $this->userService = new UserService($this->userRepository);
    }

    #[Test]
    public function create(): void
    {
        $data = new UserCreateData(
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

        $this->userRepository
            ->expects($this->once())
            ->method('create')
            ->with(
                $this->callback(function (array $data) {
                    return $data['name'] === 'user'
                        && $data['email'] === 'user@test.test'
                        && isset($data['password'])
                        && $data['role'] === 'manager';
                })
            )
            ->willReturn(
                $user
            );

        $createdUser = $this->userService->create($data);

        $this->assertEquals('user', $createdUser->name);
        $this->assertEquals('user@test.test', $createdUser->email);
    }


    #[Test]
    public function update(): void
    {
        $data = new UserCreateData(
            email: 'updated@test.test',
            name: 'Updated Name',
            password: '123456789',
            role: 'editor'
        );

        $user = new User([
            'id' => 1,
            'name' => 'user',
            'email' => 'user@test.test',
            'role' => 'manager'
        ]);

        $this->userRepository
            ->expects($this->once())
            ->method('update')
            ->with($user, $data)
            ->willReturn($user);

        $updatedUser = $this->userService->update($user, $data);

        $this->assertSame($user, $updatedUser);
    }

    #[Test]
    public function deleteUser(): void
    {
        $user = new User([
            'id' => 1,
            'name' => 'user',
            'email' => 'user@test.test',
            'role' => 'manager'
        ]);

        $this->userRepository
            ->expects($this->once())
            ->method('delete')
            ->with($user)
            ->willReturn(true);

        $result = $this->userService->delete($user);

        $this->assertTrue($result);
    }
}

<?php

namespace Tests\Unit;

use App\Data\Admin\User\UserCreateData;
use App\Data\Admin\User\UserUpdateData;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;
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

    /*
     * @return void
     */
    public function testCreateUser(): void
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
                        && isset($data['password']);
                })
            )
            ->willReturn(
                $user
            );

        $createdUser = $this->userService->create($data);

        $this->assertEquals('user', $createdUser->name);
        $this->assertEquals('user@test.test', $createdUser->email);
    }


    /*
     * @return void
     */
    public function testUpdateUser(): void
    {
        $data = new UserUpdateData(
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
            ->with(
                $this->equalTo($user),
                $this->callback(function ($array) {
                    return $array['email'] === 'updated@test.test'
                        && $array['name'] === 'Updated Name'
                        && Hash::check('123456789', $array['password']);
                })
            )
            ->willReturn($user);

        $updatedUser = $this->userService->update($user, $data);

        $this->assertSame($user, $updatedUser);
    }

    /*
    * @return void
    */
    public function testDeleteUser(): void
    {
        $user = new User([
            'id' => 1,
            'name' => 'user',
            'email' => 'user@test.test',
            'role' => 'manager',
        ]);

        $this->userRepository
            ->expects($this->once())
            ->method('delete')
            ->with($this->equalTo($user))
            ->willReturn(true);

        $result = $this->userService->delete($user);

        $this->assertTrue($result);
    }
}

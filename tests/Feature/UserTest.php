<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Тестируем создание пользователя.
     *
     * @return void
     */
    public function test_create_user()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'user@test.test',
            'password' => '12345678',
            'role' => 'manager'
        ];

        $response = $this->postJson(route('users.store'), $data);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonFragment(['name' => 'Test User']);
    }

    /**
     * Тестируем обновление пользователя.
     *
     * @return void
     */
    public function test_update_user()
    {
        // Создаем тестового пользователя
        $user = User::factory()->create();

        $data = [
            'name' => 'Updated User',
            'email' => 'updated@test.test',
            'password' => '123456789',
            'role' => 'manager'
        ];

        $response = $this->putJson(route('users.update', $user->id), $data);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment(['name' => 'Updated User']);
    }

    /**
     * Тестируем удаление пользователя.
     *
     * @return void
     */
    public function testDeleteUser()
    {
        $user = User::factory()->create();  // Создаем пользователя

        $response = $this->deleteJson(route('users.destroy', $user));  // Отправляем запрос на удаление

        $response->assertStatus(200);  // Проверяем, что статус код 204
    }


    /**
     * Тестируем получение списка пользователей.
     *
     * @return void
     */
    public function test_get_users_list()
    {
        // Создаем несколько пользователей
        User::factory()->count(3)->create();

        $response = $this->getJson(route('users.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(3, 'data'); // Проверяем, что возвращено 3 пользователя
    }

    /**
     * Тестируем получение одного пользователя.
     *
     * @return void
     */
    public function test_get_single_user()
    {
        // Создаем пользователя
        $user = User::factory()->create();

        $response = $this->getJson(route('users.show', $user->id));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment(['name' => $user->name]);
    }
}

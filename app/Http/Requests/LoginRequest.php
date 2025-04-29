<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $login
 * @property string $password
 * @property User $user
 */
class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'login' => 'required|string|max:255',
            'password' => 'required|string|min:1|max:128',
        ];
    }

    protected function passedValidation(): void
    {
        /** @var User|null $user */
        $user = User::query()->where('name', $this->login)->first();

        if (!($user && password_verify($this->password, $user->password))) {
            throw new AuthenticationException('Incorrect email or password');
        }

        $this->merge([
            'user' => $user,
        ]);
    }
}

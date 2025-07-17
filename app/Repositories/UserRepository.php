<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function update(Model $model, array $data): ?Model
    {
        $model->fill([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        if (isset($data['password'])) {
            $model->password = Hash::make($data['password']);
        }

        if ($model->isDirty()) {
            $model->save(); // вызовет updated
        }

        return $model;
    }
}

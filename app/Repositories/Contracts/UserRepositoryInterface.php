<?php
namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

interface UserRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): null|Model|User;

    public function create(array $data): null|Model|User;

    public function update(User $model, array $data): null|Model|User;

    public function delete(User $model): bool;
}

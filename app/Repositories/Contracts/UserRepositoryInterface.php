<?php
namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

interface UserRepositoryInterface
{
    public function all(): array;

    public function find(int $id): ?array;

    public function create(array $data): int;

    public function update(User $model, array $data): array;

    public function delete(int $id): bool;
}

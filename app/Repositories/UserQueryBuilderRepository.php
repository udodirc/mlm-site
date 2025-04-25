<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserQueryBuilderRepository implements UserRepositoryInterface
{
    protected $table = 'users';

    public function all(): array
    {
        return DB::table($this->table)->get()->toArray();
    }

    public function find(int $id): ?array
    {
        $user = DB::table($this->table)->where('id', $id)->first();
        return $user ? (array) $user : null;
    }

    public function create(array $data): int
    {
        return DB::table($this->table)->insertGetId($data);
    }

    public function update(int $id, array $data): bool
    {
        return DB::table($this->table)->where('id', $id)->update($data) > 0;
    }

    public function delete(int $id): bool
    {
        return DB::table($this->table)->where('id', $id)->delete() > 0;
    }
}

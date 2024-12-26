<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    public function create(User $entity): User;
    public function findById(string $id): ?User;
    public function update(string $id, User $entity): User;
    public function delete(string $id): bool;
    public function findByEmail(string $email): User;
    public function fetchAll(): Collection;
    public function paginate(int $perPage): Collection;
}

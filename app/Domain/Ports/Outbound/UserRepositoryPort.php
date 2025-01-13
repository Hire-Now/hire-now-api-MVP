<?php

namespace App\Domain\Ports\Outbound;

use App\Domain\Entities\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryPort
{
    public function create(User $entity): User;
    public function findById(string $id): ?User;
    public function update(string $id, User $entity): User;
    public function delete(string $id): bool;
    public function findByEmail(string $email): User;
    public function fetchAll(?string $name, ?string $status, string $orderBy, string $orderDirection): Collection;
    // public function paginate(int $perPage): Collection;
    public function setRolesToUser(string $userId, array $roles): User;
    public function removeRolesToUser(string $userId, array $roles): User;
}

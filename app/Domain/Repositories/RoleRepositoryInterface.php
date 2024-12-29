<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Role;
use Illuminate\Support\Collection;

interface RoleRepositoryInterface
{
    public function create(Role $entity): Role;
    public function findById(string $id): ?Role;
    public function update(string $id, Role $entity): Role;
    public function delete(string $id): bool;
    public function findByEmail(string $email): Role;
    public function fetchAll(): Collection;
    public function paginate(int $perPage): Collection;
}

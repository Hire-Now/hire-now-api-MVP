<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Role;
use Illuminate\Support\Collection;

interface RoleRepositoryInterface
{
    public function create(Role $entity): Role;
    public function findById(string $id): ?Role;
    public function findByName(array $roles): ?array;
    public function update(string $id, Role $entity): Role;
    public function delete(string $id): bool;
    public function fetchAll(?string $name, ?string $status, string $orderBy, string $orderDirection): Collection;
    public function createRolePermissions(string $roleId, array $permissions): Role;
    public function removeRolePermissions(string $roleId, array $permissions): Role;
}

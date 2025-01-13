<?php

namespace App\Domain\Ports\Outbound;

use App\Domain\Entities\Role;
use Illuminate\Support\Collection;

interface RoleRepositoryPort
{
    public function findById(string $id): ?Role;
    public function findByName(array $roles): ?array;
    public function update(string $id, Role $entity): Role;
    public function createRolePermissions(string $roleId, array $permissions): Role;
    public function removeRolePermissions(string $roleId, array $permissions): Role;
    public function fetchAll(?string $name, ?string $status, string $orderBy, string $orderDirection): Collection;
}

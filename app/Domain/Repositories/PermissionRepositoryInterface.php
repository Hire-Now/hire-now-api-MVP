<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Permission;
use Illuminate\Support\Collection;

interface PermissionRepositoryInterface
{
    public function create(Permission $entity): Permission;
    public function findById(string $id): ?Permission;
    public function update(string $id, Permission $entity): Permission;
    public function delete(string $id): bool;
    public function findByEmail(string $email): Permission;
    public function fetchAll(?string $name, ?string $status, string $orderBy, string $orderDirection): Collection;
}

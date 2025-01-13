<?php

namespace App\Domain\Ports\Outbound;

use App\Domain\Entities\Permission;
use Illuminate\Support\Collection;

interface PermissionRepositoryPort
{
    public function create(Permission $permission): Permission;
    public function findById(string $id): ?Permission;
    public function update(string $id, Permission $entity): Permission;
    public function fetchAll(?string $name, ?string $status, string $orderBy, string $orderDirection): Collection;
}

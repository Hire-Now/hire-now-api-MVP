<?php

namespace App\Application\Contracts;

use App\Domain\Entities\Permission;
use Illuminate\Database\Eloquent\Collection;

interface PermissionUseCaseInterface
{
    public function createPermission(Permission $entity): Permission;

    public function updatePermission(Permission $entity): Permission;

    public function deletePermission(Permission $entity): Permission;

    public function fetchPermissions(?string $name, ?string $status, string $orderBy, string $orderDirection): Collection;
}

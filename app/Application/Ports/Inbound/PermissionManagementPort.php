<?php

namespace App\Application\Ports\Inbound;

use App\Domain\Entities\Permission;
use Illuminate\Database\Eloquent\Collection;

interface PermissionManagementPort
{
    public function createPermission(Permission $entity): Permission;
    public function updatePermission(Permission $entity): void;
    public function deletePermission(Permission $entity): void;
    public function fetchPermissions(?string $name, ?string $status, string $orderBy, string $orderDirection): Collection;
}

<?php

namespace App\Application\Ports\Inbound;

use App\Domain\Entities\Role;
use Illuminate\Support\Collection;

interface RoleManagementPort
{
    public function fetchRoles(?string $name, ?string $status, string $orderBy, string $orderDirection): Collection;
    public function fetchRoleByName(array $roles): array;
    public function createRole(Role $entity): Role;
    public function assignPermissionsToRole(string $roleId, array $permissions): Role;
    public function removeRolePermissions(string $roleId, array $permissions): Role;
}

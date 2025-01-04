<?php

namespace App\Application\Contracts;

use App\Domain\Entities\Role;
use App\Shared\Types\SearchRolesFilter;
use Illuminate\Database\Eloquent\Collection;

interface RoleUseCaseInterface
{
    public function fetchRoles(?string $name, ?string $status, string $orderBy, string $orderDirection): Collection;

    public function fetchRoleByName(array $role): array;
    public function createRole(Role $entity): Role;

    public function updateRole(Role $entity): Role;

    public function deleteRole(Role $entity): Role;

    public function assignPermissionsToRole(string $roleId, array $permissions): Role;

    public function removeRolePermissions(string $roleId, array $permissions): Role;

}

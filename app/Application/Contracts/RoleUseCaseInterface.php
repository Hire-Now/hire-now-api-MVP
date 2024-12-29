<?php

namespace App\Application\Contracts;

use App\Domain\Entities\Role;

interface RoleUseCaseInterface
{
    public function createRole(Role $entity): Role;

    public function updateRole(Role $entity): Role;

    public function deleteRole(Role $entity): Role;

    public function assignPermissionsToRole(Role $entity): Role;
}

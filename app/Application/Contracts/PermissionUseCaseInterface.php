<?php

namespace App\Application\Contracts;

use App\Domain\Entities\Permission;

interface PermissionUseCaseInterface
{
    public function createPermission(Permission $entity): Permission;

    public function updatePermission(Permission $entity): Permission;

    public function deletePermission(Permission $entity): Permission;
}

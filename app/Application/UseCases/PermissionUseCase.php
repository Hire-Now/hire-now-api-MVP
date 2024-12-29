<?php

namespace App\Application\UseCases;

use App\Application\Contracts\PermissionUseCaseInterface;
use App\Domain\Entities\Permission;
use App\Domain\Repositories\PermissionRepositoryInterface;

class PermissionUseCase implements PermissionUseCaseInterface
{
    public function __construct(private PermissionRepositoryInterface $repository)
    {
    }

    public function createPermission(Permission $entity): Permission
    {
        return $this->repository->create($entity);
    }

    public function updatePermission(Permission $entity): Permission
    {
    }

    public function deletePermission(Permission $entity): Permission
    {
    }

    public function assignPermissionsToRole(Permission $entity): Permission
    {
    }
}

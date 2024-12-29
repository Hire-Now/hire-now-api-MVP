<?php

namespace App\Application\UseCases;

use App\Application\Contracts\RoleUseCaseInterface;
use App\Domain\Entities\Role;
use App\Domain\Repositories\RoleRepositoryInterface;

class RoleUseCase implements RoleUseCaseInterface
{
    public function __construct(private RoleRepositoryInterface $repository)
    {
    }

    public function createRole(Role $entity): Role
    {
        return $this->repository->create($entity);
    }

    public function updateRole(Role $entity): Role
    {
    }

    public function deleteRole(Role $entity): Role
    {
    }

    public function assignPermissionsToRole(Role $entity): Role
    {
    }
}

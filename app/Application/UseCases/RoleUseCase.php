<?php

namespace App\Application\UseCases;

use App\Application\Contracts\RoleUseCaseInterface;
use App\Domain\Entities\Role;
use App\Domain\Repositories\RoleRepositoryInterface;
use App\Shared\Types\SearchRolesFilter;
use Illuminate\Database\Eloquent\Collection;

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

    public function fetchRoles(?string $name, ?string $status, string $orderBy, string $orderDirection): Collection
    {
        return $this->repository->fetchAll($name, $status, $orderBy, $orderDirection);
    }
}

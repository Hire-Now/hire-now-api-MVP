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

    public function fetchRoles(?string $name, ?string $status, string $orderBy, string $orderDirection): Collection
    {
        return $this->repository->fetchAll($name, $status, $orderBy, $orderDirection);
    }

    public function fetchRoleByName(array $roles): array
    {
        return $this->repository->findByName($roles);
    }

    public function createRole(Role $entity): Role
    {
        return $this->repository->create($entity);
    }

    public function assignPermissionsToRole(string $roleId, array $permissions): Role
    {
        return $this->repository->createRolePermissions($roleId, $permissions);
    }

    public function removeRolePermissions(string $roleId, array $permissions): Role
    {
        return $this->repository->removeRolePermissions($roleId, $permissions);
    }
}

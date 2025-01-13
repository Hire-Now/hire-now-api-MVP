<?php

namespace App\Application\UseCases;

use App\Application\Ports\Inbound\RoleManagementPort;
use App\Domain\Entities\Role;
use App\Domain\Ports\Outbound\RoleRepositoryPort;
use Illuminate\Database\Eloquent\Collection;

class RoleUseCase implements RoleManagementPort
{
    public function __construct(private RoleRepositoryPort $repository)
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

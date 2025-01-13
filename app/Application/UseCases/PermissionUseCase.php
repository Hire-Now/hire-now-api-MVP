<?php

namespace App\Application\UseCases;

use App\Application\Ports\Inbound\PermissionManagementPort;
use App\Domain\Entities\Permission;
use App\Domain\Ports\Outbound\PermissionRepositoryPort;
use Illuminate\Database\Eloquent\Collection;

class PermissionUseCase implements PermissionManagementPort
{
    public function __construct(private PermissionRepositoryPort $repository)
    {
    }

    public function createPermission(Permission $entity): Permission
    {
        return $this->repository->create($entity);
    }

    /**
     * @return Collection<Permission>
     */
    public function fetchPermissions(?string $name, ?string $status, string $orderBy, string $orderDirection): Collection
    {
        return $this->repository->fetchAll($name, $status, $orderBy, $orderDirection);
    }

    public function updatePermission(Permission $entity): void
    {
    }

    public function deletePermission(Permission $entity): void
    {
    }
}

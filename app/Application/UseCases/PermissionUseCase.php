<?php

namespace App\Application\UseCases;

use App\Application\Contracts\PermissionUseCaseInterface;
use App\Domain\Entities\Permission;
use App\Domain\Repositories\PermissionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PermissionUseCase implements PermissionUseCaseInterface
{
    public function __construct(private PermissionRepositoryInterface $repository)
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

    public function updatePermission(Permission $entity): Permission
    {
    }

    public function deletePermission(Permission $entity): Permission
    {
    }
}

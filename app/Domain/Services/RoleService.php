<?php

namespace App\Domain\Services;

use App\Domain\Entities\Role;
use App\Domain\Entities\User;
use App\Domain\Repositories\UserRepositoryInterface;

class RoleService
{
    public function __construct(private RoleRepositoryInterface $roleRepository, PermissionRepositoryInterface $permissionRepository)
    {
    }

    public function createRole(string $name, string $description, array $permissionNames): Role
    {
        // Buscar permisos por nombres
        $permissions = array_map(fn($name) => $this->permissionRepository->findByName($name), $permissionNames);

        // Crear el rol
        $role = new Role($name, $description, $permissions);

        // Guardar el rol en el repositorio
        $this->roleRepository->save($role);

        return $role;
    }

    public function assignPermissionsToRole(string $roleName, array $permissionNames): void
    {
        // Buscar el rol
        $role = $this->roleRepository->findByName($roleName);

        // Buscar permisos por nombres
        $permissions = array_map(fn($name) => $this->permissionRepository->findByName($name), $permissionNames);

        // Actualizar permisos del rol
        $role->setPermissions($permissions);

        // Guardar cambios en el repositorio
        $this->roleRepository->save($role);
    }
}

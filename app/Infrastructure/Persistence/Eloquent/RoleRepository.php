<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Entities\Role;
use App\Domain\Entities\Permission;
use App\Domain\Repositories\RoleRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Models\Permission as PermissionModel;
use App\Infrastructure\Persistence\Eloquent\Models\Role as RoleModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RoleRepository implements RoleRepositoryInterface
{
    public function create(Role $role): Role
    {
        try {
            $roleModel = RoleModel::create([
                'name'        => $role->getName(),
                'description' => $role->getDescription()
            ]);

            $role->setId($roleModel->id);

            return $role;
        } catch (\Throwable $th) {
            throw new Exception("Error saving role to database", 0, $th);
        }
    }

    public function findById(string $id): ?Role
    {
        try {
            $role = RoleModel::with('permissions')->findOrFail($id);

            $permissionsEntities = $role->permissions->map(function ($permission) {
                return new Permission($permission->id, $permission->name, $permission->description);
            });

            return new Role($role->id, $role->name, $role->description, $permissionsEntities->toArray());
        } catch (\Throwable $th) {
            throw new Exception("Error fetching role data from database.", 0, $th);
        }
    }

    /**
     * Summary of findByName
     * @param array $roles
     * @throws \Exception
     * @return array Role[]
     */
    public function findByName(array $roles): ?array
    {
        try {
            $rolesFound = RoleModel::whereIn('name', $roles)->with('permissions')->get();
            $rolesWithPermissions = [];

            foreach ($rolesFound as $role) {
                $permissionsEntities = [];

                foreach ($role->permissions as $permission) {
                    $permissionsEntities[] = new Permission($permission->id, $permission->name, $permission->description);
                }

                $rolesWithPermissions[] = new Role(
                    $role->id,
                    $role->name,
                    $role->description,
                    $permissionsEntities
                );
            }

            return $rolesWithPermissions;
        } catch (\Throwable $th) {
            throw new Exception("Error fetching role data from database.", 0, $th);
        }
    }

    public function update(string $id, Role $entity): Role
    {
        try {
            $date = Carbon::now();
            //todo: arreglar updates para que no se deban actualizar todos los campos siempre, solo lo requerido
            $userModel = RoleModel::where('id', $id)->update();

            if (!$userModel) {
                throw new ModelNotFoundException("An error happened when updating the model, " . RoleModel::class);
            }

            $entity->setLastActivity($date);

            return $entity;
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage(), 0, $th);
        }
    }

    public function delete(string $id): bool
    {
        return true;
    }

    public function createRolePermissions(string $roleId, array $permissions): Role
    {
        try {
            $role = RoleModel::findOrFail($roleId);

            $role->permissions()->syncWithoutDetaching($permissions);

            $permissionsEntities = PermissionModel::whereIn('id', $permissions)->get();

            $permissionEntities = $permissionsEntities->map(function ($permission) {
                return new Permission($permission->id, $permission->name, $permission->description);
            });

            return new Role(
                $role->id,
                $role->name,
                $role->description,
                $permissionEntities->toArray()
            );
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage(), 0, $th);
        }
    }

    public function removeRolePermissions(string $roleId, array $permissions): Role
    {
        try {
            $role = RoleModel::findOrFail($roleId);

            $role->permissions()->detach($permissions);

            $permissionsEntities = $role->permissions;

            $permissionEntities = $permissionsEntities->map(function ($permission) {
                return new Permission($permission->id, $permission->name, $permission->description);
            });

            return new Role(
                $role->id,
                $role->name,
                $role->description,
                $permissionEntities->toArray()
            );
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage(), 0, $th);
        }
    }

    public function fetchAll(?string $name, ?string $status, string $orderBy, string $orderDirection): Collection
    {
        try {
            if (!in_array($orderDirection, [ 'asc', 'desc' ])) {
                throw new Exception('Order direction param is wrong, permitted values are asc or desc');
            }

            $query = RoleModel::query();

            if (!is_null($name)) {
                $query->where('name', 'like', "%{$name}%");
            }

            if (!is_null($status)) {
                $query->where('status', $status);
            }

            $query->orderBy("{$orderBy}_at", $orderDirection);

            return $query->with('permissions')->get([ 'id', 'name', 'description', 'status', 'created_at', 'updated_at' ]);
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage(), 0, $th);
        }
    }

}

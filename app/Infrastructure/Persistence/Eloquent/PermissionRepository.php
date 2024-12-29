<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Entities\Permission;
use App\Domain\Entities\Role;
use App\Domain\Repositories\PermissionRepositoryInterface;
use App\Domain\Repositories\RoleRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Models\Permission as PermissionModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class PermissionRepository implements PermissionRepositoryInterface
{
    public function create(Permission $permission): Permission
    {
        try {
            $permissionModel = PermissionModel::create([
                'name'        => $permission->getName(),
                'description' => $permission->getDescription()
            ]);

            $permission->setId($permissionModel->id);

            return $permission;
        } catch (\Throwable $th) {
            throw new Exception("Error saving permission to database", 0, $th);
        }
    }

    public function findById(string $id): ?Permission
    {   //todo: setear roles a la entidad
        try {
            $permissionModel = PermissionModel::with('roles')->findOrFail($id);

            return new Permission();
        } catch (\Throwable $th) {
            throw new Exception("Error fetching user data from database.", 0, $th);
        }
    }

    public function update(string $id, Permission $entity): Permission
    {
        try {
            $date = Carbon::now();
            //todo: arreglar updates para que no se deban actualizar todos los campos siempre, solo lo requerido
            $userModel = PermissionModel::where('id', $id)->update();

            if (!$userModel) {
                throw new ModelNotFoundException("An error happened when updating the model, " . PermissionModel::class);
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

    public function findByEmail(string $email): Permission
    {
        return new Permission();
    }

    public function fetchAll(): Collection
    {
        return new Collection();
    }

    public function paginate(int $perPage): Collection
    {
        return new Collection();
    }
}

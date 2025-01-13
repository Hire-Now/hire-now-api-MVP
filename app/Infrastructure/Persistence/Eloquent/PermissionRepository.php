<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Entities\Permission;
use App\Domain\Ports\Outbound\PermissionRepositoryPort;
use App\Infrastructure\Persistence\Eloquent\Models\Permission as PermissionModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PermissionRepository implements PermissionRepositoryPort
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

    public function fetchAll(?string $name, ?string $status, string $orderBy, string $orderDirection): Collection
    {
        $query = PermissionModel::query();

        if (!is_null($name)) {
            $query->where('name', 'like', "%{$name}%");
        }

        if (!is_null($status)) {
            $query->where('status', $status);
        }

        $query->orderBy("{$orderBy}_at", $orderDirection);

        return $query->with('roles')->get([ 'id', 'name', 'description', 'status', 'created_at', 'updated_at' ]);
    }
}

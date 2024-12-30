<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Entities\Role;
use App\Domain\Repositories\RoleRepositoryInterface;
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
    {   //todo: setear roles a la entidad
        try {
            $roleModel = RoleModel::with('permissions')->findOrFail($id);

            return new Role();
        } catch (\Throwable $th) {
            throw new Exception("Error fetching user data from database.", 0, $th);
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

    public function findByEmail(string $email): Role
    {
        return new Role();
    }

    public function fetchAll(?string $name, ?string $status, string $orderBy, string $orderDirection): Collection
    {
        $query = RoleModel::query();

        if (!is_null($name)) {
            $query->where('name', 'like', "%{$name}%");
        }

        if (!is_null($status)) {
            $query->where('status', $status);
        }

        $query->orderBy("{$orderBy}_at", $orderDirection);

        return $query->get([ 'id', 'name', 'description', 'status', 'created_at', 'updated_at' ]);
    }

    public function paginate(int $perPage): Collection
    {
        return new Collection();
    }
}

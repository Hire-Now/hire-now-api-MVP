<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Entities\Permission;
use App\Domain\Entities\Role;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Entities\User;
use App\Domain\Enums\ElementStatus;
use App\Infrastructure\Persistence\Eloquent\Models\Role as RoleModel;
use App\Infrastructure\Persistence\Eloquent\Models\User as UserModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserRepository implements UserRepositoryInterface
{
    public function create(User $user): User
    {
        try {
            $userModel = UserModel::create([
                'name'          => $user->getName(),
                'email'         => $user->getEmail(),
                'password'      => $user->getPassword(),
                'status'        => $user->getStatus(),
                'last_activity' => Carbon::now(),
                'birth_date'    => $user->getBirthDate()
            ]);

            $user->setId($userModel->id);
            $user->setCreatedAt($userModel->created_at);
            $user->setPassword(null);

            return $user;
        } catch (\Throwable $th) {
            throw new Exception("Error saving candidate to database", 0, $th);
        }
    }

    public function findById(string $id): ?User
    {   //todo: setear roles a la entidad
        try {
            $userModel = UserModel::with('roles')->findOrFail($id);

            return new User(
                $userModel->id,
                $userModel->name,
                $userModel->email,
                $userModel->password,
                $userModel->birth_date,
                null,
                ElementStatus::{strtoupper($userModel->status)},
                $userModel->created_at,
                $userModel->last_activity
            );
        } catch (\Throwable $th) {
            throw new Exception("Error fetching user data from database.", 0, $th);
        }
    }

    public function update(string $id, User $entity): User
    {
        try {
            $date = Carbon::now();
            //todo: arreglar updates para que no se deban actualizar todos los campos siempre, solo lo requerido
            $userModel = UserModel::where('id', $id)->update([
                'name'          => $entity->getName(),
                'email'         => $entity->getEmail(),
                'status'        => $entity->getStatus(),
                'last_activity' => $date,
                'birth_date'    => $entity->getBirthDate(),
            ]);

            if (!$userModel) {
                throw new ModelNotFoundException("An error happened when updating the model, " . UserModel::class);
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

    public function findByEmail(string $email): User
    {
        return new User();
    }

    public function fetchAll(): Collection
    {
        return new Collection();
    }

    public function paginate(int $perPage): Collection
    {
        return new Collection();
    }

    public function setRoleToUser(string $userId, array $roles): void
    {
        try {
            $user = UserModel::findOrFail($userId);

            $user->roles()->syncWithoutDetaching(
                array_map(function ($role) {
                    return $role->getId();
                }, $roles)
            );
        } catch (\Throwable $th) {
            throw new Exception("Error assigning role to user", 0, $th);
        }
    }
}

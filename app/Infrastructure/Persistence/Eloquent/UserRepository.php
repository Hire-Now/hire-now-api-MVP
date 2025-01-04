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
    {
        try {
            $userModel = UserModel::with('roles')->findOrFail($id);

            $roleEntities = $this->buildRoleEntity($userModel->roles);

            return $this->buildUserEntity($userModel, $roleEntities);
        } catch (\Throwable $th) {
            throw new Exception("Error fetching user data from database.", 0, $th);
        }
    }

    public function update(string $id, User $entity): User
    {
        try {
            $date = Carbon::now();

            $updateData = array_filter([
                'name'          => $entity->getName(),
                'email'         => $entity->getEmail(),
                'status'        => $entity->getStatus(),
                'last_activity' => $date,
                'birth_date'    => $entity->getBirthDate(),
                'password'      => $entity->getPassword(),
            ], function ($value) {
                return !is_null($value) && $value !== '';
            });

            $updated = UserModel::where('id', $id)->update($updateData);

            if (!$updated) {
                throw new ModelNotFoundException("An error occurred when updating the model, " . UserModel::class);
            }

            $userModel = UserModel::findOrFail($id);

            $entity->setName($userModel->name);
            $entity->setEmail($userModel->email);
            $entity->setStatus(ElementStatus::from($userModel->status));
            $entity->setBirthDate(new Carbon($userModel->birth_date));
            $entity->setLastActivity(new Carbon($userModel->last_activity));

            return $entity;
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage(), 0, $th);
        }
    }

    public function delete(string $id): bool
    {
        try {
            return UserModel::findOrFail($id)->delete();
        } catch (\Throwable $th) {
            throw new Exception("Failed to delete user with ID: $id", 0, $th);
        }
    }

    public function findByEmail(string $email): User
    {
        try {
            $userModel = UserModel::with('roles.permissions')->where([
                'email'  => $email,
                'status' => ElementStatus::ACTIVE
            ])->first();

            $roleEntities = $this->buildRoleEntity($userModel->roles);

            return $this->buildUserEntity($userModel, $roleEntities);
        } catch (\Throwable $th) {
            throw new Exception("Error fetching user data from database.", 0, $th);
        }
    }

    public function fetchAll(?string $name, ?string $status, string $orderBy, string $orderDirection): Collection
    {
        try {
            if (!in_array($orderDirection, [ 'asc', 'desc' ])) {
                throw new Exception('Order direction param is wrong, permitted values are asc or desc');
            }

            $query = UserModel::query();

            if (!is_null($name)) {
                $query->where('name', 'like', "%{$name}%");
            }

            if (!is_null($status)) {
                $query->where('status', $status);
            }

            $query->orderBy("{$orderBy}_at", $orderDirection);

            return $query->with('roles')->get([ 'id', 'name', 'email', 'status', 'birth_date', 'last_activity', 'created_at' ]);
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage(), 0, $th);
        }
    }

    public function paginate(int $perPage): Collection
    {
        return new Collection();
    }

    public function setRolesToUser(string $userId, array $roles): User
    {
        try {
            $user = UserModel::findOrFail($userId);

            $roleIds = array_map(fn($role) => $role->getId(), $roles);

            $user->roles()->syncWithoutDetaching($roleIds);

            $user->setRelation('roles', $user->roles()->with('permissions')->get());

            $roleEntities = $this->buildRoleEntity($user->roles);

            return $this->buildUserEntity($user, $roleEntities);
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage(), 0, $th);
        }
    }

    public function removeRolesToUser(string $userId, array $roles): User
    {
        try {
            $user = UserModel::findOrFail($userId);

            $user->roles()->detach($roles);

            $user->setRelation('roles', $user->roles()->with('permissions')->get());

            $roleEntities = $this->buildRoleEntity($user->roles);

            return $this->buildUserEntity($user, $roleEntities);
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage(), 0, $th);
        }
    }

    private function buildRoleEntity($roles): array
    {
        try {
            $roleEntities = [];

            foreach ($roles as $role) {
                $permissionArray = [];

                foreach ($role->permissions as $permission) {
                    $permissionArray[] = new Permission($permission->id, $permission->name, $permission->description);
                }

                $roleEntities[] = new Role($role->id, $role->name, $role->description, $permissionArray);
            }

            return $roleEntities;
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage(), 0, $th);
        }
    }

    private function buildUserEntity(UserModel $user, array $roles): User
    {
        return new User(
            $user->id,
            $user->name,
            $user->email,
            $user->password,
            $user->birth_date,
            $roles,
            ElementStatus::{strtoupper($user->status)},
            $user->created_at,
            $user->last_activity
        );
    }

}

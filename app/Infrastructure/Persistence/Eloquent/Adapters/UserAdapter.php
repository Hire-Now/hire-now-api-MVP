<?php

namespace App\Infrastructure\Persistence\Eloquent\Adapters;

use App\Domain\Entities\Permission;
use App\Domain\Entities\Role;
use App\Domain\Entities\User;
use App\Domain\Enums\ElementStatus;
use App\Infrastructure\Persistence\Eloquent\Models\User as UserModel;
use App\Infrastructure\Persistence\Eloquent\Models\Role as RoleModel;
use App\Infrastructure\Persistence\Eloquent\Models\Permission as PermissionModel;



class UserAdapter
{
    public static function toDomain(UserModel $eloquentConsumer): User
    {
        return new User(
            $eloquentConsumer->id,
            $eloquentConsumer->name,
            $eloquentConsumer->email,
            $eloquentConsumer->password,
            $eloquentConsumer->birth_date,
            self::buildRoleEntity($eloquentConsumer->roles()),
            ElementStatus::{strtoupper($eloquentConsumer->status)},
            $eloquentConsumer->created_at,
            $eloquentConsumer->last_activity,
        );
    }

    public static function toEloquent(User $domainConsumer): UserModel
    {
        $eloquentConsumer = new UserModel();
        $eloquentConsumer->id = $domainConsumer->getId();
        $eloquentConsumer->name = $domainConsumer->getName();
        $eloquentConsumer->email = $domainConsumer->getEmail();
        $eloquentConsumer->password = $domainConsumer->getPassword();
        $eloquentConsumer->birth_date = $domainConsumer->getBirthDate();
        $eloquentConsumer->status = $domainConsumer->getStatus();
        $eloquentConsumer->created_at = $domainConsumer->getCreatedAt();
        $eloquentConsumer->last_activity = $domainConsumer->getLastActivity();

        $roles = $domainConsumer->getRoles();
        $eloquentConsumer->setRelation(
            'roles',
            $roles && count($roles) > 0 ? collect($roles)->map(function ($role) {
                $eloquentRole = new RoleModel();
                $eloquentRole->id = $role['id'];
                $eloquentRole->name = $role['name'];
                $eloquentRole->description = $role['description'];

                $permissions = $role['permissions'];

                if (!empty($permissions)) {
                    $eloquentRole->setRelation(
                        'permissions',
                        $permissions && count($permissions) > 0 ? collect($permissions)->map(function ($permission) {
                            $eloquentPermission = new PermissionModel();
                            $eloquentPermission->id = $permission['id'];
                            $eloquentPermission->name = $permission['name'];
                            $eloquentPermission->description = $permission['description'];

                            return $eloquentPermission;
                        }) : []
                    );
                }

                return $eloquentRole;
            }) : []
        );

        return $eloquentConsumer;
    }

    private static function buildRoleEntity($roles): array
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
            throw new \Exception($th->getMessage(), 0, $th);
        }
    }
}

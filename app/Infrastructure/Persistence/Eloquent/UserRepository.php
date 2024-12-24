<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Entities\User;
use App\Infrastructure\Persistence\Eloquent\Models\User as UserModel;

class UserRepository implements UserRepositoryInterface
{
    public function save(User $entity): User
    {
        $model = UserModel::updateOrCreate(
            ['id' => $entity->id],
            ['name' => $entity->name]
        );

        $entity->id = $model->id;

        return $entity;
    }
}
<?php

namespace App\Application\Contracts;

use App\Infrastructure\Persistence\Eloquent\Models\User;

interface AuthorizationInterface
{
    public function hasPermission(User $user, string $permission): bool;
    public function hasRole(User $user, string $role): bool;
    public function userPolicy(string $entityId, User $model): bool;
}

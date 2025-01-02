<?php

namespace App\Application\Services;

use App\Application\Contracts\AuthorizationInterface;
use App\Infrastructure\Persistence\Eloquent\Models\User;

class AuthorizationService implements AuthorizationInterface
{
    public function hasPermission(User $user, string $permission): bool
    {
        foreach ($user->roles ?? [] as $role) {
            foreach ($role->permissions ?? [] as $perm) {
                if ($perm->name === $permission) {
                    return true;
                }
            }
        }

        return false;
    }

    public function hasRole(User $user, string $role): bool
    {
        foreach ($user->roles ?? [] as $roleIter) {
            if ($roleIter->name === $role) {
                return true;
            }
        }

        return false;
    }
}

<?php

namespace App\Application\Services;

use App\Application\Contracts\AuthorizationInterface;
use App\Infrastructure\Persistence\Eloquent\Models\User;
use Illuminate\Validation\UnauthorizedException;

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
            if ($roleIter->name === $role || $roleIter->name === 'admin') {
                return true;
            }
        }

        return false;
    }

    public function userPolicy(string $entityId, User $model): bool
    {
        $isValidAction = $entityId === $model->id || $this->hasRole($model, 'admin');

        if (!$isValidAction) {
            throw new UnauthorizedException('Unauthorized action.', 0);
        }

        return $isValidAction;
    }
}

<?php

namespace App\Application\Handlers\Role;

use App\Application\Commands\Role\RemovePermissionToRoleCommand;
use App\Application\Contracts\RoleUseCaseInterface;
use App\Domain\Entities\Role;

class RemovePermissionToRoleCommandHandler
{
    public function __construct(private RoleUseCaseInterface $roleUseCase)
    {
    }

    public function handle(RemovePermissionToRoleCommand $command): Role
    {
        return $this->roleUseCase->removeRolePermissions($command->getRoleId(), $command->getPermissions());
    }
}

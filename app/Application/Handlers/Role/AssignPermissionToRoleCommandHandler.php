<?php

namespace App\Application\Handlers\Role;

use App\Application\Commands\Role\AssignPermissionToRoleCommand;
use App\Application\Contracts\RoleUseCaseInterface;
use App\Domain\Entities\Role;

class AssignPermissionToRoleCommandHandler
{
    public function __construct(private RoleUseCaseInterface $roleUseCase)
    {
    }

    public function handle(AssignPermissionToRoleCommand $command): Role
    {
        return $this->roleUseCase->assignPermissionsToRole($command->getRoleId(), $command->getPermissions());
    }
}

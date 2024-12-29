<?php

namespace App\Application\Handlers\Permission;

use App\Application\Commands\Permission\CreatePermissionCommand;
use App\Application\Contracts\PermissionUseCaseInterface;
use App\Domain\Entities\Permission;

class CreatePermissionCommandHandler
{
    public function __construct(private PermissionUseCaseInterface $permissionUseCase)
    {
    }

    public function handle(CreatePermissionCommand $command): Permission
    {
        return $this->permissionUseCase->createPermission(new Permission(
            null,
            $command->getName(),
            $command->getDescription(),
        ));
    }
}

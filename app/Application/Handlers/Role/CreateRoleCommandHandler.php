<?php

namespace App\Application\Handlers\Role;

use App\Application\Commands\Role\CreateRoleCommand;
use App\Application\Contracts\RoleUseCaseInterface;
use App\Domain\Entities\Role;

class CreateRoleCommandHandler
{
    public function __construct(private RoleUseCaseInterface $roleUseCase)
    {
    }

    public function handle(CreateRoleCommand $command): Role
    {
        return $this->roleUseCase->createRole(new Role(
            null,
            $command->getName(),
            $command->getDescription(),
            null
        ));
    }
}

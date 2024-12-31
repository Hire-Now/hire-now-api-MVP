<?php

namespace App\Application\Handlers\Role;

use App\Domain\Entities\Role;
use App\Application\Contracts\RoleUseCaseInterface;
use App\Application\Commands\Role\FetchRoleInformationCommand;
use App\Application\Commands\Role\AssignPermissionToRoleCommand;

class FetchRoleInformationCommandHandler
{
    public function __construct(private RoleUseCaseInterface $roleUseCase)
    {
    }

    public function handle(FetchRoleInformationCommand $command): array
    {
        return $this->roleUseCase->fetchRoleByName($command->getRoles());
    }
}

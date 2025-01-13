<?php

namespace App\Application\Handlers\Role;

use App\Application\Commands\Role\FetchRoleInformationCommand;
use App\Application\Ports\Inbound\RoleManagementPort;

class FetchRoleInformationCommandHandler
{
    public function __construct(private RoleManagementPort $roleUseCase)
    {
    }

    public function handle(FetchRoleInformationCommand $command): array
    {
        return $this->roleUseCase->fetchRoleByName($command->getRoles());
    }
}

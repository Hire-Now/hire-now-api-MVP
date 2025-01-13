<?php

namespace App\Application\Handlers\Role;

use App\Application\Commands\Role\ListRolesCommand;
use App\Application\Ports\Inbound\RoleManagementPort;
use Illuminate\Database\Eloquent\Collection;

class ListRolesCommandHandler
{
    public function __construct(private RoleManagementPort $roleUseCase)
    {
    }

    public function handle(ListRolesCommand $command): Collection
    {
        return $this->roleUseCase->fetchRoles($command->getName(), $command->getStatus(), $command->getOrderBy(), $command->getOrderDirection());
    }
}

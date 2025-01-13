<?php

namespace App\Application\Handlers\Permission;

use App\Application\Commands\Permission\ListPermissionsCommand;
use App\Application\Ports\Inbound\PermissionManagementPort;
use Illuminate\Database\Eloquent\Collection;

class ListPermissionsCommandHandler
{
    public function __construct(private PermissionManagementPort $permissionUseCase)
    {
    }

    public function handle(ListPermissionsCommand $command): Collection
    {
        return $this->permissionUseCase->fetchPermissions($command->getName(), $command->getStatus(), $command->getOrderBy(), $command->getOrderDirection());
    }
}

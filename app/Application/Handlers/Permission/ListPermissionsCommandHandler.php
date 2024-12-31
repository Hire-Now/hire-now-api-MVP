<?php

namespace App\Application\Handlers\Permission;

use App\Application\Commands\Permission\ListPermissionsCommand;
use App\Application\Contracts\PermissionUseCaseInterface;
use Illuminate\Database\Eloquent\Collection;

class ListPermissionsCommandHandler
{
    public function __construct(private PermissionUseCaseInterface $permissionUseCase)
    {
    }

    public function handle(ListPermissionsCommand $command): Collection
    {
        return $this->permissionUseCase->fetchPermissions($command->getName(), $command->getStatus(), $command->getOrderBy(), $command->getOrderDirection());
    }
}

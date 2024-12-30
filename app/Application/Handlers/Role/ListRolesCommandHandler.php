<?php

namespace App\Application\Handlers\Role;

use App\Application\Commands\Role\CreateRoleCommand;
use App\Application\Commands\Role\ListRolesCommand;
use App\Application\Contracts\RoleUseCaseInterface;
use App\Domain\Entities\Role;
use App\Shared\Types\SearchRolesFilter;
use Illuminate\Database\Eloquent\Collection;

class ListRolesCommandHandler
{
    public function __construct(private RoleUseCaseInterface $roleUseCase)
    {
    }

    public function handle(ListRolesCommand $command): Collection
    {
        return $this->roleUseCase->fetchRoles($command->getName(), $command->getStatus(), $command->getOrderBy(), $command->getOrderDirection());
    }
}

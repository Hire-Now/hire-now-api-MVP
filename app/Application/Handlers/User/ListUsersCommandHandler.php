<?php

namespace App\Application\Handlers\User;

use App\Application\Commands\User\GenerateJWTUserCommand;
use App\Application\Commands\User\ListUsersCommand;
use App\Application\UseCases\UserUseCase;
use App\Domain\Entities\User;
use Illuminate\Database\Eloquent\Collection;

class ListUsersCommandHandler
{
    public function __construct(private UserUseCase $userUseCase)
    {
    }

    public function handle(ListUsersCommand $command): Collection
    {
        return $this->userUseCase->fetchUsers($command->getName(), $command->getStatus(), $command->getOrderBy(), $command->getOrderDirection());
    }
}

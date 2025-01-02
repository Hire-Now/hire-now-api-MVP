<?php

namespace App\Application\Handlers\User;

use App\Application\Commands\User\RemoveRoleToUserCommand;
use App\Application\UseCases\UserUseCase;
use App\Domain\Entities\User;

class RemoveRoleToUserCommandHandler
{
    public function __construct(private UserUseCase $userUseCase)
    {
    }

    public function handle(RemoveRoleToUserCommand $command): User
    {
        return $this->userUseCase->removeRolesToUser($command->getUserId(), $command->getRoles());
    }
}
